<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <?php
  function adding($num1, $num2, $num3)
  {
    $sum = $num1 * $num2 * $num3;
    echo "The sum of " . $num1 . " and " . $num2 . " and " . $num3 . " is: " . $sum;
  }

  adding(10, 20, 30);
  ?>
</body>

</html>