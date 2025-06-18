jq -r '.analysisVulnerabilities[].vulnerabilities | select(.language == "Leaks") | .code ' horusec-output.json |sort -u
