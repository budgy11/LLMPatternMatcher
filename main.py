#!/usr/bin/env python3
import requests
import argparse
import re
import json
import datetime
import time
import os

#custom imports
from LLM_gen import send_request
from LLM_gen import request_output
from LLM_gen import parse_output

def gen_json_out(prompt,llm_output,total_time):
    json_output = {"prompt": prompt, "output": llm_output, "time": total_time}
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
    #parser.add_argument('-l', '--loud', action="store_true", help="This variable will replace dangerouse vars with $. meaning all variables will be considered dangerous.")
    parser.add_argument('-o', '--output', help="Outputs text of matched code")
    parser.add_argument('-oj', '--output-json', help="Outputs json of matched code")
    parser.add_argument('-i', '--input', help="Markdown input file to parse")
    parser.add_argument('-ip', '--input-php', help="php input file to parse (input treated as a large codeblock)")

    args = parser.parse_args()
    model = args.model
    url = args.url
    prompt = args.prompt
    quiet = args.quiet
    #loud = args.loud
    out_text = args.output
    out_json = args.output_json
    input_file = args.input
    input_php = args.input


    #TODO this can probably be better served making a single function for generating final output.

    #One-Time Prompt from CLI
    if prompt:
        start_time = time.time()
        final_output = request_output(prompt,model,url,quiet)
        print(final_output)
        if out_text:
            timestamp = datetime.datetime.now().strftime("%Y%m%d%H%M%S%f") #to keep names unique
            with open(out_text + "-" + timestamp + '.md', 'w') as wh:
                wh.write(final_output)
        elif out_json:
            total_time = time.time() - start_time
            timestamp = datetime.datetime.now().strftime("%Y%m%d%H%M%S%f") #to keep names unique
            with open(out_json + "-" + timestamp + '.json', 'w') as wh:
                wh.write(gen_json_out(prompt,final_output,total_time))

    #Markdown input file. Ignores any supplied prompt
    elif input_file:
        start_time = time.time()
        with open(input_file, 'r') as fh:
            llm_output = fh.read()
        final_output = parse_output(llm_output,quiet)
        print(final_output)
        if out_text:
            timestamp = datetime.datetime.now().strftime("%Y%m%d%H%M%S%f") #to keep names unique
            with open(out_text + "-" + timestamp + '.md', 'w') as wh:
                wh.write(final_output)
        elif out_json:
            total_time = time.time() - start_time
            timestamp = datetime.datetime.now().strftime("%Y%m%d%H%M%S%f") #to keep names unique
            with open(out_json + "-" + timestamp + '.json', 'w') as wh:
                wh.write(gen_json_out(prompt,final_output,total_time))

    elif input_php:
        start_time = time.time()
        with open(input_php, 'r') as fh:
            llm_output = fh.read()
            llm_output = "```php\n" + llm_output +"\n```\n" #to create a code block
        final_output = parse_output(llm_output,quiet)
        print(final_output)
        if out_text:
            timestamp = datetime.datetime.now().strftime("%Y%m%d%H%M%S%f") #to keep names unique
            with open(out_text + "-" + timestamp + '.md', 'w') as wh:
                wh.write(final_output)
        elif out_json:
            total_time = time.time() - start_time
            timestamp = datetime.datetime.now().strftime("%Y%m%d%H%M%S%f") #to keep names unique
            with open(out_json + "-" + timestamp + '.json', 'w') as wh:
                wh.write(gen_json_out(prompt,final_output,total_time))

    #Interactive Prompt
    else:
        prompt = ""
        while True:
            prompt = input(model+" >>> ")
            if prompt.lower() == "exit":
                break
            start_time = time.time()
            final_output = request_output(prompt,model,url,quiet)
            print(final_output)
            if out_text:
                timestamp = datetime.datetime.now().strftime("%Y%m%d%H%M%S%f") #to keep names unique
                with open(out_text + "-" + timestamp + '.md', 'w') as wh:
                    wh.write(final_output)
            elif out_json:
                total_time = time.time() - start_time
                timestamp = datetime.datetime.now().strftime("%Y%m%d%H%M%S%f") #to keep names unique
                with open(out_json + "-" + timestamp + '.json', 'w') as wh:
                    wh.write(gen_json_out(prompt,final_output,total_time))

if __name__ == "__main__":
    main()
