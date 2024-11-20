document.addEventListener("DOMContentLoaded", () => {
  try {
    if (window.lucide && window.lucide.createIcons) {
      window.lucide.createIcons();
    } else {
      console.warn("Lucide icons library not loaded correctly");
    }
  } catch (error) {
    console.error("Error initializing Lucide icons:", error);
  }

  const signupForm = document.getElementById("signup-form");
  const togglePasswordBtn = document.getElementById("togglePasswordBtn");
  const togglePasswordConfirmBtn = document.getElementById("togglePasswordConfirmBtn");
  const passwordInput = document.getElementById("password");
  const passwordConfirmInput = document.getElementById("confirm-password");

  // Password toggle functionality
  if (togglePasswordBtn && passwordInput ) {
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

    togglePasswordBtn.addEventListener("click", function () {
      if (passwordInput.type === "password") {
        passwordInput.type = "text";
        togglePasswordBtn.innerHTML = eyeClosedIcon;
      } else {
        passwordInput.type = "password";
        togglePasswordBtn.innerHTML = eyeOpenIcon;
      }
    });
  }

   // Password toggle functionality
   if (togglePasswordConfirmBtn && passwordConfirmInput ) {
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
    togglePasswordConfirmBtn.innerHTML = eyeOpenIcon;
    passwordConfirmInput.type = "password";

    togglePasswordConfirmBtn.addEventListener("click", function () {
      if (passwordConfirmInput.type === "password") {
        passwordConfirmInput.type = "text";
        togglePasswordConfirmBtn.innerHTML = eyeClosedIcon;
      } else {
        passwordConfirmInput.type = "password";
        togglePasswordConfirmBtn.innerHTML = eyeOpenIcon;
      }
    });
  }

  if (signupForm) {
    signupForm.addEventListener("submit", function (e) {
      e.preventDefault();

      const firstName = document.getElementById("first-name").value.trim();
      const lastName = document.getElementById("last-name").value.trim();
      const email = document.getElementById("email").value.trim();
      const password = passwordInput.value.trim();
      const confirmPassword = passwordConfirmInput.value.trim();

      // Clear previous error messages
      const firstNameError = document.getElementById("first-name-error");
      const lastNameError = document.getElementById("last-name-error");
      const emailError = document.getElementById("email-error");
      const passwordError = document.getElementById("password-error");
      const confirmPasswordError = document.getElementById("confirm-password-error");

      // Input validation
      let isValid = true;

      // First Name validation
      if (!firstName) {
        firstNameError.textContent = "First name is required";
        isValid = false;
      } else {
        firstNameError.textContent = "";
      }

      // Last Name validation
      if (!lastName) {
        lastNameError.textContent = "Last name is required";
        isValid = false;
      } else {
        lastNameError.textContent = "";
      }

      // Email validation
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(email)) {
        emailError.textContent = "Please enter a valid email address";
        isValid = false;
      } else {
        emailError.textContent = "";
      }

      // Password validation
      const passwordRegex =
        /^(?=.*[A-Z])(?=.*\d{3,})(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/;
      if (!passwordRegex.test(password)) {
        passwordError.textContent =
          "Password must be at least 8 characters long, contain 1 uppercase letter, 3 digits, and 1 special character";
        isValid = false;
      } else {
        passwordError.textContent = "";
      }

      // Confirm Password validation
      if (!confirmPassword) {
        confirmPasswordError.textContent = "Please confirm your password";
        isValid = false;
      } else if (password !== confirmPassword) {
        confirmPasswordError.textContent = "Passwords do not match";
        isValid = false;
      } else {
        confirmPasswordError.textContent = "";
      }

      if (!isValid) return;

      // AJAX Request
      fetch("../actions/registerUser.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          "first-name": firstName,
          "last-name": lastName,
          email,
          password,
          "confirm-password": confirmPassword,
        }),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            window.location.href = "../view/login.php";
          } else {
            data.errors.forEach((error) => {
              const errorElement = document.getElementById(`${error.field}-error`);
              if (errorElement) errorElement.textContent = error.message;
            });
          }
        })
        .catch((error) => {
          console.error("Error submitting the form:", error);
        });
    });
  }
});