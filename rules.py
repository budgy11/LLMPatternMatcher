regex_rules = {
    # note backslashes should be escaped i.e \$ should be \\$
    # "RULE_NAME": [
    #"regex(python format)",
    #"description"
    #]
    "find_username":   [
        '\\$username',
        "There is a username variable in use"
        ],
    "find_password":   [
        '\\$password',
        "There is a password variable in use"
        ]
}

#VARIABLE CLASSIFICATIONS
#for variables that usually store sensitive data in code1
sensitive = []

#for variables that usually take user input prior to or without user
dangerous = []

#for variables that always use safe input or hardcoded values
safe = []
