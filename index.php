<?php
session_start();
session_regenerate_id();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
};
header("Location: main.php");
exit;