import serial
import requests
import json

# Serial connection settings
ser = serial.Serial('COM3', 9600)  # Replace 'COM3' with the appropriate port

# URL of the PHP endpoint
php_url = 'http://localhost/axl.com/API/insertData.php'  # Corrected URL

def send_data_to_php(data):
    try:
        response = requests.post(php_url, json=data)
        response.raise_for_status()  # Raise an exception for HTTP errors
        print(f"Data sent successfully: {data}")
    except requests.RequestException as e:
        print(f"Error sending data: {e}")

def main():
    while True:
        if ser.in_waiting > 0:
            line = ser.readline().decode('utf-8').strip()
            print(f"Received: {line}")

            # Parse sensor data
            data = {}
            parts = line.split(',')
            for part in parts:
                if ':' in part:  # Check if part contains ':'
                    try:
                        key, value = part.split(':')
                        data[key] = value
                    except ValueError:
                        print(f"Warning: Skipping malformed part '{part}'")

            # Send data to PHP
            send_data_to_php(data)

if __name__ == '__main__':
    main()
