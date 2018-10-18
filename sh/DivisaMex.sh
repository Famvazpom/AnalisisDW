cat ../mexico.txt| cut -d "[" -f2 | cut -d ":" -f3 | tr -d "}]\""
