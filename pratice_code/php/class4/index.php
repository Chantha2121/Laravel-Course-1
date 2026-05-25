<?php

// Main Class or Parent Class
class Test
{
  public function first()
  {
    echo "Hello this Main Class";
  }
}

// Sub Class or Child Class
class Sample extends Test
{
  public function second()
  {
    echo "Hello this is Sub Class";
  }
}

$test = new Sample();
$test->first();
echo "<br>";
$test->second();
