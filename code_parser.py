import re

from rules import regex_rules 

#used for json generation
def pull_code(output):
    #https://coderwall.com/p/r6b4xg/regex-to-match-github-s-markdown-code-blocks
    generated = re.findall(r'```php\n[\s\S]*?\n```', output)
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

   



