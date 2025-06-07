#!/bin/bash

for i in "./*.json"
do 
        #echo $i;
        jq -r ".code_blocks[] | select(. != null)" $i | awk '{gsub(/\\n/,"\n")}1' | sed 's/```[php]\{0,3\}//g' >> "code/$(echo $i | cut -d '-' -f1).php"
done
