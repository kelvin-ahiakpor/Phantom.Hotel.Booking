document.addEventListener("DOMContentLoaded", function () {
    // Lucide Icons initialization
    try {
        if (window.lucide && window.lucide.createIcons) {
            window.lucide.createIcons();
        } else {
            console.warn('Lucide icons library not loaded correctly');
        }
    } catch (error) {
        console.error('Error initializing Lucide icons:', error);
    }

    const loginForm = document.getElementById("login-form");
    const togglePasswordBtn = document.getElementById("togglePasswordBtn");
    const passwordInput = document.getElementById("password");

    // Password toggle functionality
    if (togglePasswordBtn && passwordInput) {
        const eyeOpenIcon = `
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>`;
            
        const eyeClosedIcon = `
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18M10.5 10.677a2 2 0 002.823 2.823M7.362 7.561A7.714 7.714 0 0012 7c4.478 0 8.268 2.943 9.542 7a7.714 7.714 0 01-1.904 3.439M9.88 9.88a7.714 7.714 0 00-7.422 2.12C2.732 7.943 6.523 5 12 5c4.478 0 8.268 2.943 9.542 7"/>
            </svg>`;

        // Set initial state
        togglePasswordBtn.innerHTML = eyeOpenIcon;
        passwordInput.type = "password";

        togglePasswordBtn.addEventListener('click', function() {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                togglePasswordBtn.innerHTML = eyeClosedIcon;
            } else {
                passwordInput.type = 'password';
                togglePasswordBtn.innerHTML = eyeOpenIcon;
            }
        });
    }

    // Form submission handler
    if (loginForm) {
        loginForm.addEventListener("submit", function (e) {
            e.preventDefault();
            
            const email = document.getElementById("email").value.trim();
            const password = passwordInput.value.trim();
            
            // Clear previous error messages
            const emailError = document.getElementById("email-error");
            const passwordError = document.getElementById("password-error");
            emailError.textContent = "";
            passwordError.textContent = "";
            
            // Input validation
            let isValid = true;
            
            if (!email) {
                emailError.textContent = "Email is required";
                isValid = false;
            } else if (!isValidEmail(email)) {
                emailError.textContent = "Invalid email format";
                isValid = false;
            }
            
            if (!password) {
                passwordError.textContent = "Password is required";
                isValid = false;
            }
            
            if (!isValid) return;
            
            // Send AJAX request
            fetch("../actions/loginUser.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ email, password })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = data.redirect;
                } else {
                    data.errors.forEach(error => {
                        if (error.field === "email") {
                            emailError.textContent = error.message;
                        } else if (error.field === "password") {
                            passwordError.textContent = error.message;
                        }
                    });
                }
            })
            .catch(error => {
                console.error("Error:", error);
            });
        });
    }

    // Email validation utility function
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
});