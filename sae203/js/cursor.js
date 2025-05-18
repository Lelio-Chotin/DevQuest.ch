
/* !CURSOR */
const cursor = document.querySelector('.cursor');

document.addEventListener('mousemove', e => {
    cursor.style.left = e.clientX + 'px';
    cursor.style.top = scrollY + e.clientY + 'px';
});

let mouseX = 0;
let mouseY = 0;
let currentX = 0;
let currentY = 0;

document.addEventListener('mousemove', e => {
    mouseX = e.clientX;
    mouseY = e.clientY;
});

function animate() {
    currentX += (mouseX - currentX) * 0.05;
    currentY += (mouseY - currentY) * 0.05;
    cursor.style.left = currentX + 'px';
    cursor.style.top = scrollY + currentY + 'px';

    requestAnimationFrame(animate);
}
animate();



document.addEventListener("mousemove", function (event) {
    const element = document.elementFromPoint(event.clientX, event.clientY);

    if (element) {
        const cursorStyle = window.getComputedStyle(element).cursor;
        const cursor = document.querySelector(".cursor")

        if (cursorStyle === "pointer") {
            cursor.style.width = "60px";
        }

        else {
            cursor.style.width = "20px";
        }
    }
});