<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <?php
  # Declare a variable
  $day = "Monday";

  if ($day == "Monday") {
    echo "Laugh on Monday, Laugh for danger <br>";
  } else if ($day == "Tuesday") {
    echo "Laugh on Tuesday, kiss a stranger <br>";
  } else if ($day == "Wednesday") {
    echo "Laugh on Wednesday, laugh for a letter <br>";
  } else if ($day == "Thursday") {
    echo "Laugh on Thursday, something better <br>";
  } else if ($day == "Friday") {
    echo "Laugh on Friday, laugh for sorrow <br>";
  } else if ($day == "Saturday") {
    echo "Laugh on Saturday, joy tomorrow <br>";
  } else if ($day == "Sunday") {
    echo "Laugh on Sunday, laugh for a week <br>";
  } else {
    echo "No information in available this day.<br>";
  }
  ?>
</body>

</html>