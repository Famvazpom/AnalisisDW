#!/bin/bash
 cat ../config.txt | grep $1 | cut -d "=" -f2
exit 0
