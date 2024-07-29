// Function to toggle all LED checkboxes
function toggleAllLEDs() {
    // Get the button and all LED checkboxes
    const toggleAllButton = document.getElementById('toggleAllLEDs');
    const ledCheckboxes = document.querySelectorAll('.led-buttons input[type="checkbox"]');
    
    // Determine if the toggleAllButton is currently checked or not
    const shouldCheck = !toggleAllButton.classList.contains('checked');
    
    // Update button state
    if (shouldCheck) {
        toggleAllButton.classList.add('checked');
    } else {
        toggleAllButton.classList.remove('checked');
    }
    
    // Toggle all LED checkboxes
    ledCheckboxes.forEach(checkbox => {
        checkbox.checked = shouldCheck;
    });
}

// Event listener for the "Toggle All LEDs" button
document.getElementById('toggleAllLEDs').addEventListener('click', toggleAllLEDs);


// Function to toggle all fan checkboxes
function toggleAllFans() {
    // Get the button and all fan checkboxes
    const toggleAllButton = document.getElementById('toggleAllFans');
    const fanCheckboxes = document.querySelectorAll('.fan-buttons input[type="checkbox"]');
    
    // Determine if the toggleAllButton is currently checked or not
    const shouldCheck = !toggleAllButton.classList.contains('checked');
    
    // Update button state
    if (shouldCheck) {
        toggleAllButton.classList.add('checked');
    } else {
        toggleAllButton.classList.remove('checked');
    }
    
    // Toggle all fan checkboxes
    fanCheckboxes.forEach(checkbox => {
        checkbox.checked = shouldCheck;
    });
}

// Event listener for the "Toggle All Fans" button
document.getElementById('toggleAllFans').addEventListener('click', toggleAllFans);

// Function to format date as DD MMMM
function formatDate(date) {
    const options = { day: '2-digit', month: 'long' };
    return date.toLocaleDateString('en-US', options);
}

// Function to set the current date and message
function setCurrentDateAndMessage() {
    const dateElement = document.getElementById('date');
    const messageElement = document.getElementById('message');
    const today = new Date();
    const formattedDate = formatDate(today);
    
    // Set date
    dateElement.textContent = formattedDate;
    
    // Set message
    messageElement.textContent = `Today is ${formattedDate}. Have a great day!`;
}

// Call the function to set the date and message when the page loads
document.addEventListener('DOMContentLoaded', setCurrentDateAndMessage);

// Function to handle logout
function handleLogout() {
    // Implement your logout logic here
    window.location.href = 'login.html'; // Redirect to a login page
}

// Add event listener to the Logout button
document.getElementById('logoutButton').addEventListener('click', handleLogout);



// garage 

// Function to handle mode toggles
function handleModeToggle(mode) {
    const modeCheckbox = document.getElementById(mode + 'Toggle');
    if (modeCheckbox.checked) {
        console.log(`${mode.charAt(0).toUpperCase() + mode.slice(1)} Mode Enabled`);
        // Implement mode-specific logic here
    } else {
        console.log(`${mode.charAt(0).toUpperCase() + mode.slice(1)} Mode Disabled`);
        // Implement logic for disabling mode here
    }
}

// Add event listeners for mode toggles
document.getElementById('overrideToggle').addEventListener('change', () => handleModeToggle('override'));
document.getElementById('manualToggle').addEventListener('change', () => handleModeToggle('manual'));
document.getElementById('autoToggle').addEventListener('change', () => handleModeToggle('auto'));

// Function to update IR sensor status
function updateIRSensor(detecting) {
    const irCard = document.querySelector('.card.ir-sensor');
    const irStatus = document.getElementById('irStatus');

    if (detecting) {
        irCard.classList.add('detecting');
        irStatus.textContent = 'IR Sensor: Detecting';
    } else {
        irCard.classList.remove('detecting');
        irStatus.textContent = 'IR Sensor: Not Detecting';
    }
}

// Example usage to simulate IR sensor detection (for testing purposes)
setTimeout(() => updateIRSensor(true), 5000);  // Simulate detecting after 2 seconds
setTimeout(() => updateIRSensor(false), 5000); // Simulate not detecting after 5 seconds


// Function to handle "Up" button click
document.getElementById('manualUp').addEventListener('click', function() {
    console.log("Manual Up button clicked");
    // Implement logic for "Up" button here
});

// Function to handle "Down" button click
document.getElementById('manualDown').addEventListener('click', function() {
    console.log("Manual Down button clicked");
    // Implement logic for "Down" button here
});
