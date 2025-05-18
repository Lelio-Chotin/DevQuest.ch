/* !HEADER */
const hNav = document.querySelectorAll("ul.nav > li > a");
const conn = document.querySelector("header .connexion")

hNav.forEach(li => {
    li.addEventListener("mouseover", (e) => {
        hNav.forEach(li => {
            li.classList.add("unselected")

            if (li === e.target) {
                li.classList.remove("unselected")
                li.classList.add("selected")
            }

        })
    })
    li.addEventListener("mouseleave", (e) => {
        hNav.forEach(li => {
            li.classList.remove("unselected")
            li.classList.remove("selected")
        })
    })
})


const header = document.querySelector("header");
let oldScroll = 0;

document.addEventListener("scroll", () => {
    if (oldScroll < window.scrollY) {
        header.classList.add("hidden");
    } else {
        header.classList.remove("hidden");
    }
    oldScroll = window.scrollY;
});


document.addEventListener('DOMContentLoaded', function () {
    const burger = document.querySelector('.burger');
    const mobileMenu = document.querySelector('.mobile-menu');
    const closeBtn = document.querySelector('.close-menu');

    burger.addEventListener('click', () => {
        mobileMenu.classList.add('active');
    });

    closeBtn.addEventListener('click', () => {
        mobileMenu.classList.remove('active');
    });

    mobileMenu.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            mobileMenu.classList.remove('active');
        });
    });
});









