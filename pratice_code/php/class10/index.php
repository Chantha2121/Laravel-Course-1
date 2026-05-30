<?php


interface Flyable
{
  public function fly();
}

class Bird implements Flyable
{
  public function fly()
  {
    echo "The bird is flying. <br>";
  }
}

class Drone implements Flyable
{
  public function fly()
  {
    echo "The drone is flying. <br>";
  }
}

function makeItFly(Flyable $flyable)
{
  $flyable->fly();
}

makeItFly(new Bird()); // Output: The bird is flying.
makeItFly(new Drone()); // Output: The drone is flying.
