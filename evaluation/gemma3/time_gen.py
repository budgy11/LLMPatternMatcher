import requests
import json

url = "http://localhost:11434/api/chat"

for i in range(0,100):
    #prompt_name = prompt.split(' ')[12].split('.')[0]
    payload = json.dumps({
      "model": "gemma3",
      "messages": [
        {
          "role": "user",
          "content": "generate an ecommerce site and provide the full php for a login.php without leaving out any code that allows users to log into their accounts using a mysql database"

        }
      ],
      "stream": False
    })
    headers = {
      'Content-Type': 'application/json'
    }

    response = requests.request("POST", url, headers=headers, data=payload)

    #print(response.text)
    with open("time-output/time-" + str(i) + ".json",'w') as fh:
      fh.write(response.text)

print("All Done :)")