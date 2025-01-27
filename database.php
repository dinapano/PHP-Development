<?php
#defining server and database credentials
$servername="localhost";
$username="root";
$password="";
$dbname="info_db";

#connection creation with the mysql database
$conn=new mysqli($servername, $username, $password, $dbname);

#checking connection and handling any error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
   echo "Database connection has been set!";
}

?>