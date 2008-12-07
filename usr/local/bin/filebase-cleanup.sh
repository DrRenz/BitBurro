#!/bin/bash

filebase=/data/filebase
datenow=`date +%s`

#find $filebase/ -type d -maxdepth 1 ! -wholename $filebase/ -ctime +31 -exec rm -r {} \;

cd $filebase && find . -type d -maxdepth 1 | egrep -v '^.$' | sed 's/\.\///g' | while read DATE; do
  if [[ $datenow -gt $DATE ]]; then
    rm -rf $DATE
  fi
done
