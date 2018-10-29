#!/bin/bash
cat ../mexico.txt| cut -d "[" -f2 | cut -d ":" -f3 | tr -d "}]\""
exit 0
