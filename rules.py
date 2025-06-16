regex_rules = {
    # "RULE_NAME": [
    #r"regex(python format)",
    #"description"
    #]
    #"find_username":   [
    #    r'\$username',
    #    "There is a username variable in use"
    #    ],
    #"find_password":   [
    #    r'\$password',
    #    "There is a password variable in use"
    #    ],
    # The following rules are modified from graudit at https://github.com/wireghoul/graudit/blob/master/signatures/php/default.db
    # TODO find rules that use GET|... and make sure common user variables are included
    "use_exec":   [
        r'exec\s*\([^;\)]*\$[\(\{]?[_a-zA-Z0-9][^\)]*\)\s*[\);]',
        "Exec function can lead to RCE"
        ],
    "use_system":   [
        r'system\s*\([^;]*\$[^\)]+\)',
        "System function can lead to RCE"
        ],
    "use_passthru":   [
        r'passthru\s*\(.*\)',
        "Passthru function can lead to RCE"
        ],
    "use_popen":   [
        r'popen\s*\(.*\$.*\)',
        "Popen function can lead to RCE"
        ],
    "use_shell_exec":   [
        r'shell_exec\s*\(.*\$.*\)',
        "Shell_exec function can lead to RCE"
        ],
    "use_call_user_func":   [
        r'call_user_func\s*\(.?.?\$.*,.?\$.*',
        "Exec function can lead to RCE"
        ],
    "use_backticks_assignment":   [
        r'[= (]`[^`]*\$[\(\{]?[_a-zA-Z0-9][^`]*`',
        "Backticks may be a sign of command execution when used for variable assignment"
        ],
    "use_backticks_containment":   [
        r'^`[^`]*\$[\(\{]?[_a-zA-Z0-9][^`]*`',
        "Backticks may be a sign of command execution when containing a variable"
        ],
    "use_echo":   [
        r'echo.*\$_.*\[.*\]', #TODO this seems like it may flag a lot 
        "Echo may lead to XSS"
        ],
    "use_query":   [
        r'(mysql.?_|pg_|sqlsrv_|::)query\s*\(.*\$.*\)', 
        "Use of non parameterized SQL Queries can lead to SQLI and is strongly discouraged"
        ],
    "use_variable_in_where":   [
        r'[Ww][Hh][Ee][Rr][Ee]\s+.*=.*\$[^; ]+',
        "Inserting a variable directly into a WHERE statement can lead to SQLI"
        ],
    "use_variable_in_where_and_or":   [
        r'([Ww][Hh][Ee][Rr][Ee]|[Aa][Nn][Dd]|[Oo][Rr])\s+.*\s+[Ll][Ii][Kk][Ee]\s+.*\$',
        "Inserting a variable directly after a WHERE/AND/OR/LIKE statement can lead to SQLI"
        ],
    "use_VALUES":   [
        r'VALUES\s*\([^\)]*\$.*\)',
        "VALUES followed by a variable may lead to SQLI"
        ],
    "use_include_require":   [
        r'^\s*(include|include_once|require|require_once)\s*\([^\;\}\{]*\$.*\)',
        "Use of variables following include or require may lead to SQLI"
        ],
    "use_print_param":   [
        r'print.*param\s*\(.*\);',
        "Printing parameters may lead to XSS or database leakage"
        ],
    "use_extract_user_input":   [ 
        r'extract\s*\(\$_(GET|POST|REQUEST|COOKIE|SERVER)', #TODO may be useful to also include user-vars here
        "Use of extract on user input may be a sign of SQLI"
        ],
    "use_new_user_input":   [
        r'new\s+\$_(GET|REQUEST|POST|COOKIE).*\(',
        "Creating a PHP class from user input using new is dangerous and could lead to RCE"
        ]
}

#Default Graudit to convert over
#exec\s*\([^;\)]*\$[\(\{]?[_a-zA-Z0-9][^\)]*\)\s*[\);]
#passthru\s*\(.*\)
#popen\s*\(.*\$.*\)
#shell_exec\s*\(.*\$.*\)
#system\s*\([^;]*\$[^\)]+\)
#call_user_func\s*\(.?.?\$.*,.?\$.*
#[= (]`[^`]*\$[\(\{]?[_a-zA-Z0-9][^`]*`
#^`[^`]*\$[\(\{]?[_a-zA-Z0-9][^`]*`
#echo.*\$_.*\[.*\]
#eval\s*\(.*\$.*\)
#(mysql.?_|pg_|sqlsrv_|::)query\s*\(.*\$.*\)
#[Ww][Hh][Ee][Rr][Ee]\s+.*=.*\$[^; ]+
#([Ww][Hh][Ee][Rr][Ee]|[Aa][Nn][Dd]|[Oo][Rr])\s+.*\s+[Ll][Ii][Kk][Ee]\s+.*\$
#VALUES\s*\([^\)]*\$.*\)
#^\s*(include|include_once|require|require_once)\s*\([^\;\}\{]*\$.*\)
#print.*param\s*\(.*\);
#extract\s*\(\$_(GET|POST|REQUEST|COOKIE|SERVER)
#new\s+\$_(GET|REQUEST|POST|COOKIE).*\(

#https://github.com/FloeDesignTechnologies/phpcs-security-audit/tree/master/Security/Sniffs/BadFunctions - more stuff to look at

#VARIABLE CLASSIFICATIONS
#for variables that usually store sensitive data in code1
sensitive = []

#for variables that usually take user input prior to or without user
dangerous = []

#for variables that always use safe input or hardcoded values
safe = []
