from var_gen import dangerous_vars_regex, sensitive_vars_regex, callback_func_regex, filesystem_func_regex,functionhandling_func_regex

#Formatting for implementing regex rules
# "RULE_NAME": [
#r"regex(python format)",
#"description"
#]

#Examples
#"find_username":   [
#    r'\$username',
#    "There is a username variable in use"
#    ],
#"find_password":   [
#    rf'\${dangerous_vars_regex}',
#    "There is a dangerous variable in use"
#    ],:W
# Note: there is currently a bug where using full matches must be placed higher in the dictionary to be alerted (i.e shell_exec must be placed before exec)

# Use {dangerous_vars_regex} in locations where you are checking for user input specifically and change r' to rf' to use a formatted string
# Note: using dangerous_vars_regex is not fullproof and may lead to false negatives but should provide less alerts. This is also not a replacement for code review/audits

#May need to switch off of format strings  and do substitution later in code_parser
regex_rules = {
    # The following default rules are modified from graudit at https://github.com/wireghoul/graudit/blob/master/signatures/php/default.db
    # and https://github.com/FloeDesignTechnologies/phpcs-security-audit/tree/master/Security/Sniffs/BadFunctions 
    "secret_variables_check":   [
        rf'{sensitive_vars_regex}',
        "Variable may contain secrets that should not be stored in code"
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
    "use_pcntl_exec":   [
        r'pcntl_exec\s*\(.*\$.*\)',
        "pcntl_exec function can lead to RCE"
        ],
    "use_shell_exec":   [
        r'shell_exec\s*\(.*\$.*\)',
        "Shell_exec function can lead to RCE"
        ],
    "use_exec":   [
        r'exec\s*\([^;\)]*\$[\(\{]?[_a-zA-Z0-9][^\)]*\)\s*[\);]',
        "Exec function can lead to RCE"
        ],
    "use_eval":   [
        r'eval\s*\([^;\)]*\$[\(\{]?[_a-zA-Z0-9][^\)]*\)\s*[\);]',
        "Eval function can lead to RCE"
        ],
    "use_proc_open":   [
        r'proc_open\s*\(.*\$.*\)',
        "proc_open function can lead to RCE"
        ],
    "use_backticks_assignment":   [
        r'[= (]`[^`]*\$[\(\{]?[_a-zA-Z0-9][^`]*`',
        "Backticks may be a sign of command execution when used for variable assignment"
        ],
    "use_backticks_containment":   [
        r'^`[^`]*\$[\(\{]?[_a-zA-Z0-9][^`]*`',
        "Backticks may be a sign of command execution when containing a variable"
        ],
    #modified to have negative look behind for htmlspecialchars( based on gemma3 output
    "use_echo":   [
        rf'echo\s+.*(?<!htmlspecialchars\()\s*{dangerous_vars_regex}.*\n', 
        #rf'echo.*(?<!htmlspecialchars\()\s*.*', 
        "Echo may lead to XSS if passed unsanitized input"
        ],
    "use_query":   [
        r'(mysql.?_|pg_|sqlsrv_|::)query\s*\(.*\$.*\)', 
        "Use of non parameterized SQL Queries can lead to SQLI and is strongly discouraged"
        ],
    "use_variable_in_where":   [
        r'[Ww][Hh][Ee][Rr][Ee]\s*.*=.*\$[^; ]+.*\.',
        "Inserting a variable directly into a SQL Query can lead to SQLI"
        ],
    "use_variable_in_where_and_or":   [
        r'([Ww][Hh][Ee][Rr][Ee]|[Aa][Nn][Dd]|[Oo][Rr])\s+.*\s+[Ll][Ii][Kk][Ee]\s+.*\$.*\.',
        "Inserting a variable directly into a SQL Query can lead to SQLI"
        ],
    "use_VALUES":   [
        r'VALUES\s*\([^\)]*\$.*\)',
        "VALUES followed by a variable may lead to SQLI"
        ],
    "use_include_require":   [
        r'(include|include_once|require|require_once)\s*\([^\;\}\{]*\$.*\)',
        "Use of variables following include or require may lead to file inclusion vulnerabilities"
        ],
    "use_print_param":   [
        r'print.*\s*\((?<!htmlspecialchars\().*\);', 
        "Printing parameters may lead to XSS or database leakage"
        ],
    "use_extract_user_input":   [ 
        rf'extract\s*\(\$.*\)',
        "Use of extract on user input may be a sign of SQLI"
        ],
    "use_new_user_input":   [
        rf'new\s+\$.*\(', 
        "Creating a PHP class from user input using new is dangerous and could lead to RCE"
        ],
    "use_callback_function":   [
        rf'{callback_func_regex}\s*\(\s*\$.*\)', 
        "PHP Callback functions could allow RCE or Information Disclosure from input"
        ],
    "use_filesystem_function":   [
        rf'{filesystem_func_regex}\s*\(\s*\$.*\)', 
        "PHP filesystem functions could allow RCE or Information Disclosure from input"
        ],
    "use_functionhandling_function":   [
        rf'{functionhandling_func_regex}\s*\(\s*\$.*\)', 
        "PHP filesystem functions could allow RCE or Information Disclosure from input"
        ],
    "use_phpinfo":   [
        r'phpinfo\(', 
        "phpinfo() can lead to information disclosure on the website"
        ],
    "use_preg_eval":   [
        r'preg_replace\(.*/e.*\)', 
        "preg_replace using /e can lead to RCE"
        ],
    "use_unserialize":   [
        r'unserialize\(.*\)', 
        "unserializing user input can lead to RCE"
        ],
    "use_md5":   [
        r'md5\(', 
        "md5 is a known insecure hashing algorithm and should be avoided when possible"
        ],
    "host_header_poisoning":   [
        r'\$_SERVER\[.HTTP_HOST.\]\s*\.\s*.\/reset_password.*', #reset password functionality always used reset_password.php as the endpoint
        "Using Host Headers to generate reset links can allow attackers to point reset links to malicious domains to steal tokens"
        ],
    "production_warning":   [
        r'(not|never|improve|for|dont|don\'t|do not).*production',
        "The LLM marked code as needing further review before production implementation. This can be a sign of a vulnerability."
        ],
}
