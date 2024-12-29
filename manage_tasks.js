// Display the message
function displayMessage(content, isSuccess) {
    const messageDiv = document.getElementById('message');
    messageDiv.textContent = content;
    messageDiv.classList.remove('popup-success', 'popup-error'); // Remove previous classes
    messageDiv.classList.add(isSuccess ? 'popup-success' : 'popup-error'); // Add new class
    messageDiv.style.display = 'block';

    // Automatically hide the message after 3 seconds
    setTimeout(() => {
        messageDiv.style.display = 'none';
    }, 3000);
}

// Call this function with appropriate parameters
displayMessage('Your task has been assigned successfully!', true);
