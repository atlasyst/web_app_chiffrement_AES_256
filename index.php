<?php require_once __DIR__.'/config.php';
if(!empty($_SESSION['logged'])) header('Location: encrypt.php');
else header('Location: login.php'); ?>