<?php
include 'db.php';

if (isset($_POST['submit'])) {

  global $con;
  $name = $_POST['name'];
  $email = $_POST['email'];

  $sql = "INSERT INTO users(name, email)
            VALUES('$name', '$email')";

  mysqli_query($con, $sql);

  header("Location: index.php");
}
?>

<form method="POST">

  <input type="text" name="name" placeholder="Name">
  <br><br>

  <input type="email" name="email" placeholder="Email">
  <br><br>

  <button type="submit" name="submit">Save</button>

</form>