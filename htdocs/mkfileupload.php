<?php

# mkfileupload.php
#
# first-contact handler for upload tickets, arguments given
# are the two identifiers. Usually called from a redirect page
# inside the upload ticket directory.
#
#
# This script is licensed under GNU GPL version 3.0 or above
#
# Copyright (C) 2008 The BitBurro Development Team
#
# This file is part of the BitBurro project.
# Feedback/comment/suggestions: http://bitburro.sf.net

require("includes/functions.php"); ?>
<html><head><title><?php echo $sitename ?> - File storage</title></head>
<body><div align=right><img src="/bitburro.gif" alt="BitBurro Logo" width="240" height="88"></div>
<font size="+3" face="Helvetica,Verdana,Arial"><?php echo $sitename; ?></font>
<P>

<form enctype="multipart/form-data" action="mkdeployupload.php" method="POST">
<input type="hidden" name="MAX_FILE_SIZE" value="2000000000">
<input type="hidden" name="ID1" value="<?php echo htmlspecialchars($_GET["id1"]) ?>">
<input type="hidden" name="ID2" value="<?php echo htmlspecialchars($_GET["id2"]) ?>">
<table border="0"><tr>
<td><font face="Helvetica,Verdana,Arial">Dateiname:</font></td>
<td><input name="uploadedfile" type="file" /></td>
</tr><tr>
<td><font face="Helvetica,Verdana,Arial">Benachrichtigung schicken:</font></td>
<td><input type="checkbox" name="notify"><br><font size="-2">geht noch nicht</font></td>
</tr><tr>
<td></td>
<td><input type="submit" value="Hochladen" /></td>
</tr></table>
</form>
</body></html>
