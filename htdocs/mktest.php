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

include("config/envvars.php"); ?>
<html><head><title><?php echo $sitename ?> - Statusseite</title></head><body><pre>ID-Test...<BR>
<?php
import_request_variables('p','p_');

set_time_limit(0);

echo "<TABLE><TR><TD>Datestamp</TD><TD>UniqID</TD><TD>Decimal</TD></TR>";

for ( $counter = 0; $counter <= 10; $counter++ ) {

$datestampexpire = date("U");
echo "<TR><TD>$datestampexpire</TD>";

$uniqid = uniqid();
echo "<TD>$uniqid</TD>";

$substr = substr($uniqid,7,6);
//echo "SubStr: $substr<BR>";

$reverse = strrev($substr);
//echo "Reverse: $reverse<BR>";

$deci = hexdec($reverse);
$paddeddeci = sprintf("%08d", $deci);
echo "<TD>$paddeddeci</TD></TR>";
}
echo "</TABLE>";

?>
</body></html>
