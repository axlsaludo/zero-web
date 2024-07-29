import serial
import requests
import json

# Configure serial port
ser = serial.Serial('COM3', 9600, timeout=1)  # Adjust COM port and baud rate as needed

# Backend URL
backend_url = 'http://localhost/your_php_script.php'  # Replace with your PHP backend URL

def send_data_to_backend(data):
    """Send JSON data to PHP backend."""
    try:
        response = requests.post(backend_url, data=json.dumps(data), headers={'Content-Type': 'application/json'})
        if response.status_code == 200:
            print("Data sent successfully.")
        else:
            print(f"Failed to send data. Status code: {response.status_code}")
    except Exception as e:
        print(f"Error sending data: {e}")

def process_data(data):
    """Decode the Arduino data and prepare it for sending."""
    try:
        lines = data.split(',')
        sensor_data = {}
        for line in lines:
            if ':' in line:
                key, value = line.split(':', 1)
                sensor_data[key.strip()] = value.strip()
        return sensor_data
    except Exception as e:
        print(f"Error processing data: {e}")
        return {}

def main():
    while True:
        if ser.in_waiting > 0:
            raw_data = ser.readline().decode('utf-8').strip()
            print(f"Raw data received: {raw_data}")
            sensor_data = process_data(raw_data)
            print(f"Processed data: {sensor_data}")
            send_data_to_backend(sensor_data)

if __name__ == "__main__":
    main()
