<?php

abstract class Shape
{
  abstract public function calculateArea(): float;
}

class Circle extends Shape
{
  public function __construct(private float $radius) {}

  public function calculateArea(): float
  {
    return pi() * pow($this->radius, 2);
  }
}

class Rectangle extends Shape
{
  public function __construct(
    protected float $width,
    protected float $height
  ) {}

  public function calculateArea(): float
  {
    return $this->width * $this->height;
  }
}

class Square extends Rectangle
{
  // A square is just a rectangle where width == height
  public function __construct(float $side)
  {
    parent::__construct($side, $side);
  }
}

// --- Usage Example ---
$shapes = [
  new Circle(5),       // Area ≈ 78.54
  new Rectangle(4, 5), // Area = 20
  new Square(4)        // Area = 16
];

foreach ($shapes as $shape) {
  // Polymorphism allows us to call calculateArea() blindly, regardless of the class type
  echo "Shape type: " . get_class($shape) . " | Area: " . round($shape->calculateArea(), 2) . "\n";
}
