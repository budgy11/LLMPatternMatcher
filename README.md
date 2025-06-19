# LLM Pattern Matching Tool for PHP Code
This tool is a python tool that will highlight potentially vulnerable portions of PHP code provided when using an LLM and print out basic alerts strings. It can be used with a `-p` flag followed by the prompt string wrapped in quotes or through an interactive prompt. 

## Extensibility
Regex rules are stored in a python dictionary located in `rules.py` with formatting information. The `variables/sensitive_vars.txt` file contains a list of regex to match potential code secrets and can be added by newlines. The `variables/dangerous_vars.txt` file includes regex commonly associated with user input based on previous LLM output. It also includes `_GET` and other similar PHP patterns for user input variables. Users should be able to modify any of these files to include or disclude regex from their respective lists.

#### Dangerous Variables
The `variables/dangerous_vars.txt` file by default contains `\$.` to match everything. There is also an ecommerce version available in the `variables` folder with some default variable names discovered during testing on gemma3. The file can be swapped in by renaming to `variables/dangerous_vars.txt`.

## Connecting to LLMs
The LLM Pattern Matching Tool for PHP Code was tested and designed for use with models hosted with Ollama. The `-u` flag can be used to point at specific API endpoints for the target LLMs and defaults to `http://localhost:11434/api/chat` for locally hosted Ollama. The `-m` flag will specify the model to use and is required.

The `chatgpt.py` file can be used to connect to OpenAI LLMs but is not well tested. To enable this feature you will need to import the OpenAI according to their documentation and uncomment the `#import chatgpt` line from `LLM_gen.py`. The token is taken from the `OpenAI_Token` environment variable.

## Output
The `-o <file_prefix>` flag can be used to output the processed LLM Outputs to text files. `-oj <file_prefix>` can be used to output the prompt and output into a json file. If using jq, it's recommended to use `jq -r .output <file_prefix-timestamp.json>` for the output to maintain newlines and ansi colors.