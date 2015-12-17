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
                    $query = $query . $value . " ";
                }
            }

        }



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
              <a class="navbar-brand" href="index.html">DBLP Explorer</a>
            </div>
            <div>

          </div>
        </nav>



        <div class="container1">
         <?php
         echo "<p class='bg-success'> Query submitted: ". $query."</p>";

        $dbname = "DBLP";
        $servername = "localhost";
  

        try{
            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);
            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $result = mysqli_query($conn, $query);
            if (!($result = mysqli_query($conn, $query))){
              echo("<p class = 'bg-warning'>Sorry could not process your query <br> Redirecting..</p>" );
                //redirect

                header( "refresh:5;url=page2.php" );
            }

            else{
                  $data = array();
                  $fields = mysqli_fetch_fields ( $result );

                  $length = count($fields); 
                  echo $length;

                  $numResults = mysqli_num_rows ($result);

                  echo "<p class='bg-success'>" . $numResults . " results. Displaying up to 500. </p>";
                  // echo gettype($fields);
                  echo ("<div class='container'><h2>Results</h2>
                        <table class='table'>
                    <thead>
                      <tr>");
                  for ($i = 0; $i < $length; $i++) {
                        $name = $fields[$i]->name;
                        echo "<th>".  $name . "</th>";

                  }


                echo "</tr>
                </thead>
                <tbody>
                ";



                $cnt = 0;
                while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
                    if($cnt > 499){
                        break;
                    }

                    array_push($data, $row);



                    echo "<tr>";
                    //loop through fields
                    for ($i = 0; $i < $length; $i++) {


                        echo ("<td>" . $row[$fields[$i]->name] . "</td>");


                    }
                    echo "</tr>";

                    $cnt++;
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
