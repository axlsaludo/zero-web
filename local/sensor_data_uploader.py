import requests

class SensorDataUploader:
    def __init__(self, api_url):
        self.api_url = api_url

    def upload(self, data):
        try:
            response = requests.post(self.api_url, json=data)
            print(f"Data sent to API, response: {response.text}")
        except requests.RequestException as e:
            print(f"Error sending data to API: {e}")
