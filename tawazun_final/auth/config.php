<?php
$hostname="localhost";
$con=mysqli_connect($hostname,"root","","tawazun_final_db");

if(!$con){
    die("Connection failed". mysqli_connect_errno());
}
?>