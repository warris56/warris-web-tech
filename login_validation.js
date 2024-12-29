document.getElementById('loginForm').addEventListener('submit', function(event) {
    var username = document.getElementById('username').value;
    var password = document.getElementById('password').value;
    var errorDisplay = document.getElementById('error-display');
    errorDisplay.style.display = 'none';
    errorDisplay.innerText = '';

    if (username === '' || password === '') {
        errorDisplay.style.display = 'block';
        errorDisplay.innerText = 'Please fill in all fields.';
        event.preventDefault();
    }
});


window.addEventListener('DOMContentLoaded', (event) => {
     var errorMessage = "<?php echo isset($_SESSION['error']) ? $_SESSION['error'] : ''; ?>";
    if (errorMessage) {
        document.getElementById('error-display').style.display = 'block';
        document.getElementById('error-display').innerText = errorMessage;
    }
});
