<?php

# mkdeployupload.php
#
# handles upload-POST for "upload tickets" and provides a link
# back to upload page for reception of more files.
#
#
# This script is licensed under GNU GPL version 3.0 or above
#
# Copyright (C) 2008 The BitBurro Development Team
#
# This file is part of the BitBurro project.
# Feedback/comment/suggestions: http://bitburro.sf.net

include("envvars.php"); ?>
<html><head><title><?php echo $sitename ?> - Dateiablage</title></head><body>
<?php

$datestamp = date("U");
$randomnumber = rand();
$target_path = "filebase/";
import_request_variables('p','p_');

if ($p_ID1 and $p_ID2) {
    $id1 = preg_replace("/[^0-9]/","",$p_ID1);
    $id2 = preg_replace("/[^0-9]/","",$p_ID2);
    $target_path = $target_path . $id1 . "/" . $id2 . "/"; 
}

$target_file = $target_path . basename( $_FILES['uploadedfile']['name']); 

if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_file)) {
    $linkaddress = $baseserver . "/" . $target_path;
    echo "<h3>Datei erfolgreich empfangen.</h3><P>";
    echo "Der aktuelle Verzeichnisinhalt ist unter <A HREF=\"$linkaddress\">$linkaddress</A> abrufbar.<P>";
    echo "Wollen Sie weitere Dateien hochladen? <A HREF=\"$baseserver\mkfileupload.php?id1=$id1&id2=$id2\">Zurueck zur Upload-Seite.</A><P>";
    if ($p_notify == "on" ) {
        echo "<BR>Mail wird verschickt...";
        $headers= "MIME-Version: 1.0\r\n";
        $headers.= "Content-type: text/plain; charset=iso-8859-1\r\n";
        $headers.= "From: ".$mailfrom."\r\n";

        $content="Hallo lieber $sitename-Kunde,\r\n\r\n";
        $content.="fuer Sie wurde eine neue Datei bereitgestellt. Sie koennen diese unter\r\n\r\n";
        $content.="$linkaddress\r\n\r\n";
        $content.="abrufen.\r\n\r\n";
        if ($p_passenable == "on" ) {
            $content.="Fuer die Datei wurde ein Passwort hinterlegt; das Passwort lautet: \"" . $p_password . "\"\r\n\r\n";
        }
        $content.="Die Datei wird sieben Tage lang vorgehalten; danach wird sie unwiederbringlich geloescht.\r\n\r\n";
        $content.="Ihr $sitename-Team ($mailsupport)\r\n";
        $ok=mail($p_targetmail, $sitename."-Dateilink", $content, $headers);
        echo $ok;
    }
} else{
    echo "Fehler beim Hochladen; bitte erneut probieren!";
}
?>
</body></html>
