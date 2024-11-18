lucide.createIcons();

        // Toggle password visibility
        function togglePassword(button) {
            const input = button.parentElement.querySelector('input');
            const icon = button.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.setAttribute('data-lucide', 'eye-off');
            } else {
                input.type = 'password';
                icon.setAttribute('data-lucide', 'eye');
            }
            lucide.createIcons();
        }

        // Toggle between login and signup forms
        function toggleForm(formType) {
            const loginForm = document.getElementById('loginForm');
            const signupForm = document.getElementById('signupForm');
            
            if (formType === 'login') {
                loginForm.classList.remove('hidden');
                signupForm.classList.add('hidden');
            } else {
                loginForm.classList.add('hidden');
                signupForm.classList.remove('hidden');
            }
        }

        document.getElementById('login-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            let isValid = true;
            const emailInput = document.getElementById('login-email');
            const passwordInput = document.getElementById('login-password');
            const emailError = document.getElementById('email-error');
            const passwordError = document.getElementById('password-error');

            // Email validation
            if (!emailInput.value) {
                showError(emailError, 'Email is required');
                isValid = false;
            } else if (!isValidEmail(emailInput.value)) {
                showError(emailError, 'Please enter a valid email address');
                isValid = false;
            } else {
                hideError(emailError);
            }

            // Password validation
            if (!passwordInput.value) {
                showError(passwordError, 'Password is required');
                isValid = false;
            } else if (!isValidPassword(passwordInput.value)) {
                showError(passwordError, 'Password must be at least 8 characters long, contain at least one uppercase letter, three digits, and one special character');
                isValid = false;
            } else {
                hideError(passwordError);
            }

            if (isValid) {
                // Form is valid, you can submit it here
                console.log('Form is valid');
                // Uncomment the next line to submit the form
                // this.submit();
            }
        });

        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        function isValidPassword(password) {
            const passwordRegex = /^(?=.*[A-Z])(?=.*\d.*\d.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/;
            return passwordRegex.test(password);
        }

        function showError(element, message) {
            element.textContent = message;
            element.style.display = 'block';
        }

        function hideError(element) {
            element.textContent = '';
            element.style.display = 'none';
        }