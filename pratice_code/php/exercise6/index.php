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
  $a = 1000;
  $b = 1200;
  $c = 1400;


  echo "<table border='1' cellpadding=6 cellspacing=0>";
  echo "<tr>
          <td colspan='2'><b>Monthly Salary Statement</b></td>
        </tr>";
  echo "<tr>
        <td><font color=blue >Salary of Mr. A is </font></td>
        <td><font color=blue>$ $a</font></td>
      </tr>";
  echo "<tr>
        <td><font color=#ff6347 >Salary of Mr. B is </font></td>
        <td><font color=red>$ $b</font></td>
      </tr>";
  echo "<tr>
        <td><font color=green >Salary of Mr. C is </font></td>
        <td><font color=green>$ $c</font></td>
      </tr>";
  echo "</table>";

  ?>
</body>

</html>