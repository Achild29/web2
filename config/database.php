<?php
$connect = mysqli_connect("database_web2", "database_web2_user", "database_web2_password", "database_web2");
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    // echo "Connected successfully";
}
