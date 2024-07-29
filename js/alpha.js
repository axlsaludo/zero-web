document.addEventListener('DOMContentLoaded', () => {
    const fetchData = async () => {
        try {
            const response = await fetch('http://localhost/axl.com/API/sensorData.php');
            const data = await response.json();
            document.getElementById('outside-humidity').textContent = `${data.humidity}%`;
            document.getElementById('outside-temperature').textContent = `${data.temperature}°C`;
            document.getElementById('ldr-status').textContent = data.ldr;
            document.getElementById('irStatus').textContent = `IR Sensor: ${data.ir}`;
        } catch (error) {
            console.error('Error fetching sensor data:', error);
        }
    };

    const sendCommand = async (command) => {
        try {
            await fetch('http://localhost/axl.com/API/updateSensor.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ command }),
            });
        } catch (error) {
            console.error('Error sending command:', error);
        }
    };

    // Set up event listeners for controls
    document.getElementById('toggleAllLEDs').addEventListener('click', () => {
        sendCommand('toggle all leds');
    });

    document.getElementById('toggleAllFans').addEventListener('click', () => {
        sendCommand('toggle all fans');
    });

    // Fetch initial data
    fetchData();

    // Periodically update sensor data
    setInterval(fetchData, 1000); // Update every 5 seconds
});


document.addEventListener('DOMContentLoaded', () => {
    const toggleElements = document.querySelectorAll('.led-card input[type="checkbox"]');
    
    toggleElements.forEach(element => {
        element.addEventListener('change', async (event) => {
            const ledIndex = event.target.id.replace('toggle', '');
            const state = event.target.checked ? 'on' : 'off';
            await sendLEDCommand(ledIndex, state);
        });
    });

    document.getElementById('toggleAllLEDs').addEventListener('click', async () => {
        await sendLEDCommand('all', 'toggle');
    });
});

async function sendLEDCommand(ledIndex, state) {
    try {
        const response = await fetch('/API/control_led.php', { // Ensure this path matches your PHP file location
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded', // Set content type for form data
            },
            body: new URLSearchParams({
                'index': ledIndex,
                'state': state
            })
        });

        if (response.ok) {
            const result = await response.json();
            console.log(result.message); // Adjust this to handle response data as needed
        } else {
            console.error('Error controlling LED:', response.statusText);
        }
    } catch (error) {
        console.error('Error:', error);
    }
}
