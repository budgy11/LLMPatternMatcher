import requests
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
    
def request_output(prompt,model,url,quiet):
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
        if line.strip()[0:6] == '```php':
            #print(line) #already printed from above branch
            llm_output += code_parse(code_blocks[block_ctr],quiet)[0]
            alert_string += code_parse(code_blocks[block_ctr],quiet)[1]
            if not quiet:
                llm_output += "\n#####\nAlerts:\n" + alert_string + "#####\n"
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