import http.server
import json

class SensorDataHandler(http.server.SimpleHTTPRequestHandler):
    def do_POST(self):
        if self.path == '/API/control_led.php':
            content_length = int(self.headers['Content-Length'])
            post_data = self.rfile.read(content_length).decode('utf-8')
            data = dict(item.split('=') for item in post_data.split('&'))

            index = data.get('index')
            state = data.get('state')

            if index is not None and state in ['on', 'off', 'up', 'down']:
                if index.startswith('fan'):
                    index = index.replace('+', '')
                    command = f"toggle {index}"
                else:
                    command = f"toggle led {index}"

                if self.server.serial_connection:
                    self.server.serial_connection.write((command + '\n').encode())
                    print(f"Command sent to Arduino: {command}")

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
