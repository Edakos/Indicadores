<?php
@session_start();
require_once($_SESSION['path'].'/modules/errorHandler/launch.php');
require_once($_SESSION["path"].'/modules/ado/launch.php');
require_once($_SESSION['path'].'/modules/mailer/launch.php');
require_once($_SESSION['path'].'/modules/filesUploader/launch.php');
?>
