cat ../brazil.txt | grep -E  -o '[0-9]+\,[0-9]{4}' | tail -1
