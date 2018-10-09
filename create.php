<!DOCTYPE HTML>
<html>
<head>
    <title>PDO - Create a Record - PHP CRUD Tutorial</title>

    <!-- Latest compiled and minified Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />

</head>
<body>

    <!-- container -->
    <div class="container">

        <div class="page-header">
            <h1>Create Product</h1>
        </div>
        <!-- PHP CODE -->
        <?php

            if($_POST){
              //include database connection
              include 'config/database.php';

              try {

                $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                //build query
                $query = "INSERT INTO products
                          SET name=:name, description=:description, price=:price, image=:image, created=:created";
                //use the pdo object to prepare query
                $stmt = $con->prepare($query);

                //accept and clean posted data
                $name = htmlspecialchars(strip_tags($_POST['name']));
                $description = htmlspecialchars(strip_tags($_POST['description']));
                $price = htmlspecialchars(strip_tags($_POST['price']));

                //new image field
                $image = !empty($_FILES["image"]["name"]) ? sha1_file($_FILES['image']['tmp_name']). "_".basename($_FILES['image']['name']): "";
                $image = htmlspecialchars(strip_tags($image));

                //bind parameters
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':price', $price);
                $stmt->bindParam(':image', $image);

                //get date and time of recording into dbase
                $created = date('Y-m-d H:i:s');
                $stmt->bindParam(':created', $created);

                //Execute the query
                    if($stmt->execute()){
                        echo "<div class='alert alert-success'>Record was saved.</div>";
                        if ($image) {
                          // sha1_file() is used to make a unique file name
                          $target_directory = "uploads/";
                          $target_file = $target_directory.$image;
                          $file_type = pathinfo($target_file, PATHINFO_EXTENSION);

                          //error message is empty
                          $file_upload_error_messages = "";

                          //make sure file is a real image
                          $check = getimagesize($_FILES['image']['tmp_name']);
                              if($check !== false){

                              }else {
                                $file_upload_error_messages.="<div>Submitted File is not an image</div>";
                                }

                        //check for file types
                        $allowed_file_types = array("jpg", "jpeg", "png", "gif");
                        if(!in_array($file_type, $allowed_file_types))
                          {
                            $file_upload_error_messages .= "<div>Only JPG, JPEG, PNG, GIF Files are allowed</div>";
                          }
                        //make sure file does not exist
                        if(file_exists($target_file)){
                          $file_upload_error_messages .= "<div>Image already Exist. try to change file name</div>";
                        }
                        //check file size
                        if ($_FILES['image']['size'] > (1024000)) {
                          $file_upload_error_messages .="<div>Image too large. use less than 1MB</div>";
                        }
                        //check upload folder
                        if (!is_dir($target_directory)) {
                            mkdir($target_directory, 0777, true);
                        }

                        // if $file_upload_error_messages is still empty
                        if(empty($file_upload_error_messages)){
                            // it means there are no errors, so try to upload the file
                              if(move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)){
                                // it means photo was uploaded
                              }else{
                                echo "<div class='alert alert-danger'>";
                                    echo "<div>Unable to upload photo.</div>";
                                    echo "<div>Update the record to upload photo.</div>";
                                echo "</div>";
                              }
                        }

                        // if $file_upload_error_messages is NOT empty
                        else{
                            // it means there are some errors, so show them to user
                            echo "<div class='alert alert-danger'>";
                                echo "<div>{$file_upload_error_messages}</div>";
                                echo "<div>Update the record to upload photo.</div>";
                            echo "</div>";
                        }

                  }
                    }
                    else
                    {
                        echo "<div class='alert alert-danger'>Unable to save record.</div>";
                    }
                  }
                catch(PDOException $e)
                {
                  die('ERROR: '. $e->getMessage());
                }

            }
         ?>
    <!-- html form to create product will be here -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
    <table class='table table-hover table-responsive table-bordered'>
        <tr>
            <td>Name</td>
            <td><input type='text' name='name' class='form-control' /></td>
        </tr>
        <tr>
            <td>Description</td>
            <td><textarea name='description' class='form-control'></textarea></td>
        </tr>
        <tr>
            <td>Price</td>
            <td><input type='text' name='price' class='form-control' /></td>
        </tr>
        <tr>
            <td>Photo</td>
            <td><input type="file" name="image" /></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <input type='submit' value='Save' class='btn btn-primary' />
                <a href='index.php' class='btn btn-danger'>Back to read products</a>
            </td>
        </tr>
    </table>
</form>

    </div> <!-- end .container -->

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

<!-- Latest compiled and minified Bootstrap JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</body>
</html>
