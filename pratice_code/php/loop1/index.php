<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>

  <?php
  $x = 2;


  // 1,2,3,...,20
  $i = 1;
  for ($i; $i <= 20; $i++) {
    echo "The number is " . $i . "<br>";
  }

  ?>

  <br>

  <p>number start 2,4,6,...,20</p>

  <?php

  // 2,4,6,...,20
  for ($i = 2; $i <= 20; $i = $i + 2) {
    echo "The number is " . $i . "<br>";
  }
  ?>


  <p>number start 2,3,5,...,20</p>

  <?php

  // 2,4,6,...,20
  for ($i = 2; $i <= 20; $i = $i + 3) {
    echo "The number is " . $i . "<br>";
  }
  ?>
</body>

</html>