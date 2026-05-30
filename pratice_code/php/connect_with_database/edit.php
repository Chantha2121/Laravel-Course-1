<?php
require_once __DIR__ . '/db.php';

if (!isset($conn) || !$conn) {
  die('Database connection not found.');
}

$id = $_GET['id'];

$data = mysqli_query($conn, "SELECT * FROM users WHERE id=$id");
$row = mysqli_fetch_assoc($data);

if (isset($_POST['update'])) {

  $name = $_POST['name'];
  $email = $_POST['email'];

  mysqli_query(
    $conn,
    "UPDATE users
         SET name='$name', email='$email'
         WHERE id=$id"
  );

  header("Location: index.php");
}
?>

<form method="POST">

  <input type="text"
    name="name"
    value="<?= $row['name']; ?>">

  <br><br>

  <input type="email"
    name="email"
    value="<?= $row['email']; ?>">

  <br><br>

  <button type="submit" name="update">
    Update
  </button>

</form>