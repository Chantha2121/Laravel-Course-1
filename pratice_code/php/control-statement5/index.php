<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <?php
  # Declare a variable
  $i = 10;

  if ($i == 10) {
    # first if statement
    if ($i < 15)
      echo "i is smaller than 15 <br>";

    # nested if statement
    if ($i < 12)
      echo "i is smaller than 12 too <br>";
    else
      echo "i is greater than 15";
  }
  ?>
</body>

</html>