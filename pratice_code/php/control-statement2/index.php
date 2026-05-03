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
  $overtime = 60;
  if ($overtime <= 50) {
    $pay_amt = 1200;
    $medical = 1000;
    echo "<h1><font color=blue> Payment Amount: $ $pay_amt and Medical: $ $medical</font></h1>";
  } else {
    $pay_amt = 2000;
    $medical = 1500;
    echo "<h1><font color=blue> Payment Amount: $ $pay_amt and Medical: $ $medical</font></h1>";
  }
  ?>
</body>

</html>