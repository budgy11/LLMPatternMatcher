#!/usr/bin/env python3
import requests
import argparse
import re
import json

#custom imports
#import chatgpt  #removed due to need for venv and not using chatgpt for project
import classifications
from code_parser import code_parse
from code_parser import pull_code

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

#removed because it seems like bad practice and unnecessary
#def remove_thinking(output): 
#    return output.split('</think>',1)[1] #feels archaic but effective
    
def request_output(prompt,model,url):
    llm_output = "" 
    alert_string = ""
    #openai models that were implemented originally but not used for research
    if model[0:3] == "gpt" or model[0:2] == "o1" or model[0:2] == "o3" or model[0:2] == "o4" or  model == "codex-mini-latest" or  model == "computer-use-preview":
        output = chatgpt.send_request(model,prompt,token)
    #All none openAI models. Should likely be moved to else if statements later (likely separate gemma for parsing and maybe deepseek because of think tags)
    else: 
        output = send_request(url, model, prompt)['message']['content']

    code_blocks = pull_code(output)

    isCode = False
    block_ctr = 0
    for line in output.split('\n'):
        #lines in php blocks to parse
        if line[0:6] == '```php':
            #print(line) #already printed from above branch
            llm_output += code_parse(code_blocks[block_ctr])[0]
            alert_string += code_parse(code_blocks[block_ctr])[1]
            block_ctr += 1
            isCode = True
        elif isCode and line[0:3] == '```':
            #print(line)
            print() #missing newline
            isCode = False
        elif not isCode:
            #print(line)
            llm_output += line + "\n"
        elif isCode:
            pass
        else:
            print("Unknown line type: %s" % line)

    return llm_output,alert_string



def main():
    parser = argparse.ArgumentParser(
        prog='LLMPatternMatcher',
        description='Does basic vulnerability checking on LLM output',
        epilog=''
        )

    parser.add_argument('-u', '--url', default='http://localhost:11434/api/chat')
    parser.add_argument('-m', '--model', required = True)
    parser.add_argument('-p', '--prompt', required = False)
    parser.add_argument('-t', '--token')
    parser.add_argument('-q', '--quiet', action='store_false')

    args = parser.parse_args()
    model = args.model
    url = args.url
    prompt = args.prompt
    token = args.token
    quiet = args.quiet

    isCode = False #flag for when within PHP code block
    output = "NO OUTPUT WAS GENERATED"

    if prompt:
        print(request_output(prompt,model,url)[0])
        if not quiet:
            print("ALERTS:\n")
            print(request_output(prompt,model,url)[1])

    else:
        prompt = ""
        while True:
            prompt = input(model+" >>> ")
            if prompt.lower() == "exit":
                exit()
            print(request_output(prompt,model,url)[0])
            if not quiet:
                print("ALERTS:")
                print(request_output(prompt,model,url)[1])

    #json output used for analysis and debugging
    #json_out = {"prompt": prompt} 
    #json_out["llm_output"] = output
    #json_out["code_blocks"] = pull_code(output)
    #json_out = json.dumps(json_out)
    #print(json_out)

    #print("############################################################################")
    #print(output)

if __name__ == "__main__":
    main()
