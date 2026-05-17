<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <h1>Using with sort() Function</h1>
  <?php
  $s_array = ["Bayon", "Angkor Wat", "Ta Prohm", "Banteay Srei"];
  foreach ($s_array as $value) {
    echo "$value <br>";
  }

  # Implement sort() function
  sort($s_array);
  echo "<br>After sorting: <br>";
  foreach ($s_array as $value) {
    echo "$value <br>";
  }
  ?>
</body>

</html>