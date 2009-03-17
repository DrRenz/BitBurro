<?php

# functions.php
#
# contains file handling and parser functions used across multiple scripts.
#
#
# This script is licensed under GNU GPL version 3.0 or above
#
# Copyright (C) 2008 The BitBurro Development Team
#
# This file is part of the BitBurro project.
# Feedback/comment/suggestions: http://bitburro.sf.net

require ("includes/dynamicvars.php");
include ("config/config.php");

function getticketdir($maxage) {

  global $filepath;
  global $permissions;

  $datestampexpire = date("U");
  echo "$datestampexpire $maxage<BR>";
  if ($maxage) {
      if ($maxage<31) {
          $datestampexpire = $datestampexpire+86400*$maxage;
      }
  }
  echo "$datestampexpire<BR>";

  $paddeduniq = sprintf("%08d", hexdec(strrev(substr(uniqid(),7,6))));
  $target_path = "$datestampexpire/$paddeduniq/";
  $documentroot=$_SERVER['DOCUMENT_ROOT'];
  $mkdirpath = "$documentroot/$filepath/$target_path";
  echo "$target_path<BR>";
  echo "$mkdirpath<BR>";
  echo "$permissions<BR>";

  #$mkdirsuccess = mkdir ($mkdirpath, $permissions, 1);
  $mkdirsuccess = mkdir ($mkdirpath, 0755, 1);
  if ($mkdirsuccess) {
    echo "Success: $mkdirsuccess<BR>";
  } else {
    echo "Fail: $mkdirsuccess<BR>";
  }

  return $target_path;  
}

function SSLCon(){ 
  switch ($_SERVER['HTTPS']) {
    case 1: {
       return TRUE;
    }
    case "on": {
       return TRUE;
    }
    case "off": { 
       return FALSE; 
    }
    default: {
       return FALSE;
    }
  }
} 
?>
