document.addEventListener('DOMContentLoaded', function() {
    // Function to fetch sensor data from PHP backend
    function fetchSensorData() {
        fetch('../API/fetchSensorData.php')
            .then(response => response.json())
            .then(data => {
                // Update HTML elements with the fetched data
                document.getElementById('outside-humidity').textContent = `${data.humidity}%`;
                document.getElementById('outside-temperature').textContent = `${data.temperature}°C`;
                document.getElementById('ldr-status').textContent = data.ldr;
                document.getElementById('irStatus').textContent = `IR Sensor: ${data.ir}`;
                
                // Update LED and Fan statuses
                document.getElementById('toggle0').checked = data.led[0];
                document.getElementById('toggle1').checked = data.led[1];
                document.getElementById('toggle2').checked = data.led[2];
                document.getElementById('toggle3').checked = data.led[3];
                document.getElementById('toggle4').checked = data.led[4];

                document.getElementById('toggleFan1').checked = data.fan[0];
                document.getElementById('toggleFan2').checked = data.fan[1];

                // Update Auto Mode toggles
                document.getElementById('toggleAutoLed').checked = data.ledAutoMode;
                document.getElementById('autoModeToggle').checked = data.fanAutoMode;
                document.getElementById('overrideToggle').checked = data.overrideMode;
                document.getElementById('autoToggle').checked = data.garageAutoMode;
            })
            .catch(error => console.error('Error fetching sensor data:', error));
    }

    // Fetch data every 5 seconds
    setInterval(fetchSensorData, 5000);

    // Add event listeners for buttons and toggles
    document.getElementById('toggleAllLEDs').addEventListener('click', function() {
        // Send request to toggle all LEDs
        fetch('http://localhost:8000', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'toggleLED', ledIndex: 'all', state: '1' })
        })
            .then(response => response.json())
            .then(result => console.log(result))
            .catch(error => console.error('Error toggling all LEDs:', error));
    });

    document.getElementById('toggleAllFans').addEventListener('click', function() {
        // Send request to toggle all fans
        fetch('http://localhost:8000', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'toggleFan', fanIndex: 'all', state: '1' })
        })
            .then(response => response.json())
            .then(result => console.log(result))
            .catch(error => console.error('Error toggling all fans:', error));
    });

    // Event listeners for individual LEDs
    document.querySelectorAll('.led-card input[type="checkbox"]').forEach((checkbox, index) => {
        checkbox.addEventListener('change', function() {
            const state = this.checked ? '1' : '0';
            fetch('http://localhost:8000', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'toggleLED', ledIndex: index, state: state })
            })
                .then(response => response.json())
                .then(result => console.log(result))
                .catch(error => console.error('Error updating LED state:', error));
        });
    });

    // Event listeners for individual fans
    document.querySelectorAll('.fan-card input[type="checkbox"]').forEach((checkbox, index) => {
        checkbox.addEventListener('change', function() {
            const state = this.checked ? '1' : '0';
            fetch('http://localhost:8000', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'toggleFan', fanIndex: index, state: state })
            })
                .then(response => response.json())
                .then(result => console.log(result))
                .catch(error => console.error('Error updating fan state:', error));
        });
    });

    // Auto mode toggles
    document.getElementById('toggleAutoLed').addEventListener('change', function() {
        const state = this.checked ? '1' : '0';
        fetch('http://localhost:8000', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'updateAutoMode', mode: 'led', state: state })
        })
            .then(response => response.json())
            .then(result => console.log(result))
            .catch(error => console.error('Error updating LED auto mode:', error));
    });

    document.getElementById('autoModeToggle').addEventListener('change', function() {
        const state = this.checked ? '1' : '0';
        fetch('http://localhost:8000', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'updateAutoMode', mode: 'fan', state: state })
        })
            .then(response => response.json())
            .then(result => console.log(result))
            .catch(error => console.error('Error updating fan auto mode:', error));
    });

    document.getElementById('overrideToggle').addEventListener('change', function() {
        const state = this.checked ? '1' : '0';
        fetch('http://localhost:8000', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'updateOverrideMode', state: state })
        })
            .then(response => response.json())
            .then(result => console.log(result))
            .catch(error => console.error('Error updating override mode:', error));
    });

    document.getElementById('autoToggle').addEventListener('change', function() {
        const state = this.checked ? '1' : '0';
        fetch('http://localhost:8000', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'updateAutoMode', mode: 'garage', state: state })
        })
            .then(response => response.json())
            .then(result => console.log(result))
            .catch(error => console.error('Error updating garage auto mode:', error));
    });
});


// Function to update LED state
function updateLEDState(ledIndex, state) {
    fetch('http://localhost:8000/', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            action: 'toggleLED',
            ledIndex: ledIndex,
            state: state
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok ' + response.statusText);
        }
        return response.json();
    })
    .then(data => {
        console.log('LED update successful:', data);
    })
    .catch(error => {
        console.error('Error updating LED state:', error);
    });
}

// Function to fetch sensor data
function fetchSensorData() {
    fetch('http://localhost:8000/fetchSensorData.php')
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok ' + response.statusText);
        }
        return response.json();
    })
    .then(data => {
        console.log('Sensor data:', data);
    })
    .catch(error => {
        console.error('Error fetching sensor data:', error);
    });
}
