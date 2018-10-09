<?php
  #used to connect to the databse
  $host = "localhost";
  $db_name = "mydatabase";
  $username = "root";
  $password = "";

  try {
      $con = new PDO("mysql:host={$host};dbname={$db_name}", $username, $password);
  } catch (PDOException $e) {
    echo "Connection error: ". $e->getMessage();
  }

 ?>
