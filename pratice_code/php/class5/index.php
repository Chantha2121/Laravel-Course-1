<?php


// Main Class or Parent Class
class Fruit
{
  public $name;
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
  public function message()
  {
    echo "I am a Apple!";
    $this->intro();
  }
}

class Mango extends Fruit
{

  public function message()
  {
    echo "I am a Mango!";
  }
}

// $apple = new Apple("Apple", "Red");
// $apple->intro();
// echo "<br>";
// $apple->message();

// $mango = new Apple("Mango", "Yellow");
// $mango->intro();
// echo "<br>";
// $mango->message();
