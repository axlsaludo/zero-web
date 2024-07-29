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
        fetch('../API/toggleAllLeds.php', { method: 'POST' })
            .then(response => response.text())
            .then(result => console.log(result))
            .catch(error => console.error('Error toggling all LEDs:', error));
    });

    document.getElementById('toggleAllFans').addEventListener('click', function() {
        // Send request to toggle all fans
        fetch('toggle_all_fans.php', { method: 'POST' })
            .then(response => response.text())
            .then(result => console.log(result))
            .catch(error => console.error('Error toggling all fans:', error));
    });

    // Event listeners for individual LEDs
    document.querySelectorAll('.led-card input[type="checkbox"]').forEach((checkbox, index) => {
        checkbox.addEventListener('change', function() {
            const state = this.checked ? 1 : 0;
            fetch('update_led.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `ledIndex=${index}&state=${state}`
            })
                .then(response => response.text())
                .then(result => console.log(result))
                .catch(error => console.error('Error updating LED state:', error));
        });
    });

    // Event listeners for individual fans
    document.querySelectorAll('.fan-card input[type="checkbox"]').forEach((checkbox, index) => {
        checkbox.addEventListener('change', function() {
            const state = this.checked ? 1 : 0;
            fetch('update_fan.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `fanIndex=${index}&state=${state}`
            })
                .then(response => response.text())
                .then(result => console.log(result))
                .catch(error => console.error('Error updating fan state:', error));
        });
    });

    // Auto mode toggles
    document.getElementById('toggleAutoLed').addEventListener('change', function() {
        const state = this.checked ? 1 : 0;
        fetch('update_led_auto_mode.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `state=${state}`
        })
            .then(response => response.text())
            .then(result => console.log(result))
            .catch(error => console.error('Error updating LED auto mode:', error));
    });

    document.getElementById('autoModeToggle').addEventListener('change', function() {
        const state = this.checked ? 1 : 0;
        fetch('update_fan_auto_mode.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `state=${state}`
        })
            .then(response => response.text())
            .then(result => console.log(result))
            .catch(error => console.error('Error updating fan auto mode:', error));
    });

    document.getElementById('overrideToggle').addEventListener('change', function() {
        const state = this.checked ? 1 : 0;
        fetch('update_override_mode.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `state=${state}`
        })
            .then(response => response.text())
            .then(result => console.log(result))
            .catch(error => console.error('Error updating override mode:', error));
    });

    document.getElementById('autoToggle').addEventListener('change', function() {
        const state = this.checked ? 1 : 0;
        fetch('update_garage_auto_mode.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `state=${state}`
        })
            .then(response => response.text())
            .then(result => console.log(result))
            .catch(error => console.error('Error updating garage auto mode:', error));
    });
});
