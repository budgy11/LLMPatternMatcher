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

    parser.add_argument('-u', '--url', default='http://localhost:11434/api/chat', help="The endpoint used to interact with the LLM model. Default http://localhost:11434/api/chat")
    parser.add_argument('-m', '--model', required = True, help="The model used to generate output.")
    parser.add_argument('-p', '--prompt', required = False, help="Prompt to send the model.")
    parser.add_argument('-q', '--quiet', action="store_true", help="This variable will mute the Alerts and can help cutdown on runtime.")

    args = parser.parse_args()
    model = args.model
    url = args.url
    prompt = args.prompt
    quiet = args.quiet

    #One-Time Prompt from CLI
    if prompt:
        print(request_output(prompt,model,url,quiet)[0])

    #Interactive Prompt
    else:
        prompt = ""
        while True:
            prompt = input(model+" >>> ")
            if prompt.lower() == "exit":
                exit()
            print(request_output(prompt,model,url,quiet)[0])

if __name__ == "__main__":
    main()
