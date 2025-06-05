#!/bin/bash

model='deepseek-r1:32b'
for i in {1..100};
do
        ./main.py -p 'Create a user login function using php' -m $model > "lots-of-code/$model-$i.json"
done
