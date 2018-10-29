#!/bin/bash
cat ../RepDom.txt  | grep -a -E -o "[0-9]+\.[0-9]{2}"
exit 0
