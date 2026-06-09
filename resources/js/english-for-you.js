// DROPDOWN MENU
const profile = document.querySelector(".profile");
const dropdown = document.querySelector(".dropdown");

if(profile){

    profile.addEventListener("click", () => {

        dropdown.classList.toggle("active");

    });

}

// CLOSE OUTSIDE
window.addEventListener("click", (e) => {

    if(
        profile &&
        !profile.contains(e.target)
    ){

        dropdown.classList.remove("active");

    }

});

// WORD COUNT
const textarea = document.querySelector("textarea");
const wordCount = document.querySelector(".word-count");

if(textarea && wordCount){

    textarea.addEventListener("input", () => {

        let words = textarea.value
        .trim()
        .split(/\s+/);

        if(textarea.value.trim() == ""){

            wordCount.innerHTML = "Words: 0";

        }
        else{

            wordCount.innerHTML =
            "Words: " + words.length;

        }

    });

}

// CLASSIFICATION PANEL TOGGLE
const classifyToggle = document.querySelector('.classify-toggle');
const classificationPanel = document.querySelector('.classification-panel');

if(classifyToggle && classificationPanel){
    classifyToggle.addEventListener('click', () => {
        classificationPanel.style.display = classificationPanel.style.display === 'none' ? 'block' : 'none';
    });
}

// PRACTICE PANEL SWITCHING
const skillButtons = document.querySelectorAll(".practice-sidebar .menu-title[data-skill]");
const skillPanels = document.querySelectorAll(".skill-panel");

if(skillButtons.length && skillPanels.length){

    skillButtons.forEach(button => {

        button.addEventListener("click", () => {

            const skill = button.dataset.skill;

            skillButtons.forEach(btn => btn.closest(".menu-skill").classList.remove("active-skill"));
            button.closest(".menu-skill").classList.add("active-skill");

            skillPanels.forEach(panel => {
                panel.classList.toggle("active", panel.dataset.panel === skill);
            });

        });

    });


}

// AUTO-DISMISS SUBMISSIONS ALERT
const submissionsAlert = document.getElementById('submissions-alert');

if (submissionsAlert) {
    setTimeout(() => {
        submissionsAlert.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        submissionsAlert.style.opacity = '0';
        submissionsAlert.style.transform = 'translateY(-8px)';
        setTimeout(() => submissionsAlert.remove(), 500);
    }, 4000);
}

