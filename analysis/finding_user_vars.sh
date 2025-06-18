grep -iE '^\s*\$.*\s*=\s*\$_(POST|GET|REQUEST|SERVER|COOKIE)' samples/* | tr -d '\/' | cut -d ':' -f2 |  awk '{print $1}' | sort -u

#removed cart[1] cart[2] cart[3] due to duplication
