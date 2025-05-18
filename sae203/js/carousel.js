document.addEventListener("DOMContentLoaded", () => {
    const list = document.querySelector('.carousel-list');
    const items = list.querySelectorAll('li');
    const prevBtn = document.querySelector('.prev');
    const nextBtn = document.querySelector('.next');

    const itemWidth = items[0].offsetWidth + 40;
    const visibleCount = Math.floor(document.querySelector('.carousel-container').offsetWidth / itemWidth);
    const totalItems = items.length;

    let currentIndex = 0;
    let currentTranslate = 0;
    let startX = 0;
    let isDragging = false;

    function updateCarousel() {
        const translateX = -(currentIndex * itemWidth);
        list.style.transform = `translateX(${translateX}px)`;
        list.style.transition = 'transform 0.5s ease';
        currentTranslate = translateX;
    }

    // Boutons
    nextBtn.addEventListener('click', () => {
        if (currentIndex < totalItems - visibleCount) {
            currentIndex++;
            updateCarousel();
        }
    });

    prevBtn.addEventListener('click', () => {
        if (currentIndex > 0) {
            currentIndex--;
            updateCarousel();
        }
    });


    list.addEventListener('mousedown', dragStart);
    list.addEventListener('touchstart', dragStart, {passive: true});

    list.addEventListener('mousemove', dragMove);
    list.addEventListener('touchmove', dragMove, {passive: true});

    list.addEventListener('mouseup', dragEnd);
    list.addEventListener('mouseleave', dragEnd);
    list.addEventListener('touchend', dragEnd);

    function dragStart(e) {
        const target = e.target;

        if (target.closest('a')) {
            isDragging = false;
            return;
        }

        isDragging = true;
        list.style.transition = 'none';
        startX = getPositionX(e);
    }


    function dragMove(e) {
        if (!isDragging) return;
        const currentX = getPositionX(e);
        const diff = currentX - startX;
        const move = currentTranslate + diff;
        list.style.transform = `translateX(${move}px)`;
    }

    function dragEnd(e) {
        if (!isDragging) return;
        isDragging = false;
        const endX = getPositionX(e);
        const movedBy = endX - startX;

        if (movedBy < -50 && currentIndex < totalItems - visibleCount) {
            currentIndex++;
        } else if (movedBy > 50 && currentIndex > 0) {
            currentIndex--;
        }

        updateCarousel();
    }

    function getPositionX(e) {
        return e.type.includes('mouse') ? e.pageX : e.touches[0].clientX;
    }


    window.addEventListener("resize", updateCarousel);
});