<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <h1>sorting an associate array by value with asort() function</h1>
  <?php
  $first = array("CocaCola" => 3, "Pepsi" => 2, "Fanta" => 1);
  echo "<b>an Associate Array before sorting:</b><br>";

  foreach ($first as $key => $value) {
    echo "$key => $value <br>";
  }

  ksort($first);
  echo "<br><b>an Associate Array after sorting:</b><br>";
  foreach ($first as $key => $value) {
    echo "$key => $value <br>";
  }
  ?>
</body>

</html>