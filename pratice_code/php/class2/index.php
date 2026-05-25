<?php
class Mobile
{
  public $price = 0.0;
  public $title = '';
  // public function __construct($price = 0.0, $title = '')
  // {
  //   $this->price = $price;
  //   $this->title = $title;
  // }

  public function setPrice($price)
  {
    $this->price = $price;
  }
  public function setTitle($title)
  {
    $this->title = $title;
  }
  public function getPrice()
  {
    echo "The price is: $" . $this->price;
  }
  public function getTitle()
  {
    echo "The title is: " . $this->title;
  }
}

$Samsung = new Mobile();
$Xiaomi = new Mobile();
$Iphone = new Mobile();

$Samsung->setTitle("Samsung Galaxy S21");
$Samsung->setPrice(799.99);
$Xiaomi->setTitle("Xiaomi Mi 11");
$Xiaomi->setPrice(699.99);
$Iphone->setTitle("iPhone 13");
$Iphone->setPrice(999.99);

// Display the details of each mobile
$Samsung->getTitle();
echo "<br>";
$Samsung->getPrice();
echo "<br><br>";
$Xiaomi->getTitle();
echo "<br>";
$Xiaomi->getPrice();
echo "<br><br>";
$Iphone->getTitle();
echo "<br>";
$Iphone->getPrice();
echo "<br><br>";
