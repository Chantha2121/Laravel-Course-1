<?php

$con = mysqli_connect("localhost", "root", "ServBay.dev", 'test_db');
if (!$con) {
  die("Connection failed: " . mysqli_connect_error());
}
