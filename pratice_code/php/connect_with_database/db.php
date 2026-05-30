<?php

$conn = mysqli_connect("localhost", "root", "ServBay.dev", "crud_db");

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
