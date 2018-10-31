 cat ../Can.xml | sed 's/<v s=/\n/g'| grep 'FXUSDCAD' | grep -E -o '[0-9]+\.[0-9]+' | tail -1
