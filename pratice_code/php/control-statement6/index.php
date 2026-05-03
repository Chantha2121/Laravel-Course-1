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
  $diceNumber = 4;
  if ($diceNumber == 1) {
    echo "The dice number is 1";
  } else if ($diceNumber % 2 == 0) {
    if ($diceNumber == 2) {
      echo "The dice number is 2";
    } else if ($diceNumber == 4) {
      echo "The dice number is 4";
    } else if ($diceNumber == 6) {
      echo "The dice number is 6";
    } else {
      echo "This number is be between 1 and 6.";
    }
  } else if ($diceNumber == 3) {
    echo "The dice number is 3";
  } else if ($diceNumber == 5) {
    echo "The dice number is 5";
  } else {
    echo "This number is be between 1 and 6.";
  }
  ?>
</body>

</html>