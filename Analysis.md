# Analysis of Gemma3 PHP Code
Analysis primarily consisted of using bash to look for cool stuff in generated code

```bash
#finding db_password variables
find . -iname "gemma3.php" -exec grep -E '^\$conn' {} \; | sort -u | grep password
find . -iname "gemma3.php" -exec grep -E '\$db_password\s*=' {} \; #returns 682 instances of $db_password being set to a password or empty string (rarely)
```