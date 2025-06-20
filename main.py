#!/usr/bin/env python3
import requests
import argparse
import re
import json
import datetime
import os

#custom imports
from LLM_gen import send_request
from LLM_gen import request_output
from LLM_gen import parse_output

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
    parser.add_argument('-l', '--loud', action="store_true", help="This variable will replace dangerouse vars with $. meaning all variables will be considered dangerous.")
    parser.add_argument('-o', '--output', help="Outputs text of matched code")
    parser.add_argument('-oj', '--output-json', help="Outputs json of matched code")
    parser.add_argument('-i', '--input', help="Markdown input file to parse")

    args = parser.parse_args()
    model = args.model
    url = args.url
    prompt = args.prompt
    quiet = args.quiet
    loud = args.loud
    out_text = args.output
    out_json = args.output_json
    input_file = args.input

    #One-Time Prompt from CLI
    if prompt:
        final_output = request_output(prompt,model,url,quiet)
        print(final_output)
        if out_text:
            timestamp = datetime.datetime.now().strftime("%Y%m%d%H%M%S%f") #to keep names unique
            with open(out_text + "-" + timestamp + '.md', 'w') as wh:
                wh.write(final_output)
        elif out_json:
            timestamp = datetime.datetime.now().strftime("%Y%m%d%H%M%S%f") #to keep names unique
            with open(out_json + "-" + timestamp + '.json', 'w') as wh:
                wh.write(gen_json_out(prompt,final_output))

    #Markdown input file. Ignores any supplied prompt
    elif input:
        with open(input_file, 'r') as fh:
            llm_output = fh.read()
        final_output = parse_output(llm_output,quiet)
        print(final_output)
        if out_text:
            timestamp = datetime.datetime.now().strftime("%Y%m%d%H%M%S%f") #to keep names unique
            with open(out_text + "-" + timestamp + '.md', 'w') as wh:
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
                break
            final_output = request_output(prompt,model,url,quiet)
            print(final_output)
            if out_text:
                timestamp = datetime.datetime.now().strftime("%Y%m%d%H%M%S%f") #to keep names unique
                with open(out_text + "-" + timestamp + '.md', 'w') as wh:
                    wh.write(final_output)
            elif out_json:
                timestamp = datetime.datetime.now().strftime("%Y%m%d%H%M%S%f") #to keep names unique
                with open(out_json + "-" + timestamp + '.json', 'w') as wh:
                    wh.write(gen_json_out(prompt,final_output))

if __name__ == "__main__":
    main()
