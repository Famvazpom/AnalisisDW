cat ../Cop.txt | grep $1 | grep -E -o "[0-9]+\,[0-9]+\.[0-9]{2}"
