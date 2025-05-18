function loadLessonContent(lessonId, skipScroll = false) {
    document.querySelectorAll('.lesson-button, .step-drawer li.lesson').forEach(btn => {
        btn.classList.remove('active');
    });

    const desktopBtn = document.querySelector(`.lesson-button[data-lesson-id='${lessonId}']`);
    if (desktopBtn) {
        desktopBtn.classList.add('active');
    }

    const drawerBtn = document.querySelector(`#lessonDrawer li.lesson[data-lesson-id='${lessonId}']`);
    if (drawerBtn) {
        drawerBtn.classList.add('active');
    }

    fetch("/php/loadLessons.php?lesson_id=" + lessonId)
        .then(response => response.text())
        .then(html => {
            document.getElementById("lesson-content").innerHTML = html;
            hljs.highlightAll();

            if (typeof enableAdminEditing === "function") {
                enableAdminEditing();
            }

            if (!skipScroll) {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });

    const overlay = document.getElementById('lessonOverlay');
    drawer.classList.remove('active');
    overlay.classList.remove('active');
}




function copyCode(button) {
    const code = button.closest(".code-container").querySelector("code");
    const text = code.innerText;

    navigator.clipboard.writeText(text).then(() => {
        button.textContent = "Copié !";
        setTimeout(() => button.textContent = "Copier", 1500);
    }).catch(err => {
        console.error("Erreur de copie :", err);
    });
}


function enableAdminEditing() {
    const btn = document.getElementById('edit-btn');
    const form = document.getElementById('edit-form');
    const parsed = document.getElementById('lesson-parsed');
    const cancelBtn = document.getElementById('cancel-btn');

    if (!btn || !form || !parsed) return;

    btn.addEventListener('click', () => {
        btn.style.display = 'none';
        form.style.display = 'block';
        parsed.style.display = 'none';
    });

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const content = document.getElementById('edit-content').value;
        const lessonId = form.dataset.lessonId;

        fetch('/php/saveLesson.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'id=' + encodeURIComponent(lessonId) + '&content=' + encodeURIComponent(content)
        })
            .then(r => r.text())
            .then(msg => {
                alert(msg);
                location.reload();
            });
    });

    if (cancelBtn) {
        cancelBtn.addEventListener('click', (e) => {
            e.preventDefault();
            form.style.display = 'none';
            parsed.style.display = 'block';
            btn.style.display = 'inline-block';
        });
    }
}

function toggleLessonValidation(lessonId, validate) {
    fetch("/php/toggleLessonValidation.php", {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'lesson_id=' + encodeURIComponent(lessonId) + '&validate=' + validate
    })
        .then(response => response.text())
        .then(msg => {
            const validatedBtn = document.querySelector(`.lesson-button[data-lesson-id='${lessonId}']`);
            const validatedDrawerBtn = document.querySelector(`#lessonDrawer li.lesson[data-lesson-id='${lessonId}']`);

            if (validate) {
                if (validatedBtn) validatedBtn.classList.add('validated');
                if (validatedDrawerBtn) validatedDrawerBtn.classList.add('validated');
            } else {
                if (validatedBtn) validatedBtn.classList.remove('validated');
                if (validatedDrawerBtn) validatedDrawerBtn.classList.remove('validated');
            }

            loadLessonContent(lessonId, true);
        });
}


