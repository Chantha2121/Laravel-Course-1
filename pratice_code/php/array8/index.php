<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <h1>Array Merge array_merge()</h1>

  <?php
  $arr1 = array("Ankit", "Ram", "Shyam");
  $arr2 = array(1, 2, 3, "Ankit");

  $arr3 = array("Unnao", "Lucknow", "Kanpur");

  $arr4 = array_merge($arr1, $arr2, $arr2);
  foreach ($arr4 as $value) {
    echo "Array is " . $value . "<br>";
  }
  ?>
</body>

</html>