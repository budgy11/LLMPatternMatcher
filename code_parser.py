import re

from rules import regex_rules 

def code_parse(code_block):
   for rule in regex_rules:

      print("rule found: %s" % regex_rules[rule][0])
      matches = re.findall(rf"{regex_rules[rule][0]}",code_block,re.IGNORECASE)

      print("Matches found: %s" % matches)



