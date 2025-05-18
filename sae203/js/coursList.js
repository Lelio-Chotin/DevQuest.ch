let selectedCategory = 'Parcours Complet';

document.addEventListener('DOMContentLoaded', function () {
    if (window.innerWidth <= 1024) {
        filterCourses();
    }
});


function applyFilter(category, button = null) {
    selectedCategory = category;

    if (window.innerWidth > 1024) return;

    document.querySelectorAll('.filter-buttons button').forEach(btn => {
        btn.classList.remove('active');
    });

    if (button) {
        button.classList.add('active');
    }

    filterCourses();
}

document.getElementById('searchInput').addEventListener('input', filterCourses);

function filterCourses() {
    const search = document.getElementById('searchInput').value.toLowerCase();
    const courses = document.querySelectorAll('.coursElem');

    const carousel = document.querySelector('.carousel-container');
    const annexSection = document.querySelector('ul.annexes');
    const titleWeb = document.querySelector('.carouselOverflow h1');
    const titleAnnexe = document.querySelector('.annex-title');


    if (selectedCategory === 'Parcours Complet') {
        carousel.style.display = 'block';
        titleWeb.style.display = 'block';
        annexSection.style.display = 'none';
        titleAnnexe.style.display = 'none';
    } else if (selectedCategory === 'Parcours Annexe') {
        carousel.style.display = 'none';
        titleWeb.style.display = 'none';
        annexSection.style.display = 'grid';
        titleAnnexe.style.display = 'block';
    } else {
        carousel.style.display = 'block';
        titleWeb.style.display = 'block';
        annexSection.style.display = 'grid';
        titleAnnexe.style.display = 'block';
    }

    courses.forEach(course => {
        const title = course.dataset.title;
        const category = course.dataset.category;

        const matchSearch = title.includes(search);
        const matchCategory = (selectedCategory === 'all' || category === selectedCategory);

        course.parentElement.style.display = (matchSearch && matchCategory) ? 'block' : 'none';
    });
}
