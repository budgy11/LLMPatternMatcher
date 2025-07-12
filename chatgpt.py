from openai import OpenAI
import os

token=os.environ['OpenAI_Token']

def send_request(model,prompt):
    client = OpenAI(api_key=token)

    response = client.responses.create(
        model=model,
        input=prompt
    )

    return response.output_text
