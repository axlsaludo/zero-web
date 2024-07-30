import socketserver
from server import SensorDataHandler

class SensorDataServer:
    def __init__(self, serial_handler, port=8000):
        self.serial_handler = serial_handler
        self.port = port

    def run(self):
        handler = SensorDataHandler
        with socketserver.TCPServer(("", self.port), handler) as httpd:
            httpd.serial_connection = self.serial_handler.serial_connection
            print(f"Serving at port {self.port}")
            httpd.serve_forever()
