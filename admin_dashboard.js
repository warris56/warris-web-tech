function searchEmployee() {
    var searchValue = document.getElementById('search-employee').value;
    if (searchValue.length > 2) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'search_employee.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (xhr.status === 200) {
                try {
                    var employee = JSON.parse(xhr.responseText);
                    if (employee && employee.name) {
                        document.getElementById('name').value = employee.name;
                        document.getElementById('email').value = employee.email;
                        document.getElementById('department').value = employee.department;
                        document.getElementById('salary').value = employee.salary;
                    } else {
                        clearForm();
                    }
                } catch (error) {
                    console.error('Error parsing JSON:', error);
                    clearForm();
                }
            }
        };
        xhr.send('id=' + encodeURIComponent(searchValue));
    } else {
        clearForm();
    }
}

function clearForm() {
    document.getElementById('name').value = '';
    document.getElementById('email').value = '';
    document.getElementById('department').value = '';
    document.getElementById('salary').value = '';
}





function clearForm() {function searchEmployee() {
    var searchValue = document.getElementById('search-employee').value;
    if (searchValue.length > 2) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'search_employee.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (xhr.status === 200) {
                var employee = JSON.parse(xhr.responseText);
                if (employee) {
                    document.getElementById('name').value = employee.name;
                    document.getElementById('email').value = employee.email;
                    document.getElementById('department').value = employee.department;
                    document.getElementById('salary').value = employee.salary;
                } else {
                    clearForm();
                }
            }
        };
        xhr.send('id=' + encodeURIComponent(searchValue));
    } else {
        clearForm();
    }
}

function fetchEmployeeDetails() {
    const employeeName = document.getElementById('employee-name').value;

    if (employeeName.length >= 2) {
        fetch(`search_employee.php?name=${employeeName}`)
            .then(response => response.json())
            .then(data => {
                const employeeDetailsDiv = document.getElementById('employee-details');
                employeeDetailsDiv.innerHTML = '';

                if (data.length > 0) {
                    data.forEach(employee => {
                        const option = document.createElement('div');
                        option.textContent = `${employee.name}`;
                        option.onclick = () => selectEmployee(employee);
                        employeeDetailsDiv.appendChild(option);
                    });
                } else {
                    employeeDetailsDiv.innerHTML = 'No employees found. Create new employee details below.';
                    // Optionally, show additional form fields to create a new employee
                }
            })
            .catch(error => console.error('Error:', error));
    }
}

function selectEmployee(employee) {
    document.getElementById('employee-name').value = employee.name;
    // Hide details div
    document.getElementById('employee-details').innerHTML = '';
}

function clearForm() {
    document.getElementById('name').value = '';
    document.getElementById('email').value = '';
    document.getElementById('department').value = '';
    document.getElementById('salary').value = '';
}

    document.getElementById('name').value = '';
    document.getElementById('email').value = '';
    document.getElementById('department').value = '';
    document.getElementById('salary').value = '';
}


function fetchEmployeeDetails() {
    const employeeName = document.getElementById('employee-name').value;

    if (employeeName.length >= 2) {
        fetch(`search_employee.php?name=${employeeName}`)
            .then(response => response.json())
            .then(data => {
                const employeeDetailsDiv = document.getElementById('employee-details');
                employeeDetailsDiv.innerHTML = '';

                if (data.length > 0) {
                    data.forEach(employee => {
                        const option = document.createElement('div');
                        option.textContent = `${employee.name}`;
                        option.onclick = () => selectEmployee(employee);
                        employeeDetailsDiv.appendChild(option);
                    });
                } else {
                    employeeDetailsDiv.innerHTML = 'No employees found. Create new employee details below.';
                    // Optionally, show additional form fields to create a new employee
                }
            })
            .catch(error => console.error('Error:', error));
    }
}

function selectEmployee(employee) {
    document.getElementById('employee-name').value = employee.name;
    // Hide details div
    document.getElementById('employee-details').innerHTML = '';
}


function clearForm() {
    document.getElementById('name').value = '';
    document.getElementById('email').value = '';
    document.getElementById('department').value = '';
    document.getElementById('salary').value = '';
}
