<?php
/**
 * /system/index.php
 *
 * This file is part of DomainMOD, an open source domain and internet asset manager.
 * Copyright (C) 2010-2015 Greg Chetcuti <greg@chetcuti.com>
 *
 * Project: http://domainmod.org   Author: http://chetcuti.com
 *
 * DomainMOD is free software: you can redistribute it and/or modify it under the terms of the GNU General Public
 * License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later
 * version.
 *
 * DomainMOD is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
 * warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with DomainMOD. If not, see
 * http://www.gnu.org/licenses/.
 *
 */
?>
<?php
include("../_includes/start-session.inc.php");
include("../_includes/config.inc.php");
include("../_includes/database.inc.php");
include("../_includes/software.inc.php");
include("../_includes/auth/auth-check.inc.php");

$page_title = "Control Panel";
$software_section = "system";
?>
<?php include("../_includes/doctype.inc.php"); ?>
<html>
<head>
<title><?php echo $software_title . " :: " . $page_title; ?></title>
<?php include("../_includes/layout/head-tags.inc.php"); ?>
</head>
<body>
<?php include("../_includes/layout/header.inc.php"); ?>
<font class="subheadline">User Menu</font><BR><BR>
&raquo; <a href="display-settings.php">Display Settings</a>&nbsp;&nbsp;/&nbsp;&nbsp;<a href="email-settings.php">Email Settings</a>&nbsp;&nbsp;/&nbsp;&nbsp;<a href="defaults.php">User Defaults</a><BR><BR>
&raquo; <a href="update-profile.php">Update Profile</a>&nbsp;&nbsp;/&nbsp;&nbsp;<a href="change-password.php">Change Password</a><BR>
<BR><BR><font class="subheadline">Maintenance Menu</font><BR><BR>
&raquo; <a href="../_includes/system/update-conversion-rates.inc.php?direct=1">Update Conversion Rates</a>&nbsp;&nbsp;/&nbsp;&nbsp;<a href="../_includes/system/update-domain-fees.inc.php?direct=1">Update Domain Fees</a>&nbsp;&nbsp;/&nbsp;&nbsp;<a href="../_includes/system/update-ssl-fees.inc.php?direct=1">Update SSL Fees</a><BR>
<?php
if ($_SESSION['is_admin'] == 1) { ?>
    <BR><BR><font class="subheadline">Admin Menu</font><BR><BR>
    &raquo; <a href="admin/system-settings.php">System Settings</a>&nbsp;&nbsp;/&nbsp;&nbsp;<a href="admin/defaults.php">System Defaults</a>&nbsp;&nbsp;/&nbsp;&nbsp;<a href="admin/users.php">Users</a><BR><BR>
    &raquo; <a href="admin/domain-fields.php">Custom Domain Fields</a>&nbsp;&nbsp;/&nbsp;&nbsp;<a href="admin/ssl-fields.php">Custom SSL Fields</a><BR><BR>
    &raquo; <a href="../_includes/system/admin/delete-unused-domain-fees.inc.php?direct=1">Delete Unused Domain Fees</a>&nbsp;&nbsp;/&nbsp;&nbsp;<a href="../_includes/system/admin/delete-unused-ssl-fees.inc.php?direct=1">Delete Unused SSL Fees</a><BR><BR>
    &raquo; <a href="admin/dw/">Data Warehouse</a><BR><BR>
    &raquo; <a href="admin/system-info.php">System Information</a><BR><?php
} ?>
<?php include("../_includes/layout/footer.inc.php"); ?>
</body>
</html>
