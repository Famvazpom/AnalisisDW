#!/bin/bash
cat ../Can.csv | grep $1 | cut -d "," -f26
exit 0
