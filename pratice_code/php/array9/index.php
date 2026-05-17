<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <?php
  $arr1 = array("Ankit", "Ram", "Shyam");
  $total = array_push($arr1, "Sita", "Gita", "Gita");
  echo "Total is " . $total . "<br>";
  foreach ($arr1 as $value) {
    echo "Array is " . $value . "<br>";
  }
  ?>
</body>

</html>