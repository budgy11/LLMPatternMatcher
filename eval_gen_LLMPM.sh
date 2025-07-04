#!/bin/bash 
./main.py -m llama3.1 -i evaluation/llama3.1/code-review/markdown-output/cart.md > evaluation/llama3.1/LLMPatternMatcher-Default/cart-review.md
./main.py -m llama3.1 -i evaluation/llama3.1/code-review/markdown-output/forgot-password.md > evaluation/llama3.1/LLMPatternMatcher-Default/forgot-password-review.md
./main.py -m llama3.1 -i evaluation/llama3.1/code-review/markdown-output/login.md > evaluation/llama3.1/LLMPatternMatcher-Default/login-review.md
./main.py -m llama3.1 -i evaluation/llama3.1/code-review/markdown-output/purchase.md > evaluation/llama3.1/LLMPatternMatcher-Default/purchase-review.md
./main.py -m llama3.1 -i evaluation/llama3.1/code-review/markdown-output/registration.md > evaluation/llama3.1/LLMPatternMatcher-Default/registration-review.md
./main.py -m llama3.1 -i evaluation/llama3.1/code-review/markdown-output/review.md > evaluation/llama3.1/LLMPatternMatcher-Default/review-review.md
./main.py -m llama3.1 -i evaluation/llama3.1/code-review/markdown-output/search.md > evaluation/llama3.1/LLMPatternMatcher-Default/search-review.md
