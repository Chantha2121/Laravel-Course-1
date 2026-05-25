<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <form action="" method="post">

    <label for="id">ID:</label>
    <input type="text" id="id" name="id"><br><br>
    <label for="name">Name:</label>
    <input type="text" id="name" name="name"><br><br>
    <label for="qty">Quantity:</label>
    <input type="number" id="qty" name="qty"><br><br>
    <label for="price">Price:</label>
    <input type="number" id="price" name="price" step="0.01"><br><br>
    <input type="submit" value="Submit" name="btn-submit">
  </form>

  <?php
  if (isset($_POST['btn-submit'])) {
    class Person
    {
      public $id;
      public $name;
      public $qty;
      public $price;

      public function __construct($id, $name, $qty, $price)
      {
        $this->id = $id;
        $this->name = $name;
        $this->qty = $qty;
        $this->price = $price;
      }

      public function information_person()
      {
        echo "ID: " . $this->id . "<br>";
        echo "Name: " . $this->name . "<br>";
        echo "Quantity: " . $this->qty . "<br>";
        echo "Price: $" . $this->price . "<br>";
      }
      public function total_price()
      {
        $this->information_person();
      }
    }

    $person = new Person($_POST['id'], $_POST['name'], $_POST['qty'], $_POST['price']);
    $person->price = 10;
    $person->information_person();
  }
  ?>
</body>

</html>