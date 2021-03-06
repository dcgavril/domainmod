<?php
/**
 * /admin/ssl-fields/add.php
 *
 * This file is part of DomainMOD, an open source domain and internet asset manager.
 * Copyright (c) 2010-2017 Greg Chetcuti <greg@chetcuti.com>
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
<?php //@formatter:off
require_once('../../_includes/start-session.inc.php');
require_once('../../_includes/init.inc.php');

require_once(DIR_ROOT . '/classes/Autoloader.php');
spl_autoload_register('DomainMOD\Autoloader::classAutoloader');

$system = new DomainMOD\System();
$error = new DomainMOD\Error();
$time = new DomainMOD\Time();
$form = new DomainMOD\Form();
$custom_field = new DomainMOD\CustomField();

require_once(DIR_INC . '/head.inc.php');
require_once(DIR_INC . '/config.inc.php');
require_once(DIR_INC . '/software.inc.php');
require_once(DIR_INC . '/debug.inc.php');
require_once(DIR_INC . '/settings/admin-add-custom-ssl-field.inc.php');
require_once(DIR_INC . '/database.inc.php');

$system->authCheck();
$system->checkAdminUser($_SESSION['s_is_admin']);

$new_name = $_POST['new_name'];
$new_field_name = $_POST['new_field_name'];
$new_description = $_POST['new_description'];
$new_field_type_id = $_POST['new_field_type_id'];
$new_notes = $_POST['new_notes'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $new_name != '' && $new_field_name != '' && $custom_field->checkFieldFormat($new_field_name)) {

    $query = "SELECT field_name
              FROM ssl_cert_fields
              WHERE field_name = ?
              LIMIT 1";
    $q = $dbcon->stmt_init();

    if ($q->prepare($query)) {

        $q->bind_param('s', $new_field_name);
        $q->execute();
        $q->store_result();

        if ($q->num_rows() > 0) {

            $_SESSION['s_message_danger'] .= 'The Database Field Name you entered already exists<BR>';

        } else {

            $query_i = "INSERT INTO ssl_cert_fields
                        (`name`, field_name, description, type_id, notes, created_by, insert_time)
                        VALUES
                        (?, ?, ?, ?, ?, ?, ?)";
            $q_i = $dbcon->stmt_init();

            if ($q_i->prepare($query_i)) {

                $timestamp = $time->stamp();

                $q_i->bind_param('sssisis', $new_name, $new_field_name, $new_description, $new_field_type_id, $new_notes, $_SESSION['s_user_id'], $timestamp);
                $q_i->execute();
                $q_i->close();

            } else {
                $error->outputSqlError($dbcon, '1', 'ERROR');
            }

            if ($new_field_type_id == '1') { // Check Box

                $query = "ALTER TABLE `ssl_cert_field_data`
                          ADD `" . $new_field_name . "` INT(1) NOT NULL DEFAULT '0'";
                $q = $dbcon->stmt_init();

                if ($q->prepare($query)) {
                    $q->execute();
                    $q->close();

                } else {
                    $error->outputSqlError($dbcon, '1', 'ERROR');
                }

            } elseif ($new_field_type_id == '2') { // Text

                $query = "ALTER TABLE `ssl_cert_field_data`
                          ADD `" . $new_field_name . "` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT
                          NULL";
                $q = $dbcon->stmt_init();

                if ($q->prepare($query)) {

                    $q->execute();
                    $q->close();

                } else {

                    $error->outputSqlError($dbcon, '1', 'ERROR');
                }

            } elseif ($new_field_type_id == '3') { // Text Area

                $query = "ALTER TABLE `ssl_cert_field_data`
                          ADD `" . $new_field_name . "` LONGTEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL";
                $q = $dbcon->stmt_init();

                if ($q->prepare($query)) {

                    $q->execute();
                    $q->close();

                } else {
                    $error->outputSqlError($dbcon, '1', 'ERROR');
                }

            } elseif ($new_field_type_id == '4') { // Date

                $query = "ALTER TABLE `ssl_cert_field_data`
                          ADD `" . $new_field_name . "` DATE NOT NULL DEFAULT '1978-01-23'";
                $q = $dbcon->stmt_init();

                if ($q->prepare($query)) {

                    $q->execute();
                    $q->close();

                } else {
                    $error->outputSqlError($dbcon, '1', 'ERROR');
                }

            } elseif ($new_field_type_id == '5') { // Time Stamp

                $query = "ALTER TABLE `ssl_cert_field_data`
                          ADD `" . $new_field_name . "` DATETIME NOT NULL DEFAULT '1978-01-23 00:00:00'";
                $q = $dbcon->stmt_init();

                if ($q->prepare($query)) {

                    $q->execute();
                    $q->close();

                } else {
                    $error->outputSqlError($dbcon, '1', 'ERROR');
                }

            }

            $_SESSION['s_message_success'] .= 'Custom SSL Field ' . $new_name . ' (' . $new_field_name . ') Added<BR>';

            header("Location: ../ssl-fields/");
            exit;

        }

        $q->close();

    } else {
        $error->outputSqlError($dbcon, '1', 'ERROR');
    }

} else {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        if ($new_name == '') $_SESSION['s_message_danger'] .= 'Enter the Display Name<BR>';
        if (!$custom_field->checkFieldFormat($new_field_name)) $_SESSION['s_message_danger'] .= 'The Database Field Name format is incorrect<BR>';

    }

}
?>
<?php require_once(DIR_INC . '/doctype.inc.php'); ?>
<html>
<head>
    <title><?php echo $system->pageTitle($page_title); ?></title>
    <?php require_once(DIR_INC . '/layout/head-tags.inc.php'); ?>
</head>
<body class="hold-transition skin-red sidebar-mini">
<?php require_once(DIR_INC . '/layout/header.inc.php'); ?>
<?php
echo $form->showFormTop('');
echo $form->showInputText('new_name', 'Display Name (75)', '', $new_name, '75', '', '1', '', '');
echo $form->showInputText('new_field_name', 'Database Field Name (30)', 'The Database Field Name can contain only letters and underscores (ie. sample_field or SampleField).<BR><strong>WARNING:</strong> The Database Field Name cannot be renamed.', $new_field_name, '30', '', '1', '', '');
?>
<?php
$query = "SELECT id, `name`
          FROM custom_field_types
          ORDER BY `name` ASC";
$q = $dbcon->stmt_init();

if ($q->prepare($query)) {

    $q->execute();
    $q->store_result();
    $q->bind_result($id, $name);

    echo $form->showDropdownTop('new_field_type_id', 'Data Type', '<strong>WARNING:</strong> The Data Type cannot be changed.', '', '');

    while ($q->fetch()) {

        echo $form->showDropdownOption($id, $name, '');

    }

    echo $form->showDropdownBottom('');

    $q->close();

} else {
    $error->outputSqlError($dbcon, '1', 'ERROR');
}

echo $form->showInputText('new_description', 'Description (255)', '', $new_description, '255', '', '', '', '');
echo $form->showInputTextarea('new_notes', 'Notes', '', $new_notes, '', '', '');
echo $form->showSubmitButton('Add Custom Field', '', '');
echo $form->showFormBottom('');
?>
<?php require_once(DIR_INC . '/layout/footer.inc.php'); //@formatter:on ?>
</body>
</html>
