<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <?php
  $arr = array(
    array(1, "Ankit", 20000),
    array(2, "Ram", 30000),
    array(3, "Shyam", 40000),
    array(4, "Sita", 50000),
    array(4, "Sita", 50000),
  );

  for ($row = 0; $row < count($arr); $row++) {
    for ($col = 0; $col < count($arr[$row]); $col++) {
      echo "Array is " . $arr[$row][$col] . "<br>";
    }
  }
  ?>
</body>

</html>