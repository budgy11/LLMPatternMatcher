#!/usr/bin/env python3
import requests
import argparse
import re
import json

#custom imports
#import chatgpt 
import classifications
from code_parser import code_parse

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
    #https://coderwall.com/p/r6b4xg/regex-to-match-github-s-markdown-code-blocks
    return re.findall(r'```[a-z]*\n[\s\S]*?\n```', output)

    

def switch_language(language,code_block):
    if language == "php":
        return code_block

    else:
        return None


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
    token = args.token

    output = "NO OUTPUT WAS GENERATED"
    json_out = {"prompt": prompt} #used to give easy to parse output for reading generated snippets

    #models pulled from price page by openai
    #only tested against gpt-4.1-nano for budget purposes
    if model[0:3] == "gpt" or model[0:2] == "o1" or model[0:2] == "o3" or model[0:2] == "o4" or  model == "codex-mini-latest" or  model == "computer-use-preview":
        output = chatgpt.send_request(model,prompt,token)
        LLM_output = output #Storing raw output for later

    #All none openAI models. Should likely be moved to else if statements later (likely separate gemma for parsing and maybe deepseek because of think tags)
    else: 
        output = send_request(url, model, prompt)['message']['content']
        #if output[0:7] == '<think>':
            #output = remove_thinking(output) #this may not be a good thing
        LLM_output = output #Storing raw output for later
    
    json_out["llm_output"] = LLM_output

    code_blocks = []
    for block in pull_code(output):
        language = block[3:].split()[0] #removes ``` and then takes leftovers till whitespace (should be the language of the code block)
        switch_language(language,block)
        code_parse(block)
        code_blocks.append(switch_language(language,block))
    json_out["code_blocks"] = code_blocks

    #JSON
    #{
    #    "prompt":"PROMPT",
    #    "llm_out":"LLM_output",
    #    "code_blocks":["block1","block2"]
    #
    #}

    json_out = json.dumps(json_out)
    print(json_out)

if __name__ == "__main__":
    main()
