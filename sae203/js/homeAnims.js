/* !ANIMS */
const texts = document.querySelectorAll("h1.titleText");

setTimeout(() => {
    texts.forEach((titleText, i) => {
        setTimeout(() => {
            titleText.style.animation = ".35s ease-out forwards txtToUp";
        }, i*150)
    })
}, 50)

setTimeout(() => {
    document.querySelector("h1.titleText span").style.color = "var(--c-main)";
    document.querySelector("a.start").style.transform = "translate(-50%, -50%)";
}, 700)

setTimeout(() => {
    document.querySelector("header").style.transform = "translateX(-50%)";
}, 150)




const revealGroups = document.querySelectorAll(".revealcontainer");
const users = [...document.querySelectorAll(".user")]
users.unshift(document.querySelector(".userCards"))

window.addEventListener("scroll", () => {
    revealGroups.forEach(group => {
        const lines = group.querySelectorAll(".reveal-on-scroll");

        lines.forEach((el, i) => {
            const rect = el.getBoundingClientRect();
            const triggerPoint = window.innerHeight + 60;

            if (rect.bottom <= triggerPoint && !el.classList.contains("animated")) {
                setTimeout(() => {
                    el.style.animation = ".25s ease-out forwards txtToUp";
                }, i * 150);
                el.classList.add("animated");
            } else if (rect.top >= triggerPoint && el.classList.contains("animated")) {
                el.style.animation = "";
                el.classList.remove("animated");
            }
        });
    });

    users.forEach((user, i) => {
        const rect = user.getBoundingClientRect();
        const triggerPoint = window.innerHeight + 60;

        if (rect.bottom <= triggerPoint && !user.classList.contains("animated")) {
            setTimeout(() => {
                user.style.scale = 1;
                user.style.opacity = 1;
            }, i * 100);
            user.classList.add("animated");
        } else if (rect.top >= triggerPoint && user.classList.contains("animated")) {
            user.style.scale = .35;
            user.style.opacity = 0;
            user.classList.remove("animated");
        }
    })
});

