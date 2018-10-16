cat canada.txt | grep $1 | sed "s/FXUSDCAD/\n/g" | cut -d ":" -f3 | cut -d "}" -f1|tail -1
