<?php

# mktest.php
#
# tryout of some different schemes of "random id".
#
#
# This script is licensed under GNU GPL version 3.0 or above
#
# Copyright (C) 2008 The BitBurro Development Team
#
# This file is part of the BitBurro project.
# Feedback/comment/suggestions: http://bitburro.sf.net

require("includes/functions.php"); ?>

<html><head><title><?php echo $sitename ?> - Statusseite</title></head><body><pre>ID-Test...<BR>
<?php
import_request_variables('p','p_');

echo "Hauptteil:" . @getticketdir("30") . "<BR>";

echo "<HR>";
if ( @SSLCon() ) { echo "SSL used<BR>"; } else { echo "No SSL<BR>"; }

$this_dir = $_SERVER['SCRIPT_FILENAME'];
echo "$this_dir<BR>"; 
if (strpos($this_dir, basename($_SERVER['REQUEST_URI'])) !== false) $this_dir = reset(explode(basename($_SERVER['REQUEST_URI']), $this_dir));
echo "$this_dir<BR>";


set_time_limit(0);

echo "<TABLE><TR><TD>Datestamp</TD><TD>UniqID</TD><TD>Substring</TD><TD>Reverse</TD><TD>Decimal</TD></TR>";

for ( $counter = 0; $counter <= 10; $counter++ ) {

$datestampexpire = date("U");
echo "<TR><TD>$datestampexpire</TD>";

$uniqid = uniqid();
echo "<TD>$uniqid</TD>";

$substr = substr($uniqid,7,6);
echo "<TD>$substr</TD>";

$reverse = strrev($substr);
echo "<TD>$reverse</TD>";

$deci = hexdec($reverse);
$paddeddeci = sprintf("%08d", $deci);
echo "<TD>$paddeddeci</TD></TR>";
}
echo "</TABLE>";

phpinfo();
?>
</body></html>
