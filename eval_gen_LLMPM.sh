#!/bin/bash 
./main.py -m gemma3 -i evaluation/code_review/markdown-output/cart.md > evaluation/LLMPatternMatcher-Custom/cart-review.md
./main.py -m gemma3 -i evaluation/code_review/markdown-output/forgot-password.md > evaluation/LLMPatternMatcher-Custom/forgot-password-review.md
./main.py -m gemma3 -i evaluation/code_review/markdown-output/login.md > evaluation/LLMPatternMatcher-Custom/login-review.md
./main.py -m gemma3 -i evaluation/code_review/markdown-output/purchase.md > evaluation/LLMPatternMatcher-Custom/purchase-review.md
./main.py -m gemma3 -i evaluation/code_review/markdown-output/registration.md > evaluation/LLMPatternMatcher-Custom/registration-review.md
./main.py -m gemma3 -i evaluation/code_review/markdown-output/review.md > evaluation/LLMPatternMatcher-Custom/review-review.md
./main.py -m gemma3 -i evaluation/code_review/markdown-output/search.md > evaluation/LLMPatternMatcher-Custom/search-review.md
