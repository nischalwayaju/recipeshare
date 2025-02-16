document.addEventListener("DOMContentLoaded", function() {
  const form = document.querySelector("form");
  const emailInput = document.querySelector("input[name='email']");
  const passwordInput = document.querySelector("input[name='password']");
  const confirmPasswordInput = document.querySelector("input[name='confirmpassword']");
  const termsCheckbox = document.querySelector("input[id='remember']");
  const passwordError = document.getElementById("password_error");

  form.addEventListener("submit", function(event) {
    let valid = true;
    passwordError.textContent = "";

    // Email validation
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(emailInput.value)) {
      alert("Please enter a valid email address.");
      valid = false;
    }

    // Password validation
  const passwordPattern = /^(?=.*[A-Za-z])(?=.*\d).{8,}$/;

    if (!passwordPattern.test(passwordInput.value)) {
      alert("Password must be at least 8 characters long and include a combination of letters and numbers.");
      valid = false;
    }

    // Confirm password validation
    if (confirmPasswordInput && passwordInput.value !== confirmPasswordInput.value) {
      passwordError.textContent = "Passwords do not match.";
      valid = false;
    }

    // Terms and conditions checkbox validation
    if (termsCheckbox && !termsCheckbox.checked) {
      alert("You must agree to the terms and conditions.");
      valid = false;
    }

    if (!valid) {
      event.preventDefault();
    }
  });
});
