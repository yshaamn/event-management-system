<?php
include "db.php";
$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM booking_tb WHERE id=$id");
header("Location: index.php");
?>