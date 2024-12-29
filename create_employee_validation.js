document.getElementById('createEmployeeForm').addEventListener('submit', function(event) {
    var name = document.getElementById('name').value;
    var position = document.getElementById('position').value;
    var department = document.getElementById('department').value;
    var salary = document.getElementById('salary').value;
    var username = document.getElementById('username').value;
    var password = document.getElementById('password').value;

    if (name === '' || position === '' || department === '' || salary === '' || username === '' || password === '') {
        alert('Please fill in all fields.');
        event.preventDefault();
    }
});
