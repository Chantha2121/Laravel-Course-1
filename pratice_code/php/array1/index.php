<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <?php
  $cars = array(23, 'Toyota');

  $cars[3] = 'Honda';
  $cars[1] = 'BMW';
  echo "I like " . $cars[0] . " and " . $cars[1] . " and " . $cars[3];
  ?>
</body>

</html>