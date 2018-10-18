cat ../Argen.txt | sed -n '/'$1'/,\/tr/p' | grep -E -o  '[0-9]+\,[0-9]+'
