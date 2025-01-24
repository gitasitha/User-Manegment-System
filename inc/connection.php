<?php
 
$dbhost="localhost";
$dbuser="root";
$dbpass="";
$dbname="userdb";

$connection = mysqli_connect("localhost","root","","userdb");//databse connection link
//cheack connecton error
if (mysqli_connect_errno()) {
    die ("Databse connection error" . mysqli_connect_error());
}else {
    // echo"connection successful";
}
?>