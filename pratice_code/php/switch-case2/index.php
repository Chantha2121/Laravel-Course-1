<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <?php

  // Declare the Operator
  $operator = '+';

  // Declare the Variable
  $x = 100;
  $y = 50;

  switch ($operator) {
    case '+':
      echo "(X + Y) = " . $x + $y;
      break;
    case '-':
      echo "(X - Y) = " . $x - $y;
      break;
    case '*':
      echo "(X * Y) = " . $x * $y;
      break;
    case '/':
      echo "(X / Y) = " . $x / $y;
      break;
    default:
      echo "The Operator is Undefine";
  }
  ?>
</body>

</html>