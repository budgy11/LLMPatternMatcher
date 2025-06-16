#!/usr/bin/env python3
import requests
import argparse
import re
import json

#custom imports
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
    parser.add_argument('-q', action="store_true", help="This variable will mute the Alerts and can help cutdown on runtime")

    args = parser.parse_args()
    model = args.model
    url = args.url
    prompt = args.prompt
    quiet = args.q

    isCode = False #flag for when within PHP code block
    output = "NO OUTPUT WAS GENERATED"

    if prompt:
        print(request_output(prompt,model,url,quiet)[0])

    else:
        prompt = ""
        while True:
            prompt = input(model+" >>> ")
            if prompt.lower() == "exit":
                exit()
            print(request_output(prompt,model,url,quiet)[0])

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
