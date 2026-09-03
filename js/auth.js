function switchTab(tab) {
    document.getElementById('login-form').classList.remove('active');
    document.getElementById('register-form').classList.remove('active');
    document.getElementById('toggle-login').classList.remove('active');
    document.getElementById('toggle-register').classList.remove('active');

    if (tab === 'login') {
        document.getElementById('login-form').classList.add('active');
        document.getElementById('toggle-login').classList.add('active');
    } else {
        document.getElementById('register-form').classList.add('active');
        document.getElementById('toggle-register').classList.add('active');
    }
}

function handleForgotPassword(event) {
    event.preventDefault();
    const email = document.getElementById('login-email').value;
    if (email) {
        alert("Password reset instructions sent to: " + email);
    } else {
        alert("Please enter your email address in the field above first.");
    }
}

function validateLogin(event) {
    event.preventDefault();
    alert("Login client-side validation passed. Ready for PHP backend.");
    return true;
}

function validateRegister(event) {
    event.preventDefault();
    const pwd = document.getElementById('reg-password').value;
    const confirmPwd = document.getElementById('reg-confirm-password').value;
    const errorMsg = document.getElementById('password-error');

    if (pwd !== confirmPwd) {
        errorMsg.style.display = 'block';
        return false;
    }
    
    errorMsg.style.display = 'none';
    alert("Registration client-side validation passed. Ready for PHP backend.");
    return true;
}