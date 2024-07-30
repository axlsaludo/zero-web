import serial

class SerialHandler:
    def __init__(self, port, baud_rate):
        self.port = port
        self.baud_rate = baud_rate
        self.serial_connection = None

    def open(self):
        self.serial_connection = serial.Serial(self.port, self.baud_rate, timeout=1)

    def close(self):
        if self.serial_connection:
            self.serial_connection.close()

    def read_data(self):
        if self.serial_connection and self.serial_connection.in_waiting > 0:
            return self.serial_connection.readline().decode('utf-8').strip()
        return None
