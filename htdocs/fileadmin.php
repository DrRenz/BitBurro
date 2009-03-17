<?php

# fileadmin.php
#
# shows a list of filebase contents on your web server,
# allowing removal of single directories
#
#
# This script is licensed under GNU GPL version 3.0 or above
#
# Copyright (C) 2008 The BitBurro Development Team
#
# This file is part of the BitBurro project.
# Feedback/comment/suggestions: http://bitburro.sf.net

require("includes/functions.php"); ?>
<html><head><title><?php echo $sitename ?> - Administration</title></head><body><pre>Hier kommt die Liste...<BR>
<?php
import_request_variables('p','p_');

set_time_limit(0);

$fullpath="$documentroot/$filepath";
$thelist="";
$notdeleted="";
echo '<form enctype="multipart/form-data" action="fileadmin.php" method="POST">';
echo "<table><TR><TD>L&ouml;schen</TD><TD>TicketNr.</TD><TD>Inhalt</TD></TR>";
if ($basedirhandle = opendir($fullpath)) {
  while (false !== ($content = readdir($basedirhandle))) {
    if ($content != "." && $content != "..") {
      $subpath="$fullpath/$content";
      if (is_dir($subpath)) {
        if ($subdirhandle = opendir($subpath)) {
          while (false !== ($subcontent = readdir($subdirhandle))) {
            if ($subcontent != "." && $subcontent != "..") {
              $contentpath="$fullpath/$content/$subcontent";
              $delcheckvar="p_{$content}-{$subcontent}";
              echo "<TR><TD>";
              if (isset($$delcheckvar)) {
                if ($$delcheckvar=="on") {
                  echo 'Deleted.';
                }
              } else {
                echo '<input type="checkbox" name="'.$content.'-'.$subcontent.'">';
              }
              echo '</TD><TD>'.$content.'/'.$subcontent.'</TD><TD>';
              if ($contenthandle = opendir($contentpath)) {
                while (false !== ($contentcontent = readdir($contenthandle))) {
                  if ($contentcontent != "." && $contentcontent != "..") {
                    if (isset($$delcheckvar)) {
                      if ($$delcheckvar=="on") {
                        $killthisfile="$fullpath/$content/$subcontent/$contentcontent";
                        unlink($killthisfile);
                        echo "$contentcontent deleted.<BR>";
                      }
                    } else {
                      echo '<A HREF="'.$content.'/'.$subcontent.'/'.$contentcontent.'">'.$contentcontent.'<BR>';
                    }
                  }
                }
                closedir($contenthandle);
                if (isset($$delcheckvar)) {
                  if ($$delcheckvar=="on") {
                    $killthisdir="$fullpath/$content/$subcontent";
                    rmdir($killthisdir);
                  }
                }
              }
              echo '</TD></TR>';
            }
          }
        }
        closedir($subdirhandle);
      }
    }
  }
  closedir($basedirhandle);
}
echo "</table><BR>";
echo '<input type="submit" value="Ausf&uuml;hren"><BR>';

?>
</body></html>
