jq -r ".code_blocks[] | select(. != null)" *-100/*  | awk '{gsub(/\\n/,"\n")}1' | sed 's/```[php]\{0,3\}//g'
