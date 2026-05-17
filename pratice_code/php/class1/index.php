<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Class</title>
</head>

<body>
  <?php
  class Book
  {
    public function name()
    {
      echo "Drupal Book";
    }

    public function price()
    {
      echo "Price: $20";
    }
  }
  

  $obj = new Book();
  $obj->name();
  echo "<br>";
  $obj->price();
  echo "<br>";

  $obj2 = new Book();
  $obj2->name();
  echo "<br>";
  $obj2->price();

  ?>
</body>

</html>