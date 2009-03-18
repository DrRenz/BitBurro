<?php

# mkuploadticket.php
#
# creates an upload ticket directory and notification email
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

$singleids=explode("/",$idpath,2);
var_dump($singleids);

$target_dir_fs = "$filebase_fs/$idpath";
$target_dir_url = "$filebase_url/$idpath";

echo "Start Datei-Handling...<BR>$target_dir_fs<BR>$target_dir_url<BR>";

// FixMe: Target zeigt noch auf Server-Root...
$uploadtarget=$base_url.'/mkfileupload.php?id1='.$singleids[0].'&id2='.$singleids[1];
$indexfilecontent="<html><head><meta HTTP-EQUIV=\"REFRESH\" content=\"1; url=$uploadtarget\">";
$indexfilecontent.="<title>$sitename - Redirect</title></head><body>Einen Moment...</body></html>\r\n";
$indexfilename=$target_dir_fs."/upload.html";
$indexfile=fopen($indexfilename,"w");
fwrite($indexfile,$indexfilecontent);
fclose($indexfile);

$accesscontent="";
  if (isset($p_passenable)) if ($p_passenable == "on") {
    echo "Passwort-Schutz wird gesetzt...<BR>";
    //createACL($p_password,$p_sourcemail,$p_targetmail,$target_dir_fs);
   //FixMe: htaccess wird mit angelegt, soll aber in diesem Fall nich...
}
$accesscontent.="Options +Indexes\r\n";
$accesscontent.="IndexOptions FancyIndexing\r\n";
$accesscontent.="IndexIgnore upload.html\r\n";
$accesscontent.="IndexIgnore README.txt\r\n";
$accesscontent.="HeaderName README.txt\r\n";

$accessfilename=$target_dir_fs . "/.htaccess"; 
$accessfile=fopen($accessfilename,"w");
fwrite($accessfile,$accesscontent);
fclose($accessfile);

if ($p_sourcemail) {
    $readmecontent="Hallo $sitename-Kunde!\r\n";
    $readmecontent.="Dieses Verzeichnis wurde fuer Sie angelegt von $p_sourcemail. Viel Spass!\r\n";

    $readmefilename=$target_dir_fs . "/README.txt"; 
    $readmefile=fopen($readmefilename,"w");
    fwrite($readmefile,$readmecontent);
    fclose($readmefile);
}

echo "<B>Upload-Verzeichnis erfolgreich angelegt.</B><BR>";
echo 'Gesamter Link ist: <A HREF="'.$target_dir_url.'">'.$target_dir_url.'</A><BR>';
echo 'Dateien hinterlegen mit: <A HREF="'.$target_dir_url.'/upload.html">'.$target_dir_url.'/upload.html</A><BR>';
    
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
    $content.="fuer Sie wurde ein Upload-Verzeichnis bereitgestellt. Sie koennen nun unter\r\n\r\n";
    $content.=$target_dir_url."/upload.html\r\n\r\n";
    $content.="Dateien hinterlegen. Eine Uebersicht ueber die Inhalte bekommen Sie unter \r\n\r\n";
    $content.="$target_dir_url\r\n\r\n";
    $content.="angezeigt.\r\n\r\n";
    if (isset($p_passenable)) if ($p_passenable == "on" ) {
        $content.="Fuer das Verzeichnis wurde ein Passwort hinterlegt; das Passwort lautet: \"" . $p_password . "\"\r\n\r\n";
    }
    $content.="Die Verzeichnisinhalte werden ";
    if ($p_maxage == "1") {
        $content.="nur 24 Stunden lang";
    } else {
        $content.=$p_maxage . " Tage lang";
    }
    $content.=" vorgehalten; danach werden sie unwiederbringlich geloescht.\r\n\r\n";
    $content.="Ihr $sitename-Team ($mailsupport)\r\n";
    $ok=mail($p_targetmail, $sitename."-Uploadlink", $content, $headers);
    if ( $ok == "1" ) { echo "Mail erfolgreich versandt.<BR>"; }
}
?>
</body></html>
