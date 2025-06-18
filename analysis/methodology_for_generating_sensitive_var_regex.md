# Methodology for Sensitive Variables
Horusec, Sonarqube, and manual analysis were used to find variables that likely contained sensitive data. Strings such as `your_password` were used to identify sensitive variables along with the usage of the variables within code. Most variables and strings included in `sensitive_vars.txt` did not need much modification and were implemented with just the variable name.

For variables that give a lot of false positive output and sensitive data output the below process was used.

The samples files were checked for the file using grep output.

```bash
grep -iE '\$db\s'
```

This returned 590 outputs with a large number of lines containing strings such as `your_password`, `your_database_password`  or other strings that represent sensitive data that should not be stored within code. The outputs also contained a large portion of comments describing the variable without sensitive data. The outputs are then piped into an inverse grep command to see what outputs where filtered out in the new regular expression, as shown below.

```bash
grep -iE '\$db\s' samples/*  | grep -v -iE '\$db\s+.*password'  > db_var_output.txt 
```

The filtered output did not contain anything suggesting the storage of sensitive data within code and filtered out 290 likely false positives. The inverted regex `\$db\s+.*password` was added to the `sensitive_vars.txt` file to find dangerous usage of the `$db` variable while minimizing potential false positives.


