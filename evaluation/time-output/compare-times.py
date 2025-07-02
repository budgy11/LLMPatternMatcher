#!/usr/bin/env python3

def calc_avg(file):
    total = 0.0
    cnt = 0
    with open(file,'r') as fh:
        for line in fh:
            line = line.strip()
            total += float(line)
            cnt+=1

    return total/cnt

print(calc_avg('ollama-time.txt'))
print(calc_avg('LLMPM-times.txt'))


print(calc_avg('LLMPM-times.txt') - calc_avg('ollama-time.txt'))

