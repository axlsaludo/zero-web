document.addEventListener('DOMContentLoaded', () => {
    const tableContainer = document.getElementById('reg-data-table');
    const yearFilter = document.getElementById('year-filter');
    const paginationContainer = document.getElementById('pagination');

    if (!tableContainer) {
        console.error('Element with ID "reg-data-table" not found.');
        return; // Exit if element is not found
    }

    let allData = []; // Store all fetched data here
    let currentPage = 1; // Current page, starting with page 1
    const itemsPerPage = 17; // Number of items displayed per page

    // Fetch data from PHP endpoint
    fetch('../db/data.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text(); // Fetch as text first
        })
        .then(data => {
            data = data.trim(); // Trim whitespace
            console.log('Data fetched:', data);
            try {
                allData = JSON.parse(data); // Parse JSON data
                renderTableAndPagination(allData); // Display table and pagination initially
            } catch (error) {
                console.error('JSON parsing error:', error);
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
        });

    // Event listener for year filter change
    yearFilter.addEventListener('change', () => {
        currentPage = 1; // Reset currentPage to 1 when filter changes

        const selectedYear = yearFilter.value;

        if (selectedYear === 'all') {
            renderTableAndPagination(allData); // Display all data if 'All' is selected
        } else {
            const filteredData = allData.filter(entry => {
                return new Date(entry.date).getFullYear().toString() === selectedYear;
            });
            renderTableAndPagination(filteredData); // Display filtered data by selected year
        }
    });

    // Wait for storeDataButton to be available in the DOM
    const checkStoreDataButton = () => {
        const storeDataButton = document.getElementById('storeDataBtn');
        if (storeDataButton) {
            storeDataButton.addEventListener('click', () => {
                sendDataToServer(allData); // Send all fetched data to storeData.php
            });
        } else {
            setTimeout(checkStoreDataButton, 100); // Check again after 100ms if storeDataButton is not found
        }
    };

    checkStoreDataButton(); // Initial call to start checking

    // Function to render table and pagination
    function renderTableAndPagination(data) {
        // Clear previous content
        tableContainer.innerHTML = '';
        paginationContainer.innerHTML = '';

        // Calculate total number of pages based on data length and items per page
        const totalPages = Math.ceil(data.length / itemsPerPage);

        // Create table structure
        const table = document.createElement('table');
        table.classList.add('data-table');

        const thead = document.createElement('thead');
        thead.innerHTML = `
            <tr>
                <th>Date</th>
                <th>County</th>
                <th>State</th>
                <th>Vehicle Primary Use</th>
                <th>EV Total</th>
                <th>Non-EV</th>
                <th>Total Vehicles</th>
                <th>Percent EV</th>
            </tr>
        `;
        table.appendChild(thead);

        const tbody = document.createElement('tbody');
        const start = (currentPage - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        const pageData = data.slice(start, end);
        pageData.forEach(entry => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${formatDate(entry.date)}</td>
                <td>${entry.county}</td>
                <td>${entry.state}</td>
                <td>${entry.vehicle_primary_use}</td>
                <td>${entry.electric_vehicle_ev_total}</td>
                <td>${entry.non_electric_vehicles}</td>
                <td>${entry.total_vehicles}</td>
                <td>${formatPercentage(entry.percent_electric_vehicles)}</td>
            `;
            tbody.appendChild(row);
        });

        table.appendChild(tbody);
        tableContainer.appendChild(table);

        // Create pagination controls
        const pagination = document.createElement('div');
        pagination.classList.add('pagination');

        // First page button
        const firstButton = createPaginationButton('First', 1);
        pagination.appendChild(firstButton);

        // Previous page button
        const prevButton = createPaginationButton('Prev', currentPage - 1);
        pagination.appendChild(prevButton);

        // Page numbers
        const visiblePages = 8; // Number of visible page numbers
        const startPage = Math.max(1, currentPage - Math.floor(visiblePages / 2));
        const endPage = Math.min(totalPages, startPage + visiblePages - 1);
        
        for (let i = startPage; i <= endPage; i++) {
            const pageButton = createPaginationButton(i.toString(), i);
            if (i === currentPage) {
                pageButton.classList.add('active');
            }
            pagination.appendChild(pageButton);
        }

        // Next page button
        const nextButton = createPaginationButton('Next', currentPage + 1);
        pagination.appendChild(nextButton);

        // Last page button
        const lastButton = createPaginationButton('Last', totalPages);
        pagination.appendChild(lastButton);

        paginationContainer.appendChild(pagination);

        // Event listener for pagination buttons
        pagination.addEventListener('click', event => {
            if (event.target.tagName.toLowerCase() === 'button') {
                const page = parseInt(event.target.dataset.page);
                if (page) {
                    currentPage = page;
                    renderTableAndPagination(data); // Re-render table and pagination for the selected page
                }
            }
        });
    }

    // Function to create pagination buttons
    function createPaginationButton(text, page) {
        const button = document.createElement('button');
        button.innerText = text;
        button.dataset.page = page;
        return button;
    }

    // Function to format date as "Month Day, Year" (e.g., April 20, 2021)
    function formatDate(dateString) {
        const options = { month: 'long', day: 'numeric', year: 'numeric' };
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', options);
    }

    // Function to format percentage with two decimal places (if needed)
    function formatPercentage(percent) {
        return parseFloat(percent).toFixed(2) + '%';
    }

    // Function to send data to storeData.php
    function sendDataToServer(data) {
        // Convert data to JSON string
        const jsonData = JSON.stringify(data);

        fetch('../API/data/storeData.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: jsonData, // Send the JSON string as the request body
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(responseData => {
            console.log('Data stored successfully:', responseData);
            alert('Data stored successfully.'); // Example alert message
        })
        .catch(error => {
            console.error('Error storing data:', error);
            alert('Error storing data. Please try again.'); // Example alert message
        });
    }

});
