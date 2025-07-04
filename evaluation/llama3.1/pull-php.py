import sys
import re
from pathlib import Path

def pull_code(output):
    #https://coderwall.com/p/r6b4xg/regex-to-match-github-s-markdown-code-blocks
    #modified for php specifically and added \s* due to inconsistent indentation in output
    generated = re.findall(r'```php\n[\s\S]*?\n\s*```', output)
    return generated

input_file = sys.argv[1]
markdown = Path(input_file).read_text()

ctr = 0
for i in pull_code(markdown):
    ctr += 1
    write_file = 'php-code/' + str(ctr) + '-' + input_file.split('/')[2].replace("md","php")
    with open(write_file,'w') as wh:
        wh.write(i)
    #print(i)
