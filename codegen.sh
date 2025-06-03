#!/bin/bash

languages=("ASP.NET" "Node.JS"  "Spring")
models=("deepseek-r1")


ctr=1
while read -r line;
do
        for i in ${languages[@]}
        do
                for j in ${models[@]}
                do
                        ./main.py -m "$j" -p "$line using $i" > "$i/$j-$ctr.json"
                done
        done
        ((ctr++))
done < test_prompts.txt

