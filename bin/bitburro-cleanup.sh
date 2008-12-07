#!/bin/bash
#
# bitburro-cleanup.sh
#
# finds and removes obsolete tickets from BitBurro filebase
#
#
# This script is licensed under GNU GPL version 3.0 or above
#
# Copyright (C) 2008 The BitBurro Development Team
#
# This file is part of the BitBurro project.
# Feedback/comment/suggestions: http://bitburro.sf.net

filebase=/data/filebase
datenow=`date +%s`

#find $filebase/ -type d -maxdepth 1 ! -wholename $filebase/ -ctime +31 -exec rm -r {} \;

cd $filebase && find . -type d -maxdepth 1 | egrep -v '^.$' | sed 's/\.\///g' | while read DATE; do
  if [[ $datenow -gt $DATE ]]; then
    rm -rf $DATE
  fi
done
