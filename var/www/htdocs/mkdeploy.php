<?php include("envvars.php"); ?>
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

$target_file = $target_path . $filename;

mkdir ($target_path, 0755, 1);

if ($_FILES['uploadedfile']['error']=="0") {
  if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_file)) {
      $linkaddress = $baseserver . "/" . $target_file;
      echo "Start Datei-Handling...<BR>";
      print_r($_FILES);
      if ($p_passenable == "on") {
          echo "Passwort-Schutz wird gesetzt...<BR>";
          $cryptedpassword = crypt($p_password, base64_encode($p_password));
          $passfilecontent="$p_targetmail:$cryptedpassword\r\n";
          if ($p_sourcemail) $passfilecontent.="$p_sourcemail:$cryptedpassword\r\n";
  
          $passfilename=$target_path . ".htpasswd";
          $passfile=fopen($passfilename,"w");
          fwrite($passfile,$passfilecontent);
          fclose($passfile);
  
          $accesscontent="AuthName \"".$sitename."-Passwortschutz\"\r\n";
          $accesscontent.="AuthType Basic\r\n";
          $accesscontent.="AuthUserFile $webrootroot/$target_path/.htpasswd\r\n";
          $accesscontent.="require valid-user\r\n";

          $accessfilename=$target_path . ".htaccess"; 
          $accessfile=fopen($accessfilename,"w");
          fwrite($accessfile,$accesscontent);
          fclose($accessfile);
      }
      echo "<B>Datei erfolgreich empfangen.</B><BR>";
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
          $content.="fuer Sie wurde eine neue Datei bereitgestellt. Sie koennen diese unter\r\n\r\n";
          $content.="$linkaddress\r\n\r\n";
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
