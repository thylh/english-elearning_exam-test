import './bootstrap';
import 'bootstrap';
document.addEventListener("DOMContentLoaded", () => {

    const links = document.querySelectorAll("a");

    links.forEach(link => {

        link.addEventListener("click", function (e) {

            const href = this.getAttribute("href");

            if (
                href &&
                !href.startsWith("#") &&
                !href.startsWith("javascript")
            ) {

                e.preventDefault();

                document.body.classList.add("page-exit");

                setTimeout(() => {

                    window.location.href = href;

                }, 250);
            }
        });
    });
});
// setTimeout(() => {

//     document.querySelector('.auto-hide-alert')?.remove();

// }, 3000);