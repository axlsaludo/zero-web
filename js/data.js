// data.js

document.addEventListener('DOMContentLoaded', function () {
    // Fetch data from the API
    fetch('https://data.wa.gov/resource/3d5d-sdqb.json')
        .then(response => response.json())
        .then(data => {
            window.tableData = data; // Store data globally
            window.sortOrder = {}; // Track sort order for each column
            setupPagination(data); // Setup pagination
            displayData(1); // Display first page of data
        })
        .catch(error => console.error('Error fetching data:', error));
});

function displayData(page) {
    const itemsPerPage = 15; // Changed to 20 items per page
    const startIndex = (page - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;

    // Get selected year from the dropdown
    const selectedYear = document.getElementById('year-filter').value;

    // Filter data based on the selected year
    let filteredData = window.tableData;
    if (selectedYear !== 'all') {
        filteredData = window.tableData.filter(item => {
            const year = new Date(item.date).getFullYear().toString();
            return year === selectedYear;
        });
    }

    // Apply pagination to filtered data
    const paginatedData = filteredData.slice(startIndex, endIndex);
    populateTable(paginatedData);
}


function populateTable(data) {
    const tableContainer = document.getElementById('reg-data-table');
    tableContainer.innerHTML = ''; // Clear existing table

    const table = document.createElement('table');
    table.classList.add('data-table');

    // Create the header row
    const headerRow = document.createElement('tr');
    const headers = ['Date', 'County', 'State', 'Non EV', 'Total Vehicles', 'Percent of No of Vehicles'];
    headers.forEach((headerText, index) => {
        const header = document.createElement('th');
        header.textContent = headerText;
        header.style.cursor = 'pointer';
        header.addEventListener('click', () => sortTable(index));
        headerRow.appendChild(header);
    });
    table.appendChild(headerRow);

    // Create rows for each data entry
    data.forEach(item => {
        const row = document.createElement('tr');
        const date = new Date(item.date);
        const formattedDate = date.toLocaleString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
        const county = item.county;
        const state = item.state;
        const nonElectricVehicles = item.non_electric_vehicles;
        const totalVehicles = item.total_vehicles;
        const percentNonElectric = ((nonElectricVehicles / totalVehicles) * 100).toFixed(2) + '%';

        const rowData = [formattedDate, county, state, nonElectricVehicles, totalVehicles, percentNonElectric];
        rowData.forEach(text => {
            const cell = document.createElement('td');
            cell.textContent = text;
            row.appendChild(cell);
        });

        table.appendChild(row);
    });

    tableContainer.appendChild(table);
}

// Updated sortTable function to sort by year

function sortTable(columnIndex) {
    const isNumericColumn = columnIndex === 3 || columnIndex === 4;
    const columnKey = ['date', 'county', 'state', 'non_electric_vehicles', 'total_vehicles', 'percent_non_electric'][columnIndex];

    // Determine sort order
    window.sortOrder[columnKey] = !window.sortOrder[columnKey];

    window.tableData.sort((a, b) => {
        let valueA, valueB;

        if (columnIndex === 0) { // Date column
            // Extract year from date strings
            const yearA = parseInt(a.date.substring(0, 4));
            const yearB = parseInt(b.date.substring(0, 4));
            valueA = yearA;
            valueB = yearB;
        } else if (columnIndex === 1) { // County column
            valueA = a.county;
            valueB = b.county;
        } else if (columnIndex === 2) { // State column
            valueA = a.state;
            valueB = b.state;
        } else if (columnIndex === 3) { // Non Electric Vehicles column
            valueA = a.non_electric_vehicles;
            valueB = b.non_electric_vehicles;
        } else if (columnIndex === 4) { // Total Vehicles column
            valueA = a.total_vehicles;
            valueB = b.total_vehicles;
        } else if (columnIndex === 5) { // Percent of No of Vehicles column
            valueA = (a.non_electric_vehicles / a.total_vehicles) * 100;
            valueB = (b.non_electric_vehicles / b.total_vehicles) * 100;
        }

        if (isNumericColumn) {
            return window.sortOrder[columnKey] ? valueA - valueB : valueB - valueA;
        } else {
            if (columnIndex === 0) { // Sort by year
                return window.sortOrder[columnKey] ? valueA - valueB : valueB - valueA;
            } else {
                return window.sortOrder[columnKey] ? valueA.localeCompare(valueB) : valueB.localeCompare(valueA);
            }
        }
    });

    displayData(1); // Re-display sorted data starting from page 1
}

function setupPagination(data) {
    const itemsPerPage = 15;
    const totalPages = Math.ceil(data.length / itemsPerPage);
    const maxButtons = 8; // Maximum number of pagination buttons to show

    const paginationContainer = document.createElement('div');
    paginationContainer.classList.add('pagination');

    // Calculate which buttons to display based on current page
    let startPage = 1;
    let endPage = totalPages;
    if (totalPages > maxButtons) {
        const currentPage = 1; // Replace with your logic to get current page
        const halfMaxButtons = Math.floor(maxButtons / 2);
        startPage = Math.max(currentPage - halfMaxButtons, 1);
        endPage = startPage + maxButtons - 1;
        if (endPage > totalPages) {
            endPage = totalPages;
            startPage = Math.max(endPage - maxButtons + 1, 1);
        }
    }

    // Add "First" button
    const firstButton = document.createElement('button');
    firstButton.textContent = 'First';
    firstButton.addEventListener('click', function () {
        displayData(1);
    });
    paginationContainer.appendChild(firstButton);

    // Add buttons for pages
    for (let i = startPage; i <= endPage; i++) {
        const button = document.createElement('button');
        button.textContent = i;
        button.addEventListener('click', function () {
            displayData(i);
        });
        paginationContainer.appendChild(button);
    }

    // Add "Last" button
    const lastButton = document.createElement('button');
    lastButton.textContent = 'Last';
    lastButton.addEventListener('click', function () {
        displayData(totalPages);
    });
    paginationContainer.appendChild(lastButton);

    document.body.appendChild(paginationContainer);

    // Ensure current page is moved to the front if needed
    const currentPage = 1; // Replace with your logic to get current page
    if (currentPage > 1 && currentPage <= totalPages) {
        const currentButton = paginationContainer.querySelector(`button:nth-child(${currentPage + 1 - startPage})`);
        if (currentButton) {
            paginationContainer.insertBefore(currentButton, paginationContainer.firstChild.nextSibling);
        }
    }
}
