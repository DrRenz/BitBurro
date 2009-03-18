<?php

# setenvvars.php
#
# sets some dynamic variables for easier access
#
#
# This script is licensed under GNU GPL version 3.0 or above
#
# Copyright (C) 2008 The BitBurro Development Team
#
# This file is part of the BitBurro project.
# Feedback/comment/suggestions: http://bitburro.sf.net

global $baseserver;
global $filepath;

$documentroot=$_SERVER['DOCUMENT_ROOT'];

$filebase_fs=$documentroot.'/'.$filepath;

if (SSLCon()) {
  $accessmethod="https://";
} else {
  $accessmethod="http://";
}

$filebase_url=$accessmethod.$baseserver.'/'.$filepath;

?>
