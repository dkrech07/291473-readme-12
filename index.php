<?php
require_once('helpers.php');
require_once('includes/functions.inc.php');
require_once('includes/db_connect.inc.php');

$errors = authenticate($con);

$layout_content = include_template('layout-unauth.php', ['errors' => $errors]);
echo($layout_content);
