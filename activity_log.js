// Toggle high contrast mode
function toggleHighContrast() {
    document.body.classList.toggle('high-contrast');
}

// Add event listener to toggle high contrast mode button
const highContrastToggle = document.getElementById('high-contrast-toggle');
if (highContrastToggle) {
    highContrastToggle.addEventListener('click', toggleHighContrast);
}

// Sort table by column
function sortTable(column) {
    var table = document.getElementById('activity-logs-table');
    var rows = Array.from(table.rows).slice(1); // Get all table rows except the header
    var sortedRows;

    // Sort rows based on column
    sortedRows = rows.sort(function(a, b) {
        var aValue = a.cells[column].textContent;
        var bValue = b.cells[column].textContent;

        // Handle date sorting for the timestamp column
        if (column === 3) { // Assuming the timestamp is in the 4th column (index 3)
            aValue = new Date(aValue).getTime();
            bValue = new Date(bValue).getTime();
        }

        if (aValue < bValue) {
            return -1;
        } else if (aValue > bValue) {
            return 1;
        } else {
            return 0;
        }
    });

    // Clear the table body and append sorted rows
    var tbody = table.tBodies[0];
    tbody.innerHTML = ''; // Clear existing rows
    sortedRows.forEach(function(row) {
        tbody.appendChild(row); // Append sorted rows
    });
}

// Add event listeners to table headers for sorting
var tableHeaders = document.querySelectorAll('#activity-logs-table th');
tableHeaders.forEach(function(header, index) {
    header.addEventListener('click', function() {
        sortTable(index);
    });
});

// Filter table by search input
function filterTable() {
    var searchInput = document.getElementById('search-input');
    var filterValue = searchInput.value.toUpperCase();
    var tableRows = document.getElementById('activity-logs-table').rows;

    for (var i = 1; i < tableRows.length; i++) {
        var row = tableRows[i];
        var rowText = row.textContent.toUpperCase();

        if (rowText.indexOf(filterValue) > -1) {
            row.style.display = ''; // Show row
        } else {
            row.style.display = 'none'; // Hide row
        }
    }
}

// Add event listener to search input
document.getElementById('search-input').addEventListener('input', filterTable);