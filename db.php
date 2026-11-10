<?php
$conn = mysqli_connect ("localhost", "root", "", "event_db");

if (!$conn){
    die("connection failed: " . mysqli_connect_error()); 
}
?>