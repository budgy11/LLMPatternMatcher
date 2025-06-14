#!/usr/bin/env python3
import requests
import argparse
import re
import json

#custom imports
#import chatgpt  #removed due to need for venv and not using chatgpt for project
from LLM_gen import send_request
from LLM_gen import request_output

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
    quiet = args.quiet #TODO this doesn't seem to be parsed correctly

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
