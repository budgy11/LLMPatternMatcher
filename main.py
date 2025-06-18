#!/usr/bin/env python3
import requests
import argparse
import re
import json
import datetime

#custom imports
from LLM_gen import send_request
from LLM_gen import request_output

def gen_json_out(prompt,llm_output):
    json_output = {"prompt": prompt, "output": llm_output}
    return json.dumps(json_output)


def main():
    parser = argparse.ArgumentParser(
        prog='LLMPatternMatcher',
        description='Does basic vulnerability checking on LLM output',
        epilog=''
        )

    parser.add_argument('-u', '--url', default='http://localhost:11434/api/chat', help="The endpoint used to interact with the LLM model. Default http://localhost:11434/api/chat")
    parser.add_argument('-m', '--model', required = True, help="The model used to generate output.")
    parser.add_argument('-p', '--prompt', required = False, help="Prompt to send the model.")
    parser.add_argument('-q', '--quiet', action="store_true", help="This variable will mute the alerts and can help cutdown on runtime.")
    parser.add_argument('-o', '--output', help="Outputs text of matched code")
    parser.add_argument('-oj', '--output-json', help="Outputs json of matched code")

    args = parser.parse_args()
    model = args.model
    url = args.url
    prompt = args.prompt
    quiet = args.quiet
    out_text = args.output
    out_json = args.output_json

    #One-Time Prompt from CLI
    if prompt:
        final_output = request_output(prompt,model,url,quiet)
        print(final_output)
        if out_text:
            timestamp = datetime.datetime.now().strftime("%Y%m%d%H%M%S%f") #to keep names unique
            with open(out_text + "-" + timestamp + '.txt', 'w') as wh:
                wh.write(final_output)
        elif out_json:
            timestamp = datetime.datetime.now().strftime("%Y%m%d%H%M%S%f") #to keep names unique
            with open(out_json + "-" + timestamp + '.json', 'w') as wh:
                wh.write(gen_json_out(prompt,final_output))

    #Interactive Prompt
    else:
        prompt = ""
        while True:
            prompt = input(model+" >>> ")
            if prompt.lower() == "exit":
                exit()
            final_output = request_output(prompt,model,url,quiet)
            print(final_output)
            if out_text:
                timestamp = datetime.datetime.now().strftime("%Y%m%d%H%M%S%f") #to keep names unique
                with open(out_text + "-" + timestamp + '.txt', 'w') as wh:
                    wh.write(final_output)
            elif out_json:
                timestamp = datetime.datetime.now().strftime("%Y%m%d%H%M%S%f") #to keep names unique
                with open(out_json + "-" + timestamp + '.json', 'w') as wh:
                    wh.write(gen_json_out(prompt,final_output))

if __name__ == "__main__":
    main()
