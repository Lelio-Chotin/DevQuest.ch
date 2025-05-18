function generateSteps() {
    const nbSteps = document.getElementById('nb_steps').value;
    const container = document.getElementById('steps-container');
    container.innerHTML = "";

    for (let i = 0; i < nbSteps; i++) {
        const stepDiv = document.createElement('div');
        stepDiv.className = 'step';
        stepDiv.innerHTML = `
            <h3>Étape ${i + 1}</h3>
            <input type="text" name="steps[${i}][title]" placeholder="Titre de l'étape" required>
            <label>Nombre d'xp gagné :</label>
            <input type="number" name="steps[${i}][xp_reward]" placeholder="XP de l'étape" value="0" required>
            <label>Nombre de leçons :</label>
            <input type="number" name="steps[${i}][nb_lessons]" value="1" min="1" max="20" onchange="generateLessons(${i}, this.value)">
            <div id="lessons-${i}"></div>
            <hr>
        `;
        container.appendChild(stepDiv);
        generateLessons(i, 1);
    }
}

function generateLessons(stepIndex, nbLessons) {
    const lessonContainer = document.getElementById(`lessons-${stepIndex}`);
    lessonContainer.innerHTML = "";
    for (let j = 0; j < nbLessons; j++) {
        const lessonHTML = `
        <div class="lesson">
            <h4>Leçon ${j + 1}</h4>
            <input type="text" name="steps[${stepIndex}][lessons][${j}][title]" placeholder="Titre de la leçon" required>
            <textarea name="steps[${stepIndex}][lessons][${j}][content]" placeholder="Contenu (.lio)" required></textarea>
        </div>`;
        lessonContainer.innerHTML += lessonHTML;
    }
}