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
        ],
    "use_exec":   [
        'exec(.*\\$.*)',
        "Exec function can lead to RCE"
        ]
    "use_system":   [
        'system(.*\\$.*)',
        "Exec function can lead to RCE"
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
