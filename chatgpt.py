from openai import OpenAI
import os


def send_request(model,prompt,token):
    client = OpenAI(api_key=token)

    response = client.responses.create(
        model=model,
        input=prompt,
        #max_output_tokens=100 #TODO only in place for testing remove when creating samples
    )

    return response.output_text
