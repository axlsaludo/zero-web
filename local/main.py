import http.server
import socketserver
import serial
import requests
import json
import threading
import time

# Configuration
SERIAL_PORT = 'COM3'  # Adjust to your actual serial port
BAUD_RATE = 9600
API_URL = 'http://localhost/axl.com/API/sensorData.php'

class RequestHandler(http.server.SimpleHTTPRequestHandler):
    def do_POST(self):
        if self.path == '/API/control_led.php':
            content_length = int(self.headers['Content-Length'])
            post_data = self.rfile.read(content_length).decode('utf-8')
            data = dict(item.split('=') for item in post_data.split('&'))

            index = data.get('index')
            state = data.get('state')

            if index is not None and state in ['on', 'off', 'up', 'down']:
                # Create the command based on input
                if index.startswith('fan'):
                    command = f"toggle {index}"  # No "led" prefix for fan commands
                else:
                    command = f"toggle led {index}"  # Prefix "led" for LED commands

                if self.server.serial_connection:
                    # Send the command directly to Arduino
                    self.server.serial_connection.write((command + '\n').encode())
                    print(f"Command sent to Arduino: {command}")

                # Send a response
                self.send_response(200)
                self.send_header('Content-type', 'application/json')
                self.end_headers()
                self.wfile.write(json.dumps({'status': 'success', 'command': command}).encode('utf-8'))
            else:
                self.send_response(400)
                self.send_header('Content-type', 'application/json')
                self.end_headers()
                self.wfile.write(json.dumps({'error': 'Invalid input'}).encode('utf-8'))
        else:
            self.send_error(404, 'File not found')

def run_server(serial_connection):
    """Run the HTTP server."""
    port = 8000
    handler = RequestHandler
    httpd = socketserver.TCPServer(("", port), handler)
    httpd.serial_connection = serial_connection  # Set the serial connection here
    print(f"Serving at port {port}")
    httpd.serve_forever()

def read_sensor_data_from_serial(ser):
    """Read sensor data from the serial connection and send it to the API."""
    while True:
        if ser.in_waiting > 0:
            line = ser.readline().decode('utf-8').strip()
            print(f"Received from Arduino: {line}")

            try:
                data = json.loads(line)
                response = requests.post(API_URL, data=data)
                print(f"Data sent to API, response: {response.text}")
            except json.JSONDecodeError:
                print("Received non-JSON data from Arduino")
            except requests.RequestException as e:
                print(f"Error sending data to API: {e}")

        time.sleep(1)

if __name__ == "__main__":
    with serial.Serial(SERIAL_PORT, BAUD_RATE, timeout=1) as ser:
        threading.Thread(target=read_sensor_data_from_serial, args=(ser,), daemon=True).start()
        run_server(ser)
