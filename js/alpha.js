document.addEventListener('DOMContentLoaded', function () {
    // Function to send command to the backend
    function sendCommand(url, data) {
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams(data)
        })
        .then(response => response.json())
        .then(data => console.log(data))
        .catch(error => console.error('Error:', error));
    }

    document.getElementById('toggleAutoLed').addEventListener('change', function () {
        const state = this.checked ? 'enable' : 'disable';
        sendCommand('/axl.com/API/control_led.php', {index: 'auto', state: state});
    });

    ['toggle0', 'toggle1', 'toggle2', 'toggle3', 'toggle4'].forEach((id, index) => {
        document.getElementById(id).addEventListener('change', function () {
            const state = this.checked ? 'on' : 'off';
            sendCommand('/axl.com/API/control_led.php', {index: index, state: state});
        });
    });

    // Fan Controls
    document.getElementById('toggleFan1').addEventListener('change', function () {
        const state = this.checked ? 'on' : 'off';
        sendCommand('/axl.com/API/control_led.php', {index: 'fan 1', state: state});
    });

    document.getElementById('toggleFan2').addEventListener('change', function () {
        const state = this.checked ? 'on' : 'off';
        sendCommand('/axl.com/API/control_led.php', {index: 'fan 2', state: state});
    });

    // Garage Controls
    document.getElementById('manualUp').addEventListener('click', function () {
        sendCommand('/axl.com/API/control_led.php', {index: 'manual', state: 'up'});
    });

    document.getElementById('manualDown').addEventListener('click', function () {
        sendCommand('/axl.com/API/control_led.php', {index: 'manual', state: 'down'});
    });

    // Function to fetch sensor data
    function fetchSensorData() {
        fetch('/axl.com/API/sensorData.php')
        .then(response => response.json())
        .then(data => {
            document.getElementById('outside-humidity').textContent = data.humidity + '%';
            document.getElementById('outside-temperature').textContent = data.temperature + '°C';
            document.getElementById('ldr-status').textContent = data.ldr;
            document.getElementById('irStatus').textContent = 'IR Sensor: ' + data.ir;
        })
        .catch(error => console.error('Error:', error));
    }

    // Fetch sensor data every 2 seconds
    setInterval(fetchSensorData, 2000);

    // Logout functionality
    document.getElementById('logoutButton').addEventListener('click', function () {
        // Implement logout functionality here
        alert('Logout clicked!');
    });
});
