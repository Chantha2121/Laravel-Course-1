<?php
interface Animal
{
  public function makeSound();
}

class Cat implements Animal
{
  public function makeSound()
  {
    echo "Meow <br>";
  }
}

class Dog implements Animal
{
  public function makeSound()
  {
    echo "Woof ";
  }
}

function animalSound(Animal $animal)
{
  $animal->makeSound();
}

animalSound(new Cat()); // Output: Meow
animalSound(new Dog()); // Output: Woof

// $cat = new Cat();
// $cat->makeSound(); // Output: Meow

// $dog = new Dog();
// $dog->makeSound(); // Output: Woof  