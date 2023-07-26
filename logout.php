<?php
session_start();

$_SESSION['authorization'] = '';

header('Location: login.php');