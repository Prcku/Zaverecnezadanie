<?php

use App\Controller\PersonController;

include '../app/vendor/autoload.php';

$personController = new PersonController();
$people = $personController->getAllPeople();
?>

<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title>The HTML5 Herald</title>
  <meta name="description" content="The HTML5 Herald">
  <meta name="author" content="SitePoint">
    <script src="./js/script.js"></script>

  <style>
      table, tr, td {
          border: 1px solid black;
          border-collapse: collapse;
      }
  </style>

</head>

<body>
    <h1>OH</h1>

    <ul>
        <?php
            foreach ($people as $person) {
                echo "<li>".$person->getFullName()." <a href='editPerson.php?id=".$person->getId()."'>edit</a></li>";
            }
        ?>
    </ul>

</body>
</html>
