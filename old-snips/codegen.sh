#!/bin/bash

models=("gpt-4.1-nano")


ctr=1
while read -r line;
do
        for i in ${languages[@]}
        do
                for j in ${models[@]}
                do
                        #./main.py -m "$j" -p "$line using $i" -t 'TOKEN' > "$i/$j-$ctr.json"
                        ./main.py -m "$j" -p "$line using Ruby on Rails" > "RubyOnRails/$j-$ctr.json"
                        sleep 20
                done
        done
        ((ctr++))
done < test_prompts.txt

