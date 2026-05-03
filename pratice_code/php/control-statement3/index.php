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
  $score = 89;

  if ($score >= 85 && $score <= 100)
    echo "Excellent ...!";
  else if ($score >= 70 && $score < 85)
    echo "Very Good ....!";
  else if ($score >= 50 && $score < 70)
    echo "Fairy...!";
  else
    echo "Fail...!";
  ?>
</body>

</html>