<?php
class FileHaddler
{
  private $filehaddle;
  private $filename;

  public function __construct($filename)
  {
    $this->filename = $filename;
    echo "Constructing the object with filename: " . $this->filename . "<br>";

    $this->filehaddle = true;
  }

  public function writeData($data)
  {
    if ($this->filehaddle) {
      echo "Writing data to the file: " . $this->filename . "<br>";
      echo "Data: " . $data . "<br>";
    }
  }

  public function __destruct()
  {
    echo "Destructing the object and closing the file: " . $this->filename . "<br>";
    $this->filehaddle = false;
  }
}


echo "--- Script Start ---<br>";
$fileHandler = new FileHaddler("example.txt");
$fileHandler->writeData('Hello, World!');
$fileHandler->writeData('Testing destructor in PHP.');

echo "--- Script End ---<br>";
