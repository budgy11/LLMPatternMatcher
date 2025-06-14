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
