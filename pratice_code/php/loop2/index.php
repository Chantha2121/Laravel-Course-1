<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <?php
  // 20,19,18,...,1

  for ($i = 20; $i >= 1; $i--) {
    echo "The number is " . $i . "<br>";
  }
  echo " // 20,18,16,...,2<br><br>";
  // 20,18,16,...,2
  for ($i = 20; $i >= 1; $i = $i - 2) {
    echo "The number is " . $i . "<br>";
  }

  // 20,17,14,...,2
  echo " // 20,17,14,...,2<br><br>";

  for ($i = 20; $i >= 1; $i = $i - 3) {
    echo "The number is " . $i . "<br>";
  }
  ?>

</body>

</html>