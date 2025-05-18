const btns = document.querySelectorAll("a");
const cTrans = document.querySelectorAll(".transitionClose .cTrans");

btns.forEach(btn => {
    btn.addEventListener("click", (e) => {
        e.preventDefault();

        cTrans.forEach(tr => {
            let delay = 0;
            if (tr.classList.contains("t2")) {
                delay = 0.15;
            } else if (tr.classList.contains("t3")) {
                delay = 0.3;
            }

            tr.style.animation = `.35s ease ${delay}s forwards toMiddle`;
        });

        setTimeout(() => {
            window.location.href = btn.href;
        }, 500);
    });
})

window.addEventListener("pageshow", (event) => {
    if (event.persisted) {
        window.location.reload();
    }
});
