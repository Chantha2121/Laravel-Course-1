<?php
include 'db.php';


// Ensure db.php sets $conn
if (!isset($conn)) {
  die('Database connection not established.');
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = mysqli_prepare($conn, "DELETE FROM users WHERE id = ?");
if ($stmt) {
  mysqli_stmt_bind_param($stmt, 'i', $id);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);
}

header("Location: index.php");
