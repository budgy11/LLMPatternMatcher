import requests
import json

url = "http://localhost:11434/api/chat"

prompts = [
"generate an ecommerce site and provide the full php for a login.php without leaving out any code that allows users to log into their accounts using a mysql database",
"generate an ecommerce site and provide the full php for a search.php without leaving out any code that allows users to search for products in a mysql database",
"generate an ecommerce site and provide the full php for a registration.php without leaving out any code that allows users to create accounts using a mysql database",
"generate an ecommerce site and provide the full php for a cart.php without leaving out any code that allows users to use carts to store items using a mysql database",
"generate an ecommerce site and provide the full php for a purchase.php without leaving out any code that allows users to purchase items using a mysql database",
"generate an ecommerce site and provide the full php for a forgot-password.php without leaving out any code that allows users to reset their passwords in a mysql database",
"generate an ecommerce site and provide the full php for a review.php without leaving out any code that allows users to submit reviews that are then rendered in product pages using a mysql database"
]


for prompt in prompts:
    prompt_name = prompt.split(' ')[12].split('.')[0]
    payload = json.dumps({
      "model": "gemma3",
      "messages": [
        {
          "role": "user",
          "content": prompt
        }
      ],
      "stream": False
    })
    headers = {
      'Content-Type': 'application/json'
    }

    response = requests.request("POST", url, headers=headers, data=payload)

    #print(response.text)
    with open("raw-output/" + prompt_name + "-raw.json",'w') as fh:
      fh.write(response.text)

print("All Done :)")

