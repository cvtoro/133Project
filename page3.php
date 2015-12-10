
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

    
    echo $query;

    // $password = NULL;

    $dbname = "DBLP";
    $servername = "localhost";       
    $username = "Cecilia";         
    $password = "helloagain";

    try{
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 

        $result = mysqli_query($conn, $query);
        if (!($result = mysqli_query($conn, $query))){
          echo("<br>Sorry could not process your query <br> " . mysqli_error($conn));
        }

        else{
            $data = array();
            while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
                array_push($data, $row);
                // print_r($row);

                // echo '<br>';
            }
            echo json_encode($data); 
        }

    }
    catch (mysqli_sql_exception $e){
            throw new MySQLiQueryException($SQL, $e->getMessage(), $e->getCode());

    }

    //Close connection
    mysqli_close($conn);
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

        <link rel="stylesheet" type="text/css" href="./css/main.css">

        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
      <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

    </head>


    <body>


       <!--  <nav class="navbar navbar-inverse  navbar-fixed-top">
          <div class="container-fluid">
            <div class="navbar-header">
              <a class="navbar-brand" href="#">DBLP Modeling</a>
            </div>
            <div>

          </div>
        </nav>   --> 

  <!--       <div class="jumbotron text-center">
            <h1>DBLP Explorer</h1>
            <p>Submit query below.</p>
        </div> -->
<!--         <div class="container text-center">


            <br><br>
            <table id = 'table' class="table table-bordered" >
                <tr class = 'schema'>
                </tr>
            </table>

            <br><br>


            <form action = 'query.php'>

             <button type="submit" class="btn btn-default">Submit</button>
            </form>
        </div> -->
   

    </body>
</html>