import threading
import time
from serial_handler import SerialHandler
from sensor_data_server import SensorDataServer
from sensor_data_uploader import SensorDataUploader

# Configuration
SERIAL_PORT = 'COM3'  # Adjust to your actual serial port
BAUD_RATE = 9600
API_URL = 'http://localhost/axl.com/API/sensorData.php'

def read_sensor_data_from_serial(serial_handler, uploader):
    while True:
        line = serial_handler.read_data()
        if line:
            print(f"Received from Arduino: {line}")
            try:
                data = json.loads(line)
                uploader.upload(data)
            except json.JSONDecodeError:
                print("Received non-JSON data from Arduino")
        time.sleep(1)

if __name__ == "__main__":
    serial_handler = SerialHandler(SERIAL_PORT, BAUD_RATE)
    serial_handler.open()
    
    uploader = SensorDataUploader(API_URL)
    server = SensorDataServer(serial_handler)
    
    threading.Thread(target=read_sensor_data_from_serial, args=(serial_handler, uploader), daemon=True).start()
    server.run()

    serial_handler.close()
