#VARIABLE CLASSIFICATIONS
#for variables that usually store sensitive data in code
with open('sensitive_vars.txt') as fh:
    sensitive = fh.read().splitlines()

#for variables that usually take user input 
with open('dangerous_vars.txt') as fh:
    dangerous = fh.read().splitlines()

#for variables that always use safe input or hardcoded values
safe = []

#TODO check analysis for pulled functions with common names for vulnerable functions

def gen_regex_var_portion(var_list):
    var_regex = "("
    for var in var_list:
        var_regex += var
        var_regex += "|"
    var_regex = var_regex[:-1] + ")" #replace final '|' with ')'
    return var_regex

dangerous_vars_regex = gen_regex_var_portion(dangerous)
#print(dangerous_vars_regex)

sensitive_vars_regex = gen_regex_var_portion(sensitive)
#print(sensitive_vars_regex)
