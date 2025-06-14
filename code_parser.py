import re

from rules import regex_rules 

#used for json generation
def pull_code(output):
    #https://coderwall.com/p/r6b4xg/regex-to-match-github-s-markdown-code-blocks
    generated = re.findall(r'```php\n[\s\S]*?\n```', output)
    return generated

def code_parse(block):
   for rule in regex_rules:
      #print("rule found: %s" % regex_rules[rule][0])
      matches = re.findall(rf"{regex_rules[rule][0]}",line,re.IGNORECASE)
      out_line = re.sub(rf"({regex_rules[rule][0]})",r"\033[0;31m\1\033[0m",line)
      #print("Matches found: \033[0;31m%s\033[0m" % matches) 
   return out_line

   



