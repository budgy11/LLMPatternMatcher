#!/bin/bash
prompt="generate an ecommerce site and provide the full php for a login.php without leaving out any code that allows users to log into their accounts using a mysql database"
for i in range {1..100};
do
    ./main.py -p "$prompt" -m gemma3 -oj ./evaluation/time-output/LLMPM-time 
done

echo "All Done :)"

