<?php
include_once('config/config.php');
// Открытие сессии.
session_start();
ob_start();
$rout = new M_Rout($_GET);

$rout->Request();

flush();
ob_flush();
ob_end_clean();
