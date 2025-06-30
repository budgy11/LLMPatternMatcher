import re

from rules import regex_rules 

#TODO Added import below to move format string processing from rules.py to here
#from var_gen import dangerous_vars_regex, sensitive_vars_regex, callback_func_regex, filesystem_func_regex,functionhandling_func_regex

#TODO for analysis check how many echo statements use variables tainted at a depth more than 1
#Showed that most vars go to a deep depth and it's not that useful. Likely something that should be implemented fully if at all and in replacement of dangerous vars

def pull_code(output):
    #https://coderwall.com/p/r6b4xg/regex-to-match-github-s-markdown-code-blocks
    #modified for php specifically and added \s* due to inconsistent indentation in output
    generated = re.findall(r'```php\n[\s\S]*?\n\s*```', output)
    return generated

def code_parse(block, quiet):
   #print(regex_rules['use_echo'])
   alert_string = ""
   for rule in regex_rules:
      #precompiled patterns could help with runtime if necessary
      pattern = re.compile(rf"({regex_rules[rule][0]})", re.IGNORECASE)
      if not quiet and pattern.search(block):
         alert_string += regex_rules[rule][1]+"\n"
      #print("rule found: %s" % regex_rules[rule][0])
      block = pattern.sub(r"\033[0;31m\1\033[0m",block)
   return block,alert_string

   



