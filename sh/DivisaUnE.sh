cat ../UnEur.txt | sed -n '/Reference rates\: '$1'/,\/table>/p'| sed 's/\/td/\n/g'| grep '>'$2'<'| tr '>' '\n' | tr -d '<' | tail -1

