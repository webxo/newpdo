<?php
//include database configuration
include 'config/database.php';

try {
  //get record ID
  //isset() is a function used to verify if a variable exist
  $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found');

  $query = "DELETE FROM products
            WHERE id = ?";
  $stmt = $con->prepare($query);
  $stmt -> bindParam(1, $id);

  if ($stmt->execute()) {
    //redirect to read records
    //tell the user record was deleted
    header('Location:index.php?action=deleted');
  } else {
    die('Unable to delete records');
  }

} catch (PDOException $e) {
  die('ERROR: '. $e.getMessage());
}

?>
