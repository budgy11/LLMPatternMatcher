#!/bin/bash

languages=("php" "ruby" "csharp" "java" "javascript")
models=("llama3.1" "deepseek-r1" "gemma3")
ctr=1
while read -r prompt; do 
        for i in "${languages[@]}"; do
                for j in "${models[@]}"; do
                        ./main.py -p "$prompt using $i" -m $j >> $i/$j-$ctr.json
                done
        done
        ((ctr++))
done < ./test_prompts.txt
