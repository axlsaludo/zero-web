document.addEventListener('DOMContentLoaded', function () {
    function sendCommand(url, data) {
        console.log('Sending command to:', url, 'with data:', data);
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

    const toggleAutoLed = document.getElementById('toggleAutoLed');
    if (toggleAutoLed) {
        toggleAutoLed.addEventListener('change', function () {
            const state = this.checked ? 'enable' : 'disable';
            sendCommand('/axl.com/API/control_led.php', {index: 'auto', state: state});
        });
    } else {
        console.error('Element with ID "toggleAutoLed" not found.');
    }

    ['toggle0', 'toggle1', 'toggle2', 'toggle3', 'toggle4'].forEach((id, index) => {
        const element = document.getElementById(id);
        if (element) {
            element.addEventListener('change', function () {
                const state = this.checked ? 'on' : 'off';
                sendCommand('/axl.com/API/control_led.php', {index: index, state: state});
            });
        } else {
            console.error('Element with ID', id, 'not found.');
        }
    });

    // Fan Controls
    const toggleFan1 = document.getElementById('toggleFan1');
    if (toggleFan1) {
        toggleFan1.addEventListener('change', function () {
            const state = this.checked ? 'on' : 'off';
            sendCommand('/axl.com/API/control_led.php', {index: 'fan1', state: state});
        });
    } else {
        console.error('Element with ID "toggleFan1" not found.');
    }

    const toggleFan2 = document.getElementById('toggleFan2');
    if (toggleFan2) {
        toggleFan2.addEventListener('change', function () {
            const state = this.checked ? 'on' : 'off';
            sendCommand('/axl.com/API/control_led.php', {index: 'fan2', state: state});
        });
    } else {
        console.error('Element with ID "toggleFan2" not found.');
    }

    // Garage Controls
    const manualUp = document.getElementById('manualUp');
    if (manualUp) {
        manualUp.addEventListener('click', function () {
            sendCommand('/axl.com/API/control_led.php', {index: 'manual', state: 'up'});
        });
    } else {
        console.error('Element with ID "manualUp" not found.');
    }

    const manualDown = document.getElementById('manualDown');
    if (manualDown) {
        manualDown.addEventListener('click', function () {
            sendCommand('/axl.com/API/control_led.php', {index: 'manual', state: 'down'});
        });
    } else {
        console.error('Element with ID "manualDown" not found.');
    }

    function fetchSensorData() {
        console.log('Fetching sensor data...');
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
    document.querySelector('form[action="../API/logout.php"]').addEventListener('submit', function (event) {
        event.preventDefault(); // Prevent default form submission
        alert('Logout clicked!');
        // Implement additional logout logic here if needed
    });
});
