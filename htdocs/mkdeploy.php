<?php

# mkdeploy.php
#
# puts a single file into a directory on your web server,
# the so-called "upload handler"
#
#
# This script is licensed under GNU GPL version 3.0 or above
#
# Copyright (C) 2008 The BitBurro Development Team
#
# This file is part of the BitBurro project.
# Feedback/comment/suggestions: http://bitburro.sf.net

require("includes/functions.php");

echo'<html><head><title>'.$sitename.' - Statusseite</title></head>';
echo '<body><pre>Start des Filehandlings...<BR>';

import_request_variables('p','p_');

//set_time_limit(0);

$idpath = getticketdir($p_maxage);
echo "$idpath<BR>";

$filename = basename( $_FILES['uploadedfile']['name']);

$defaultfilename = "none";
$dodgychars = "[^0-9a-zA-Z()_-]"; // allow only alphanumeric, underscore, parentheses and hyphen

$filename = preg_replace("/^[.]*/","",$filename); // lose any leading dots
$filename = preg_replace("/[.]*$/","",$filename); // lose any trailing dots
$filename = $filename?$filename:$defaultfilename; // if filename is blank, provide default 
$lastdotpos=strrpos($filename, "."); // save last dot position
$filename = preg_replace("/$dodgychars/","_",$filename); // replace dodgy characters

$afterdot = "";
if ($lastdotpos !== false) { // Split into name and extension, if any.
    $beforedot = substr($filename, 0, $lastdotpos);
    if ($lastdotpos < (strlen($filename) - 1)) $afterdot = substr($filename, $lastdotpos + 1);
} else $beforedot = $filename;

if ($afterdot) $filename = $beforedot . "." . $afterdot;
    else $filename = $beforedot;

$target_dir_fs = "$filebase_fs/$idpath";
$target_file_fs = "$target_dir_fs/$filename";
$target_file_url = "$filebase_url/$idpath/$filename";

if ($_FILES['uploadedfile']['error']=="0") {
  if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_file_fs)) {
      echo "Start Datei-Handling...<BR>";
      //print_r($_FILES);
      if (isset($p_passenable)) if ($p_passenable == "on") {
          echo "Passwort-Schutz wird gesetzt...<BR>";
          createACL($p_password,$p_sourcemail,$p_targetmail,$target_dir_fs);
      }
      echo "<B>Datei erfolgreich empfangen.</B><BR>";
      echo 'Gesamter Link ist: <A HREF="'.$target_file_url.'">'.$target_file_url.'</A><BR>"';
    
      if ($p_targetmail) {
          echo "Mail wird verschickt...<BR>";
          $headers= "MIME-Version: 1.0\r\n";
          $headers.= "Content-type: text/plain; charset=iso-8859-1\r\n";
          if ($p_sourcemail) {
              $headers.= "From: <$p_sourcemail>\r\n";
          } else {
              $headers.= "From: ".$mailfrom."\r\n";
          }

          $content="Hallo lieber $sitename-Kunde,\r\n\r\n";
          $content.="fuer Sie wurde eine neue Datei bereitgestellt. Sie koennen diese unter\r\n\r\n";
          $content.="$target_file_url\r\n\r\n";
          $content.="abrufen.\r\n\r\n";
          if ($p_passenable == "on" ) {
              $content.="Fuer die Datei wurde ein Passwort hinterlegt; das Passwort lautet: \"" . $p_password . "\"\r\n\r\n";
          }
          $content.="Die Datei wird sieben Tage lang vorgehalten; danach wird sie unwiederbringlich geloescht.\r\n\r\n";
          $content.="Ihr $sitename-Team ($mailsupport)\r\n";
          $ok=mail($p_targetmail, $sitename."-Dateilink", $content, $headers);
          if ( $ok == "1" ) { echo "Mail erfolgreich versandt.<BR>"; }
      }
  } else{
      echo "Fehler beim Einsortieren; bitte erneut probieren!";
  }
} else {
  echo "Fehler beim Hochladen; bitte erneut probieren!";
}
?>
</body></html>
