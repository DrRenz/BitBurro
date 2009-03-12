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
#$baseserver = "http://www.example.org/bitburro";
$baseserver = "http://bitburro.example.org";

# The "document root" path relative to the file system root
$webrootroot = "/var/www/htdocs/";

# The subdir path (relative to webroot) where the files will be stored 
$filepath = "filebase";

# Do we allow tickets without a "sender" email address?
#
# ...for creating a download-only ticket:
$allow_anonymous_submission = "1";
# ...for creating an upload ticket:
$allow_anonymous_uploads = "1";


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
