<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <?php
  $colors = ["red", "green", "blue", "yellow", "orange"];

  for ($i = 0; $i < count($colors); $i++) {
    echo "<div class='box' style='background-color: " . $colors[$i] . ";'>" . ($i + 1) . "</div>";
  }
  ?>
</body>

</html>