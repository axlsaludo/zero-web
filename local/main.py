import http.server
import socketserver
import serial
import requests
import json
import threading

# Serial connection settings
SERIAL_PORT = 'COM3'  # Replace with your serial port
BAUD_RATE = 9600

ser = serial.Serial(SERIAL_PORT, BAUD_RATE)

php_url = 'http://localhost/axl.com/API/fetchAndStoreData.php'

def send_data_to_php(data):
    try:
        response = requests.post(php_url, json=data)
        response.raise_for_status()
        print(f"Data sent successfully: {data}")
    except requests.RequestException as e:
        print(f"Error sending data: {e}")

def parse_data(line):
    data = {}
    parts = line.split(',')
    for part in parts:
        if ':' in part:
            key, value = part.split(':', 1)
            data[key.strip()] = value.strip()
        else:
            print(f"Malformed data part: {part}")
    return data

class RequestHandler(http.server.SimpleHTTPRequestHandler):
    def send_cors_headers(self):
        self.send_header('Access-Control-Allow-Origin', '*')
        self.send_header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')
        self.send_header('Access-Control-Allow-Headers', 'Content-Type')

    def do_OPTIONS(self):
        self.send_response(200)
        self.send_cors_headers()
        self.end_headers()

    def do_POST(self):
        self.send_cors_headers()
        content_length = int(self.headers['Content-Length'])
        post_data = self.rfile.read(content_length)

        try:
            data = json.loads(post_data)
            action = data.get('action')

            if action == 'toggleLED':
                led_index = data.get('ledIndex')
                state = data.get('state')
                command = f"LED:{led_index}:{state}\n"

                try:
                    ser.write(command.encode())
                    print(f"Sent command to Arduino: {command}")
                    self.send_response(200)
                    self.send_header('Content-type', 'application/json')
                    self.end_headers()
                    self.wfile.write(json.dumps({'status': 'success'}).encode())
                except serial.SerialException as e:
                    print(f"SerialException: {e}")
                    self.send_response(500)
                    self.send_header('Content-type', 'application/json')
                    self.end_headers()
                    self.wfile.write(json.dumps({'status': 'error', 'message': 'Serial communication error'}).encode())
            else:
                self.send_response(400)
                self.send_header('Content-type', 'application/json')
                self.end_headers()
                self.wfile.write(json.dumps({'status': 'error', 'message': 'Invalid action'}).encode())
        except json.JSONDecodeError:
            self.send_response(400)
            self.send_header('Content-type', 'application/json')
            self.end_headers()
            self.wfile.write(json.dumps({'status': 'error', 'message': 'Invalid JSON'}).encode())

def read_serial_data():
    while True:
        try:
            if ser.in_waiting > 0:
                line = ser.readline().decode('utf-8').strip()
                print(f"Received from serial: {line}")

                data = parse_data(line)
                if data:
                    send_data_to_php(data)
        except serial.SerialException as e:
            print(f"SerialException in read_serial_data: {e}")
        except Exception as e:
            print(f"Unexpected error in read_serial_data: {e}")

def main():
    PORT = 8000
    with socketserver.TCPServer(("", PORT), RequestHandler) as httpd:
        print(f"Serving HTTP on port {PORT}")
        serial_thread = threading.Thread(target=read_serial_data)
        serial_thread.start()
        httpd.serve_forever()

if __name__ == '__main__':
    main()
