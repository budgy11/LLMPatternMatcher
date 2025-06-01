#!/usr/bin/env python3
import requests
import argparse
import re

#custom imports
import chatgpt 

def send_request(url,model,prompt):
    #For the ollama libraries currently - chatgpt will likely need to be separated

    r = requests.post(url, json={
        "model": model, 
        "messages": [
            {
                "role": "user", 
                "content": prompt 
            }
        ],
        "stream": False
    })

    return r.json()

def remove_thinking(output):
    return output.split('</think>',1)[1] #feels archaic but effective
    
def pull_code(output):
    #gemma3 does not like to use code fences and may need additional processing
    return re.findall(r'```[a-z]*\n[\s\S]*?\n```', output)

def main():
    parser = argparse.ArgumentParser(
        prog='LLMPatternMatcher',
        description='Does basic vulnerability checking on LLM output',
        epilog=''
        )

    parser.add_argument('-u', '--url', default='http://localhost:11434/api/chat')
    parser.add_argument('-m', '--model', required = True)
    parser.add_argument('-p', '--prompt', required = True)
    parser.add_argument('-t', '--token')

    args = parser.parse_args()
    model = args.model
    url = args.url
    prompt = args.prompt

    output = send_request(url, model, prompt)['message']['content']
    LLM_output = output
    if output[0:7] == '<think>':
        output = remove_thinking(output)

    for block in pull_code(output):
        print(block)

if __name__ == "__main__":
    main()
