#!/bin/bash

#TODO MISSING 404-500 DEEPSEEK PURCHASING

models=("llama3.1" "gemma3" "deepseek-r1:32b")

while read -r line;
do
        for model in ${models[@]};
        do
                for i in {101..500};
                do
                        ./main.py -p "$(echo -n $line | awk -F',' '{print $1}')" -m $model > "$(echo -n $line | awk -F',' '{print $2}')/$model-$i.json"
                done
        done
done < last_two.txt
