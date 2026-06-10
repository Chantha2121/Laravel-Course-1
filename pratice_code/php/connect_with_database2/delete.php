<?php
include 'db.php';


// Ensure db.php sets $conn
if (!isset($con)) {
  die('Database connection not established.');
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = mysqli_query($con, "DELETE FROM users WHERE id=$id");

header("Location: index.php");
