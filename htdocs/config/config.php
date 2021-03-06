<?php

# envvars.php
#
# contains basic configuration for the BitBurro site
#
#
# This script is licensed under GNU GPL version 3.0 or above
#
# Copyright (C) 2008 The BitBurro Development Team
#
# This file is part of the BitBurro project.
# Feedback/comment/suggestions: http://bitburro.sf.net


#
# SECTION SERVER SETTINGS
#

# This server's base URL (only host with method, or any subdirectory thereof)
#$baseserver = "www.example.org/bitburro";
#$baseserver = "bitburro.example.org";
$baseserver = $_SERVER['SERVER_NAME'];

# The subdir path (relative to document root) where the files will be stored 
$filepath = "bitburro/filebase";

# Which permissions shall be set on directories and uploaded files?
$permissions = "0755";

# Where are the HTML template stored (relative to document root)?
$templatepath = "templates";

# Do we allow tickets without a "sender" email address?
#
# ...for creating a download-only ticket:
$allow_anonymous_submission = "1";
# ...for creating an upload ticket:
$allow_anonymous_uploads = "1";

# How many tries to get an unique ticket number?
$maxchecks=1024;


#
# SECTION APPEARANCE
#

# The site name, referenced on nearly every occasion :-)
$sitename = "BitBurro";


#
# SECTION MAIL CONTENT
#

# Who can be contacted if everything fails
$mailsupport = "bitburro-support@example.org";

# For anonymous submissions, this is the sender address in outgoing
# emails
#$mailfrom = "BitBurro-Server <bitburro-support@example.org>";
$mailfrom = "BitBurro-Server <noreply@example.org>";


# END OF SITE CONFIG
?>
