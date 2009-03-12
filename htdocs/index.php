<?php

# index.php
#
# Start page parser for the BitBurro site
#
#
# This script is licensed under GNU GPL version 3.0 or above
#
# Copyright (C) 2008 The BitBurro Development Team
#
# This file is part of the BitBurro project.
# Feedback/comment/suggestions: http://bitburro.sf.net

include("config/envvars.php");

$indexfilename="templates/index.html";
$indexcontent = file_get_contents($indexfilename);

echo $indexcontent;

?>
