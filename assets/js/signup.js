document.addEventListener("DOMContentLoaded", () => {
  // Initialize Lucide icons if available
  try {
    if (window.lucide && window.lucide.createIcons) {
      window.lucide.createIcons();
    } else {
      console.warn("Lucide icons library not loaded correctly");
    }
  } catch (error) {
    console.error("Error initializing Lucide icons:", error);
  }

  // DOM Elements
  const signupForm = document.getElementById("signup-form");
  const togglePasswordBtn = document.getElementById("togglePasswordBtn");
  const togglePasswordConfirmBtn = document.getElementById("togglePasswordConfirmBtn");
  const passwordInput = document.getElementById("password");
  const passwordConfirmInput = document.getElementById("confirm-password");

  // Configuration
  const config = {
    passwordRegex: /^(?=.*[A-Z])(?=.*\d{3,})(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/,
    emailRegex: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
    phoneRegex: /^\+?[\d\s-]{10,}$/,
    allowedUserTypes: ['guest', 'owner']
  };

  // SVG Icons
  const icons = {
    eyeOpen: `
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
          </svg>`,
    eyeClosed: `
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18M10.5 10.677a2 2 0 002.823 2.823M7.362 7.561A7.714 7.714 0 0012 7c4.478 0 8.268 2.943 9.542 7a7.714 7.714 0 01-1.904 3.439M9.88 9.88a7.714 7.714 0 00-7.422 2.12C2.732 7.943 6.523 5 12 5c4.478 0 8.268 2.943 9.542 7"/>
          </svg>`
  };

  // Password Toggle Functionality
  function setupPasswordToggle(toggleBtn, input) {
    if (toggleBtn && input) {
      toggleBtn.innerHTML = icons.eyeOpen;
      input.type = "password";

      toggleBtn.addEventListener("click", function () {
        if (input.type === "password") {
          input.type = "text";
          toggleBtn.innerHTML = icons.eyeClosed;
        } else {
          input.type = "password";
          toggleBtn.innerHTML = icons.eyeOpen;
        }
      });
    }
  }

  // Set up password toggles
  setupPasswordToggle(togglePasswordBtn, passwordInput);
  setupPasswordToggle(togglePasswordConfirmBtn, passwordConfirmInput);

  // Form Validation Functions
  function validateRequired(value, fieldName) {
    return value ? "" : `${fieldName} is required`;
  }

  function validateEmail(email) {
    return config.emailRegex.test(email) ? "" : "Please enter a valid email address";
  }

  function validatePhone(phone) {
    return !phone || config.phoneRegex.test(phone) ? "" : "Please enter a valid phone number";
  }

  function validatePassword(password) {
    if (!password) return "Password is required";
    if (!config.passwordRegex.test(password)) {
      return "Password must be at least 8 characters long, contain 1 uppercase letter, 3 digits, and 1 special character";
    }
    return "";
  }

  function validatePasswordMatch(password, confirmPassword) {
    if (!confirmPassword) return "Please confirm your password";
    return password === confirmPassword ? "" : "Passwords do not match";
  }

  function validateUserType(userType) {
    return config.allowedUserTypes.includes(userType) ? "" : "Please select a valid account type";
  }

  // Form Handling
  if (signupForm) {
    signupForm.addEventListener("submit", async function (e) {
      e.preventDefault();

      // Get form values
      const formData = {
        firstName: document.getElementById("first-name").value.trim(),
        lastName: document.getElementById("last-name").value.trim(),
        email: document.getElementById("email").value.trim(),
        phone: document.getElementById("phone").value.trim(),
        password: passwordInput.value.trim(),
        confirmPassword: passwordConfirmInput.value.trim(),
        userType: document.getElementById("user-type").value.trim()
      };

      // Clear previous errors
      const errors = {
        'first-name': validateRequired(formData.firstName, "First name"),
        'last-name': validateRequired(formData.lastName, "Last name"),
        'email': validateEmail(formData.email),
        'phone': validatePhone(formData.phone),
        'password': validatePassword(formData.password),
        'confirm-password': validatePasswordMatch(formData.password, formData.confirmPassword),
        'user-type': validateUserType(formData.userType)
      };

      // Update error displays and check validity
      let isValid = true;
      Object.entries(errors).forEach(([field, error]) => {
        const errorElement = document.getElementById(`${field}-error`);
        if (errorElement) {
          errorElement.textContent = error;
          if (error) isValid = false;
        }
      });

      if (!isValid) return;

      // Prepare request data
      const requestData = {
        first_name: formData.firstName,
        last_name: formData.lastName,
        email: formData.email,
        phone: formData.phone,
        password: formData.password,
        confirm_password: formData.confirmPassword,
        user_type: formData.userType
      };

      try {
        const response = await fetch("../actions/registerUser.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json"
          },
          body: JSON.stringify(requestData)
        });

        const data = await response.json();

        if (data.success) {
          window.location.href = "../view/login.php";
        } else {
          data.errors.forEach((error) => {
            const errorElement = document.getElementById(`${error.field}-error`);
            if (errorElement) {
              errorElement.textContent = error.message;
            }
          });
        }
      } catch (error) {
        console.error("Error submitting the form:", error);
        // Show a general error message to the user
        const generalError = document.createElement('div');
        generalError.className = 'text-red-500 text-sm mt-4';
        generalError.textContent = 'An error occurred. Please try again later.';
        signupForm.appendChild(generalError);
      }
    });

    // Real-time validation on input
    const inputs = signupForm.querySelectorAll('input, select');
    inputs.forEach(input => {
      input.addEventListener('input', function () {
        const errorElement = document.getElementById(`${this.id}-error`);
        if (errorElement) {
          errorElement.textContent = '';
        }
      });
    });
  }
});
