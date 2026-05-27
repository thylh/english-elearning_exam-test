import './bootstrap';
import 'bootstrap';

// PAGE TRANSITION
document.addEventListener("DOMContentLoaded", () => {

    const links = document.querySelectorAll("a");

    links.forEach(link => {

        link.addEventListener("click", function (e) {

            const href = this.getAttribute("href");

            if (
                !href ||
                href.startsWith("#") ||
                href.startsWith("javascript") ||
                this.hasAttribute("target") ||
                href.includes("://") ||
                this.hasAttribute("download")
            ) {
                return;
            }

            e.preventDefault();

            document.body.classList.add("page-exit");

            setTimeout(() => {
                window.location.href = href;
            }, 250);
        });
    });
});

// AUTO HIDE ALERT
setTimeout(() => {

    document.querySelector('.auto-hide-alert')?.remove();

}, 3000);

// AJAX LOGIN
const loginForm = document.getElementById('loginForm');

if (loginForm) {

    loginForm.addEventListener('submit', async function (e) {

        e.preventDefault();

        const formData = new FormData(loginForm);

        const alertBox = document.getElementById('loginAlert');

        const submitBtn =
            loginForm.querySelector('button[type="submit"]');

        submitBtn.disabled = true;

        // CLEAR OLD ERRORS
        document.querySelectorAll('.validation-error')
            .forEach(el => el.remove());

        document.querySelectorAll('.is-invalid')
            .forEach(el => el.classList.remove('is-invalid'));

        try {

            const response = await fetch('/login', {

                method: 'POST',

                headers: {

                    'X-CSRF-TOKEN':
                        document.querySelector('meta[name="csrf-token"]')
                            ?.getAttribute('content'),

                    'Accept': 'application/json'
                },

                body: formData
            });

            const data = await response.json();

            if (!response.ok) {

                throw {
                    response: {
                        status: response.status,
                        data: data
                    }
                };
            }

            // SUCCESS
            if (data.success) {

                document.body.classList.add('page-exit');

                setTimeout(() => {

                    window.location.href = data.redirect;

                }, 250);

                return;
            }

            // LOGIN FAIL
            if (alertBox) {

                alertBox.innerHTML = `
                    <div class="alert alert-danger auto-hide-alert">
                        ${data.message || 'Login failed'}
                    </div>
                `;

                setTimeout(() => {
                    alertBox.innerHTML = '';
                }, 3000);
            }

        } catch (error) {

            // VALIDATION ERROR
            if (error.response?.status === 422) {

                const errors = error.response.data.errors;

                Object.keys(errors).forEach(field => {

                    const input =
                        document.querySelector(`[name="${field}"]`);

                    if (!input) return;

                    input.classList.add('is-invalid');

                    const wrapper =
                        input.closest('.input-wrapper') || input;

                    wrapper.insertAdjacentHTML('afterend', `
                        <small class="text-danger validation-error">
                            ${errors[field][0]}
                        </small>
                    `);
                });

            } else {

                console.error(error);

                if (alertBox) {

                    alertBox.innerHTML = `
                        <div class="alert alert-danger auto-hide-alert">
                            Something went wrong
                        </div>
                    `;

                    setTimeout(() => {
                        alertBox.innerHTML = '';
                    }, 3000);
                }
            }

        } finally {

            submitBtn.disabled = false;
        }
    });
}