<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>

<style>
  body {
    font-family: Arial, Helvetica, sans-serif;
    background-color: white;
  }

  * {
    box-sizing: border-box;
  }

  .container {
    padding: 16px;
    background-color: white;
  }

  input[type=email],
  input[type=password] {
    width: 100%;
    padding: 15px;
    margin: 5px 0 22px 0;
    display: inline-block;
    border: none;
    background: #f1f1f1;
  }

  input[type=email]:focus,
  input[type=password]:focus {
    background-color: #ddd;
    outline: none;
  }

  hr {
    border: 1px solid #f1f1f1;
    margin-bottom: 25px;
  }

  .registerbtn {
    background-color: #4CAF50;
    color: white;
    padding: 16px 20px;
    margin: 8px 0;
    border: none;
    cursor: pointer;
    width: 100%;
    opacity: 0.9;
  }
</style>

<body>
  <form action="" method="POST">
    <div class="container">
      <h1>Register Form</h1>
      <p>Please fill in this form to create an account.</p>
      <hr>

      <label for="email"><b>Email</b> </label>
      <input type="email" name="email[]" id="email" placeholder="Enter the Email" required>

      <label for="psw"><b>Password</b> </label>
      <input type="password" name="psw[]" id="psw" placeholder="Enter the Password" required>

      <label for="psw-repeat"><b>Repeat Password</b> </label>
      <input type="password" name="psw-repeat[]" id="psw-repeat" placeholder="Repeat the Password" required>
      <hr>

      <p>By creating an account you agree to our <a href="#">Terms & Privacy</a>.</p>

      <button type="submit" id="registerbtn" class="registerbtn" name="register">Register</button>
    </div>

    <div class="container signin">
      <p>Already have an account? <a href="#">Sign in</a>.</p>
    </div>
  </form>
</body>

</html>

<?php
if (isset($_POST['register'])) {
  $email = $_POST['email'];
  $password = $_POST['psw'];
  $repeat_password = $_POST['psw-repeat'];
  print_r($email);
  print_r($password);
  print_r($repeat_password);

  echo "<div class='container mt-2'><table class='table table-bordered'><tr><th>Email</th><th>Password</th><th>Repeat Password</th></tr>";
  foreach ($email as $key => $value) {
    echo "<tr><td>" . $value . "</td><td>" . $password[$key] . "</td><td>" . $repeat_password[$key] . "</td></tr>";
  }
  echo "</table></div>";
}
?>