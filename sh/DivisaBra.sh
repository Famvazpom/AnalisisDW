#!/bin/bash
cat ../brazil.txt | grep -E  -o '[0-9]+\,[0-9]{4}' | tr "," "." |tail -1 
exit 0
