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
    protected $number = 10;

    public function __construct()
    {
      $this->number = 5;
    }
    public function name()
    {
      echo "The number of book is" . $this->number . "<br>";
    }

    public function price()
    {
      echo "Price: $20";
    }
  }

  class Magazine extends Book
  {
    public function __construct()
    {
      $this->number = 5;
    }

    public function getnumber()
    {
      echo "The number of magazine is" . $this->number . "<br>";
    }
  }


  // $book = new Book();
  // $book->number = 15;
  // $book->name();

  // $magazine = new Magazine();
  // $magazine->number = 5;
  // $magazine->number2 = 10;
  // $magazine->name();

  $book = new Book();
  $book->name();

  $magazine = new Magazine();
  $magazine->name();

  ?>
</body>

</html>