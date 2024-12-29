function deleteEmployee() {
    var employeeId = document.getElementById('id').value;
    if (employeeId) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'delete_employee.php?id=' + employeeId, true);
        xhr.onload = function () {
            if (xhr.status === 200) {
                // No need for alert or further handling, redirection will be managed by PHP
                clearForm();
            } else {
                alert('Error deleting employee: ' + xhr.statusText);
            }
        };
        xhr.send();
    } else {
        alert('Please search for an employee first');
    }
}
