import serial
import requests
import json
import time

# Configuration
SERIAL_PORT = 'COM3'  # Adjust to your actual serial port
BAUD_RATE = 9600
COMMAND_FILE = 'led_command.txt'
API_URL = 'http://localhost/axl.com/API/sensorData.php'

def read_sensor_data_from_serial(ser):
    """Read sensor data from Arduino via serial port."""
    if ser.in_waiting > 0:
        data = ser.readline().decode('utf-8').strip()
        return data
    return None

def send_data_to_php(data):
    """Send JSON data to PHP script."""
    try:
        headers = {'Content-Type': 'application/json'}
        response = requests.post(API_URL, headers=headers, json=data)
        response.raise_for_status()  # Check for HTTP errors

        # Print raw response content for debugging
        print('Raw response content:', response.text)

        # Attempt to parse JSON
        response_data = response.json()
        print('Data sent successfully:', response_data)

    except requests.exceptions.RequestException as e:
        print(f'Error sending data: {e}')
    except json.JSONDecodeError:
        print('Failed to decode JSON:', response.text)

def read_command():
    """Read the command from the command file."""
    try:
        with open(COMMAND_FILE, 'r') as file:
            command = file.read().strip()
        return command
    except FileNotFoundError:
        return None

def send_command_to_arduino(command, ser):
    """Send command to Arduino."""
    try:
        ser.write((command + '\n').encode())
        print(f"Command sent to Arduino: {command}")
    except serial.SerialException as e:
        print(f'Error sending command to Arduino: {e}')

def main():
    """Main loop to read sensor data and handle commands."""
    try:
        with serial.Serial(SERIAL_PORT, BAUD_RATE, timeout=1) as ser:
            while True:
                # Read sensor data
                sensor_data = read_sensor_data_from_serial(ser)
                if sensor_data:
                    try:
                        # Attempt to decode JSON data
                        data = json.loads(sensor_data)
                        # Send data to PHP script
                        send_data_to_php(data)
                    except json.JSONDecodeError:
                        print(f'Failed to decode JSON: {sensor_data}')

                # Read command and send to Arduino
                command = read_command()
                if command:
                    send_command_to_arduino(command, ser)
                    # Clear the command file after sending
                    open(COMMAND_FILE, 'w').close()

                time.sleep(1)  # Adjust the sleep time as needed

    except serial.SerialException as e:
        print(f'Error opening serial port: {e}')

if __name__ == '__main__':
    main()
