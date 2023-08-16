<?php
$servername='localhost';
$dbname='chat';
$username='root';
$password='';
$pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$connectType='mysql'; //use mysql || json