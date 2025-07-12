import re

from rules import regex_rules 

def pull_code(output):
    #https://coderwall.com/p/r6b4xg/regex-to-match-github-s-markdown-code-blocks
    #modified for php specifically and added \s* due to inconsistent indentation in output
    generated = re.findall(r'```php\n[\s\S]*?\n\s*```', output)
    return generated

def code_parse(block, quiet):
   alert_string = ""
   for rule in regex_rules:
      pattern = re.compile(rf"({regex_rules[rule][0]})", re.IGNORECASE)
      if not quiet and pattern.search(block):
         alert_string += regex_rules[rule][1]+"\n"
      block = pattern.sub(r"\033[0;31m\1\033[0m",block)
   return block,alert_string

   



