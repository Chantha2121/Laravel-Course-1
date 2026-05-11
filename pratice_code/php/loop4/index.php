<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <?php
  $capitals = [
    'Japan' => 'Tokyo',
    'France' => 'Paris',
    'Germany' => 'Berlin',
    'United Kingdom' => 'London',
    'United States' => 'Washington, D.C.',
  ];


  foreach ($capitals as $country => $capital) {
    echo "The capital of " . $country . " is " . $capital . "<br>";
  }
  ?>
</body>

</html>