<?php
$host = 'localhost';
$user = 'root';
$pw = '';



$dbcon = "dblayer_board";


$_mysqli = new mysqli($host,$user,$pw,$dbcon);
if ($_mysqli->connect_errno){
    die('Connect Error: ' . $_mysqli->connect_errno);
}
