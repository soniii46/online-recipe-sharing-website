<?php 
$host = 'localhost';
$uname = 'root';
$pwd = '';
$db = 'recipe';
$conn = mysqli_connect($host,$uname,$pwd,$db);
if(!$conn){
    echo "connection failed".mysqli_connect_error();
    die; //end the program
}
?>