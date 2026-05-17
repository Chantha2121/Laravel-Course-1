<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <?php
  $first = array("a", "b", "c", "d", "e", "f");
  $second = array_slice($first, 2);
  print_r($second);
  ?>
</body>

</html>