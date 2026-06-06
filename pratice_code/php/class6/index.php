<?php


// Main Class or Parent Class
class Fruit
{
  protected $name;
  public $color;

  public function __construct($name, $color)
  {
    $this->name = $name;
    $this->color = $color;
  }

  protected function intro()
  {
    echo "The fruit is {$this->name} and the color is {$this->color}.";
  }
}

// Sub Class or Child Class
class Apple extends Fruit
{
  public $weight;
  public function __construct($name, $color, $weight)
  {
    $this->name = $name;
    $this->color = $color;
    $this->weight = $weight;
  }

  public function intro()
  {
    parent::$name = "Apple";
    echo "The fruit is {$this->name}, the color is {$this->color} and the weight is {$this->weight} grams.";
  }
  public function message()
  {
    echo "I am a Apple!";
  }
}


$apple = new Apple("Apple", "Red", 100);
$apple->intro();
echo "<br>";
$apple->message();
