function searchEmployee() {
    const searchValue = document.getElementById('search-employee').value.trim();
    const employeeSelect = document.getElementById('employee-select');
    employeeSelect.innerHTML = '<option value="">-- No Employee Selected --</option>';

    if (searchValue.length > 2) {
        fetch('search_employee_name.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'name=' + encodeURIComponent(searchValue)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            if (Array.isArray(data) && data.length > 0) {
                data.forEach(employee => {
                    const option = document.createElement('option');
                    option.value = JSON.stringify(employee); // Store full employee details
                    option.textContent = `${employee.name} (ID: ${employee.id})`;
                    employeeSelect.appendChild(option);
                });
                showPopupMessage('Employee found!', 'success'); // Show success pop-up
            } else {
                showPopupMessage('No employees found.', 'error'); // Show error pop-up
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

function populateEmployeeDetails() {
    const employeeSelect = document.getElementById('employee-select');
    const selectedOption = employeeSelect.options[employeeSelect.selectedIndex];

    if (selectedOption.value) {
        const employee = JSON.parse(selectedOption.value);
        document.getElementById('employee-id').value = employee.id;
        document.getElementById('employee-name').value = employee.name;
        document.getElementById('email').value = employee.email;
        document.getElementById('department').value = employee.department;
        document.getElementById('salary').value = employee.salary;
    } else {
        document.getElementById('employee-id').value = '';
        document.getElementById('employee-name').value = '';
        document.getElementById('email').value = '';
        document.getElementById('department').value = '';
        document.getElementById('salary').value = '';
    }
}

document.getElementById('createLoginForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission

    const formData = new FormData(this); // Collect form data

    fetch('create_employee_login.php', { // Make the POST request
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log(data); // Check what is being returned
        const messageDiv = document.getElementById('message');
        messageDiv.textContent = data.message;
        messageDiv.classList.add(data.status === 'success' ? 'popup-success' : 'popup-error');
        messageDiv.style.display = 'block';
        setTimeout(() => {
            messageDiv.style.display = 'none';
        }, 3000);
    })
    
    .catch(error => console.error('Error:', error)); // Handle any errors
});

// Function to show custom pop-up messages
function showPopupMessage(message, type) {
    const popup = document.createElement('div');
    popup.className = `popup ${type}`; // Add class based on type
    popup.textContent = message;
    document.body.appendChild(popup);

    // Automatically remove the popup after 3 seconds
    setTimeout(() => {
        popup.remove();
    }, 3000);
}