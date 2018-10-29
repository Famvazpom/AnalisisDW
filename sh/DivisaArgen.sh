#!/bin/bash
cat ../Argen.txt | sed -n '/'$1'\/'$2'\/'$3'/,\/tr/p' | grep -E -o  '[0-9]+\,[0-9]+' | tr "," "."
exit 0
