const modal = document.getElementById("defiModal");

document.querySelectorAll(".coursElem").forEach(elem => {
    elem.addEventListener("click", () => {
        modal.querySelector("#modalTitle").textContent = elem.dataset.title;
        modal.querySelector("#modalDescription").textContent = elem.dataset.description;
        modal.querySelector("#modalBanner").src = elem.dataset.banner;
        modal.querySelector("#modalLevel").textContent = elem.dataset.level;
        modal.querySelector("#modalXP").textContent = elem.dataset.xp;
        modal.querySelector("#deadlineP").style.display = "none"
        if (elem.dataset.deadline.length > 1) {
            modal.querySelector("#modalDeadline").textContent = elem.dataset.deadline;
            modal.querySelector("#deadlineP").style.opacity = 1;
            modal.querySelector("#deadlineP").style.pointerEvents = "initial";
        }
        modal.style.opacity = 1;
        modal.style.pointerEvents = "initial";
    });
});

window.onclick = e => {
    if (e.target === modal) {
        modal.style.opacity = 0;
        modal.style.pointerEvents = "none";
    }
};


document.querySelectorAll('.coursElem').forEach(elem => {
    elem.addEventListener('click', () => {
        const modal = document.getElementById('defiModal');
        modal.style.display = 'block';

        document.getElementById('modalTitle').textContent = elem.dataset.title;
        document.getElementById('modalDescription').textContent = elem.dataset.description;
        document.getElementById('modalBanner').src = elem.dataset.banner;
        document.getElementById('modalLevel').textContent = elem.dataset.level;
        document.getElementById('modalXP').textContent = elem.dataset.xp;
        document.getElementById('modalDeadline').textContent = elem.dataset.deadline || 'Aucune';

        const formInput = document.getElementById('defiIdInput');
        formInput.value = elem.dataset.id;
    });
});
