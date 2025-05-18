document.addEventListener('DOMContentLoaded', function () {
    const sortButtons = document.querySelectorAll('.sort-options button');
    const usersContainer = document.querySelector('.leader');
    const searchInput = document.getElementById("search");

    let currentSortBy = 'xp';
    let currentSearchValue = '';

    function sortAndFilterUsers() {
        const sortOrder = (currentSortBy === 'alphabetical') ? 'ASC' : 'DESC';

        fetch(`?sortBy=${currentSortBy}&sortOrder=${sortOrder}`)
            .then(response => response.json())
            .then(data => {
                const filteredUsers = data.filter(user => {
                    const name = (user.prenom + ' ' + user.nom).toLowerCase();
                    const statut = user.statut.toLowerCase();
                    const xp = user.xp.toString().toLowerCase();

                    return name.includes(currentSearchValue) || statut.includes(currentSearchValue) || xp.includes(currentSearchValue);
                });

                usersContainer.innerHTML = '';

                filteredUsers.forEach((user, index) => {
                    const userHTML = `
                        <div class="user">
                            <div class="left">
                                <img class="icon" src="./uploads/pfp/${user.pfp}" alt="profile pic">
                                <div class="text">
                                    <span class="statut">${user.statut}</span>
                                    <div class="nom">${user.prenom} ${user.nom}, ${user.age}</div>
                                    <p class="xp">XP : ${user.xp}</p>
                                </div>
                            </div>
                            <div class="classement">
                                <span class="num">${index + 1}</span>
                            </div>
                        </div>
                    `;
                    usersContainer.innerHTML += userHTML;
                });

                animateUsersOnScroll();
            })
            .catch(error => {
                console.error('Erreur AJAX:', error);
            });
    }

    sortButtons.forEach(button => {
        button.addEventListener('click', function () {
            currentSortBy = this.getAttribute('data-sort');
            sortAndFilterUsers();
        });
    });

    searchInput.addEventListener("input", function () {
        currentSearchValue = this.value.toLowerCase();
        sortAndFilterUsers();
    });

    sortAndFilterUsers();


});


function animateUsersOnScroll() {
    const users = document.querySelectorAll(".user");
    let visibleIndex = 0;

    users.forEach((user) => {
        const rect = user.getBoundingClientRect();
        const triggerPoint = window.innerHeight;

        if (rect.top <= triggerPoint && !user.classList.contains("animated")) {
            setTimeout(() => {
                user.style.scale = 1;
                user.style.opacity = "1";
            }, visibleIndex * 100);

            user.classList.add("animated");
            visibleIndex++;
        } else if (rect.top >= window.innerHeight && user.classList.contains("animated")) {
            user.style.scale = .35;
            user.style.opacity = "0";
            user.classList.remove("animated");
        }
    });
}



window.addEventListener("scroll", animateUsersOnScroll);
