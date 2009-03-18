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

include ("config/config.php");
require ("includes/dynamicvars.php");


function getUniqueID($path,$maxage) {

  global $maxchecks;

  $trysleft = $maxchecks;
  do {
    $expiry = date("U")+86400*$maxage;
    $id = getRandomID();
    $idpath=$path.'/'.$expiry;
  } while (isIDUnique($idpath, $id) == FALSE && --$trysleft);
  if (!$trysleft) {
    // Damn, can't find any unique IDs. The apocalypse must have happened!
    die("The apocalypse must have happened! (Or LHC started working)");
  }
  return $expiry.'/'.$id;
}


function getRandomID() {
  // Choose one:
  // return rand();
  // return md5(microtime());
  return sprintf("%08d", hexdec(strrev(substr(uniqid(),7,6))));
  // use your own...
}


function isIDUnique($path, $id) {
  $path2check="$path/$id";
  // For testing:
  //mkdir($path2check,0755,1);
  if (is_dir($path2check)) {
    // Directory exists, so it's not unique :-(
    return FALSE;
  } else {
    return TRUE;
  }
}


function getticketdir($maxage) {

  global $documentroot;
  global $filepath;
  global $permissions;

  $checkidpath = "$documentroot/$filepath";
  $target_path = getUniqueID($checkidpath,$maxage);
  $mkdirpath = "$documentroot/$filepath/$target_path";

  #$mkdirsuccess = mkdir ($mkdirpath, $permissions, 1);
  $mkdirsuccess = mkdir ($mkdirpath, 0775, 1);
  #if ($mkdirsuccess) {
    #echo "Success: $mkdirsuccess<BR>";
  #} else {
    #echo "Fail: $mkdirsuccess<BR>";
  #}

  return $target_path;  
}

function SSLCon(){ 
  if(isset($_SERVER['HTTPS'])) {
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
  } else {
    return FALSE;
  }
} 


function createACL($password,$sourcemail,$targetmail,$targetdir) {

  global $sitename;

  $cryptedpassword = crypt($password, base64_encode($password));

  $passfilecontent="$targetmail:$cryptedpassword\r\n";
  if ($sourcemail) $passfilecontent.="$sourcemail:$cryptedpassword\r\n";

  $passfilename=$targetdir."/.htpasswd";
  $passfile=fopen($passfilename,"w");
  fwrite($passfile,$passfilecontent);
  fclose($passfile);

  $accesscontent="AuthName \"".$sitename."-Passwortschutz\"\r\n";
  $accesscontent.="AuthType Basic\r\n";
  $accesscontent.="AuthUserFile $targetdir/.htpasswd\r\n";
  $accesscontent.="require valid-user\r\n";

  $accessfilename=$targetdir . "/.htaccess";
  $accessfile=fopen($accessfilename,"w");
  fwrite($accessfile,$accesscontent);
  fclose($accessfile);
}


function showHTMLFromTemplate($templatefile) {

  global $documentroot;
  global $templatepath;
  global $sitename;
  global $mailsupport;

  $templatetags = array("%%%SITENAME%%%", "%%%MAILADDRESS%%%");
  $templatesubst = array($sitename, $mailsupport);

  $templatefilename="$templatepath/$templatefile";
  $templatecontent = file_get_contents($templatefilename);
  $outputcontent = str_replace($templatetags, $templatesubst, $templatecontent);

  echo $outputcontent;
}

?>
