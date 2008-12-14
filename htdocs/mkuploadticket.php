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

include("envvars.php"); ?>
<html><head><title><?php echo $sitename ?> - Statusseite</title></head><body><pre>Start des Filehandlings...<BR>
<?php
import_request_variables('p','p_');

set_time_limit(0);

$datestampexpire = date("U");
echo "$datestampexpire<BR>";
if ($p_maxage) {
    echo "$p_maxage<bR>";
    if ($p_maxage<31) {
        $datestampexpire = date("U", mktime(date("G"),date("i"),date("s"),date("m"),date("d")+$p_maxage,date("Y")));
    }
}
echo "$datestampexpire<BR>";

$randomnumber = rand();

$target_path = "$filepath/$datestampexpire/$randomnumber/";

mkdir ($target_path, 0755, 1);

$linkaddress = $baseserver . "/" . $target_path;
echo "Start Datei-Handling...<BR>";
print_r($_FILES);

$indexfilecontent="<html><head><meta HTTP-EQUIV=\"REFRESH\" content=\"1; url=$baseserver/mkfileupload.php?id1=$datestampexpire&id2=$randomnumber\">";
$indexfilecontent.="<title>$sitename - Redirect</title></head><body>Einen Moment...</body></html>\r\n";
$indexfilename=$target_path . "upload.html";
$indexfile=fopen($indexfilename,"w");
fwrite($indexfile,$indexfilecontent);
fclose($indexfile);

$accesscontent="";
if ($p_passenable == "on") {
    echo "Passwort-Schutz wird gesetzt...<BR>";
    $cryptedpassword = crypt($p_password, base64_encode($p_password));
    $passfilecontent="$p_targetmail:$cryptedpassword\r\n";
  
    $passfilename=$target_path . ".htpasswd";
    $passfile=fopen($passfilename,"w");
    fwrite($passfile,$passfilecontent);
    fclose($passfile);
    $accesscontent="AuthName \"".$sitename."-Passwortschutz\"\r\n";
    $accesscontent.="AuthType Basic\r\n";
    $accesscontent.="AuthUserFile $webrootroot/$target_path/.htpasswd\r\n";
    $accesscontent.="require valid-user\r\n";
}
$accesscontent.="Options +Indexes\r\n";
$accesscontent.="IndexOptions FancyIndexing\r\n";
$accesscontent.="IndexIgnore upload.html\r\n";
$accesscontent.="IndexIgnore README.txt\r\n";
$accesscontent.="HeaderName README.txt\r\n";

$accessfilename=$target_path . ".htaccess"; 
$accessfile=fopen($accessfilename,"w");
fwrite($accessfile,$accesscontent);
fclose($accessfile);

if ($p_sourcemail) {
    $readmecontent="Hallo $sitename-Kunde!\r\n";
    $readmecontent.="Dieses Verzeichnis wurde fuer Sie angelegt von $p_sourcemail. Viel Spass!\r\n";

    $readmefilename=$target_path . "README.txt"; 
    $readmefile=fopen($readmefilename,"w");
    fwrite($readmefile,$readmecontent);
    fclose($readmefile);
}

echo "<B>Upload-Verzeichnis erfolgreich angelegt.</B><BR>";
echo "Gesamter Link ist: <A HREF=\"$linkaddress\">$linkaddress</A><BR>";
    
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
    $content.=$linkaddress."upload.html\r\n\r\n";
    $content.="Dateien hinterlegen. Eine Uebersicht ueber die Inhalte bekommen Sie unter \r\n\r\n";
    $content.="$linkaddress\r\n\r\n";
    $content.="angezeigt.\r\n\r\n";
    if ($p_passenable == "on" ) {
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
