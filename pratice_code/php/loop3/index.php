<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <?php
  $brush_price = 5;
  echo "<table border='1' align='center'>";
  echo "<tr>
         <th>Quantity</th>
         <th>Price</th>
        </tr>";
  for ($counter = 10; $counter <= 100; $counter = $counter + 10) {
    echo "<tr>
            <td>" . $counter . "</td>
            <td>" . ($counter * $brush_price) . "</td>
          </tr>";
  }
  echo "</table>";
  ?>

  <?php

  for ($x = 20; $x >= 1; --$x) {
    echo "This number is" . $x . "<br>";
  }
  ?>
</body>

</html>