<!DOCTYPE HTML>
<html>
<head>
    <title>PDO - Read Records - PHP CRUD Tutorial</title>

    <!-- Latest compiled and minified Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />

    <!-- custom css -->
    <style>
    .m-r-1em{ margin- :1em; }
    .m-b-1em{ margin-bottom:1em; }
    .m-l-1em{ margin-left:1em; }
    .mt0{ margin-top:0; }
    </style>

</head>
<body>

    <!-- container -->
    <div class="container">

        <div class="page-header">
            <h1>Read Products</h1>
        </div>

        <!-- PHP code to read records will be here -->
        <?php
            //include database connections
            include 'config/database.php';
            //PAGINATION VARIABLES
            //PAGE is the current page, if there is nothing set, default is page 1
            $page = isset($_GET['page']) ? $_GET['page'] : 1;//means page will be set to 1 as default is there is nothing in get.

            //set records or rows of data per page
            $records_per_page = 5;

            //calculate for the query limit clause
            $from_record_num = ($records_per_page * $page) - $records_per_page;

            $action = isset($_GET['action']) ? $_GET['action'] : "";
            //if it was redirected from delete.php
            if($action == 'deleted')
            {
              echo "<div class='alert alert-success'>Record was deleted.</div>";
            }

            $query = "SELECT id, name, description, price
                      FROM products
                      ORDER BY id DESC
                      LIMIT :from_record_num, :records_per_page";

            $stmt = $con->prepare($query);
            $stmt -> bindParam(':from_record_num', $from_record_num, PDO::PARAM_INT);
            $stmt -> bindParam(':records_per_page', $records_per_page, PDO::PARAM_INT);
            $stmt->execute();

            //get number of rows returned
            $num = $stmt->rowCount();

            //Link to create new records
            echo "<a href='create.php' class='btn btn-primary m-b-1em'>Create New Products</a>";

            //check if more than 0 record found
            if($num > 0)
            {
              echo "<table class='table table-hover table-responsive table-bordered'>";
              //start table

    //creating our table heading
                    echo "<tr>";
                        echo "<th>ID</th>";
                        echo "<th>Name</th>";
                        echo "<th>Description</th>";
                        echo "<th>Price</th>";
                        echo "<th>Action</th>";
                    echo "</tr>";

                // table body will be here
             //retrieve table contents
             //use fetch()
             while($row=$stmt->fetch(PDO::FETCH_ASSOC))
             {
               //extract row this truns array keys into variables
               extract($row);
               //create new row per record
               echo "<tr>";
                  echo "<td>{$id}</td>";
                  echo "<td>{$name}</td>";
                  echo "<td>{$description}</td>";
                  echo "<td>&#36;{$price}</td>";
                  echo "<td>";
                      //view a single record
                      echo "<a href='read_one.php?id={$id}' class='btn btn-info m-r-1em'>Read</a>";
                      //link to update record
                      echo "<a href='update.php?id={$id}' class='btn btn-primary m-r-1em'>Edit</a>";
                      //link to delete
                      // we will use this links on next part of this post
                      echo "<a href='#' onclick='delete_user({$id});' class='btn btn-danger'>Delete</a>";
                  echo "</td>";
             }
            // end table
                  echo "</table>";

                  //PAGINATION code
                  //count total number of rows

                  $query = "SELECT COUNT(*)
                            AS total_rows
                            FROM products";
                  $stmt = $con -> prepare($query);
                  $stmt->execute();

                  //get total rows
                  $row = $stmt->fetch(PDO::FETCH_ASSOC);
                  $total_rows = $row['total_rows'];

                  //paginate records
                  $page_url = "index.php?";
                  include_once "paging.php";
            } else {
              echo "<div class='alert alert-danger'>No records found.</div>";
            }
        ?>
    </div> <!-- end .container -->

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

<!-- Latest compiled and minified Bootstrap JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<!-- confirm delete record will be here -->
<script type="text/javascript">
//confirm record deletion
  function delete_user( id ){

    var answer = confirm('Are you sure?');
    if(answer){
      //if the user clicked ok, pass the id to delete.php
      //for excecution
      window.location = 'delete.php?id=' +id;
    }
  }

</script>

</body>
</html>
