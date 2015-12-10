<?php $SELECT=  $_POST["SELECT"]; 
    $FROM = $_POST['FROM'];


    $query = "SELECT ";
    $index = 0;
    $len = sizeof($SELECT) -1;
    foreach ($SELECT as  $value) {
        if ($index < $len){
            $query = $query . $value. ', ' ;
        }
        else{
            $query = $query . $value;
        }
        $index++;


    }
    $len = sizeof($FROM) - 1;
    $index = 0;
    $query = $query . " FROM ";
    foreach ($FROM as $value) {
       
        if ($index < $len){
            $query = $query . $value. ', ' ;
        }
        else{
            $query = $query . $value;
        }
        $index++;

    }


  

//check if something was sent for WHERE
        if (isset($_POST["WHERE"]) && !empty($_POST["WHERE"])) {

            $WHERE = $_POST["WHERE"];
            if ($WHERE[0] != ""){
                $query = $query . " WHERE ";
                foreach ($WHERE as $value) {
               
                    $query = $query . $value;
                }
            }

        }

    
    // echo "<p id = 'Query'> Query submitted: ". $query."</p>";

    // $password = NULL;


?>




<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>DBLP Modeling</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" type="text/css" href="./css/queryPage.css">

        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
      <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

    </head>


    <body>


        <nav class="navbar navbar-inverse  navbar-fixed-top">
          <div class="container-fluid">
            <div class="navbar-header">
              <a class="navbar-brand" href="#">DBLP Modeling</a>
            </div>
            <div>

          </div>
        </nav>   



<!--         <div class="container">
          <h2>Basic Table</h2>
          <p>The .table class adds basic styling (light padding and only horizontal dividers) to a table:</p>            
          <table class="table">
            <thead>
              <tr>
                <th>Firstname</th>
                <th>Lastname</th>
                <th>Email</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>John</td>
                <td>Doe</td>
                <td>john@example.com</td>
              </tr>
              <tr>
                <td>Mary</td>
                <td>Moe</td>
                <td>mary@example.com</td>
              </tr>
              <tr>
                <td>July</td>
                <td>Dooley</td>
                <td>july@example.com</td>
              </tr>
            </tbody>
          </table>
        </div>
 -->

        <div class="container1">
         <?php 
         echo "<p class='bg-success'> Query submitted: ". $query."</p>";

        try{
            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);
            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            } 

            $result = mysqli_query($conn, $query);
            if (!($result = mysqli_query($conn, $query))){
              echo("<p class = 'bg-warning'>Sorry could not process your query </p>" . mysqli_error($conn));
            }
     
            else{
              $data = array();
              $fields = mysqli_fetch_fields ( $result );

              $length = count($fields);
              // echo gettype($fields);
              echo ("<div class='container'><h2>Results</h2>
                        
              <table class='table'>
                <thead>
                  <tr>");
              for ($i = 0; $i < $length; $i++) {
                    echo "<th>".  $fields[$i]->name . "</th>";
              }
 
    
            echo "</tr>
            </thead>
            <tbody>
            ";

       
 
          
                while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {

                    array_push($data, $row);
                    // print_r($row);


                    echo "<tr>";
                    for ($i = 0; $i < $length; $i++) {
                        echo ("<td>" . $row[$fields[$i]->name] . "</td>"); //make less specific to that one attribute

                    }
                    echo "</tr>";
                }


                echo "</tbody>
                    </table>
                    </div>";
                json_encode($data); 
            }

        }
        catch (mysqli_sql_exception $e){
                throw new MySQLiQueryException($SQL, $e->getMessage(), $e->getCode());

        }

        //Close connection
        mysqli_close($conn);
        ?>

        </div>
     



    </body>
</html>


