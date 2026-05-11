<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <?php
  $arr = array();
  $arr[0][0] = "Sok";
  $arr[0][1] = "Vanna";
  $arr[1][0] = "KiKi";
  $arr[1][1] = "Pisey";

  foreach ($arr as $e1) {
    foreach ($e1 as $e2) {
      echo $e2 . "<br>";
    }
  }
  echo $arr[0][1] . "<br>";
  ?>
</body>

</html>