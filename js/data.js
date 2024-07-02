// data.js

document.addEventListener('DOMContentLoaded', () => {
    const yearFilter = document.getElementById('year-filter');
    const searchInput = document.getElementById('search-input');
    const tableContainer = document.getElementById('reg-data-table');

    fetch('../php/fetch_data.php')
        .then(response => response.json())
        .then(data => {
            populateYearFilter(data);
            displayData(data);

            yearFilter.addEventListener('change', () => {
                filterData(data);
            });

            searchInput.addEventListener('input', () => {
                filterData(data);
            });
        });

    function populateYearFilter(data) {
        const years = [...new Set(data.map(entry => new Date(entry.date).getFullYear()))];
        years.sort().forEach(year => {
            const option = document.createElement('option');
            option.value = year;
            option.textContent = year;
            yearFilter.appendChild(option);
        });
    }

    function displayData(data) {
        tableContainer.innerHTML = '';
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
                <th>Non-Electric Vehicles</th>
                <th>Total Vehicles</th>
                <th>Percent Electric Vehicles</th>
            </tr>
        `;
        table.appendChild(thead);

        const tbody = document.createElement('tbody');
        data.forEach(entry => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${new Date(entry.date).toLocaleDateString()}</td>
                <td>${entry.county}</td>
                <td>${entry.state}</td>
                <td>${entry.vehicle_primary_use}</td>
                <td>${entry.electric_vehicle_ev_total}</td>
                <td>${entry.non_electric_vehicles}</td>
                <td>${entry.total_vehicles}</td>
                <td>${entry.percent_electric_vehicles}</td>
            `;
            tbody.appendChild(row);
        });

        table.appendChild(tbody);
        tableContainer.appendChild(table);
    }

    function filterData(data) {
        const selectedYear = yearFilter.value;
        const searchTerm = searchInput.value.toLowerCase();
        const filteredData = data.filter(entry => {
            const yearMatch = selectedYear === 'all' || new Date(entry.date).getFullYear() == selectedYear;
            const searchMatch = Object.values(entry).some(val => 
                val.toString().toLowerCase().includes(searchTerm)
            );
            return yearMatch && searchMatch;
        });
        displayData(filteredData);
    }
});
