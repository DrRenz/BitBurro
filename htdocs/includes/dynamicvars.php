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

if (SSLCon()) {
  $accessmethod="https://";
} else {
  $accessmethod="http://";
}

$base_url=$accessmethod.$baseserver;
$filebase_url=$base_url.'/'.$filepath;
$filebase_fs=$documentroot.'/'.$filepath;

?>
