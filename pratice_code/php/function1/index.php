<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <?php

  function addFunction($num1, $num2)
  {
    $sum = $num1 + $num2;
    return $sum;
  }

  $return_value = addFunction(10, 20);
  echo "The sum of 10 and 20 is: " . $return_value;
  ?>
</body>

</html>