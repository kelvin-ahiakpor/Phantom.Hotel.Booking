lucide.createIcons();

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

document.getElementById('signup-form').addEventListener('submit', function(e) {
  e.preventDefault();
  
  let isValid = true;
  const firstName = document.getElementById('first-name').value.trim();
  const lastName = document.getElementById('last-name').value.trim();
  const email = document.getElementById('email').value.trim();
  const password = document.getElementById('password').value;
  const confirmPassword = document.getElementById('confirm-password').value;

  // First Name validation
  if (firstName === ' ') {
      document.getElementById('first-name-error').textContent = 'First name is required';
      isValid = false;
  } else {
      document.getElementById('first-name-error').textContent = '';
  }

  // Last Name validation
  if (lastName === '') {
      document.getElementById('last-name-error').textContent = 'Last name is required';
      isValid = false;
  } else {
      document.getElementById('last-name-error').textContent = '';
  }

  // Email validation
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(email)) {
      document.getElementById('email-error').textContent = 'Please enter a valid email address';
      isValid = false;
  } else {
      document.getElementById('email-error').textContent = '';
  }

  // Password validation
  const passwordRegex = /^(?=.*[A-Z])(?=.*\d{3,})(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/;
  if (!passwordRegex.test(password)) {
      document.getElementById('password-error').textContent = 'Password must be at least 8 characters long, contain 1 uppercase letter, 3 digits, and 1 special character';
      isValid = false;
  } else {
      document.getElementById('password-error').textContent = '';
  }

  // Confirm Password validation
  if (password !== confirmPassword) {
      document.getElementById('confirm-password-error').textContent = 'Passwords do not match';
      isValid = false;
  } else {
      document.getElementById('confirm-password-error').textContent = '';
  }

  if (isValid) {
      alert('Form submitted successfully!');
      this.submit();
  }
});