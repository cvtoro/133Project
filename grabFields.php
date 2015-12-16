<?php 

        $tableName = $_POST["tableName"];
    // $tableName = "Article";


           $query = "SELECT column_name FROM information_schema.columns WHERE table_schema = 'DBLP' AND table_name = 'Article' ";

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
              echo("<p class = 'bg-warning'>Sorry could not process your query " );

            }
     
            else{
                
                $attrOptions = "";
                while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {

                    
                    echo "<option>" .  $tableName . "." . $row['column_name'] . "</option>";

                    
                }

            }

        }
        catch (mysqli_sql_exception $e){
                throw new MySQLiQueryException($SQL, $e->getMessage(), $e->getCode());

        }



        //Close connection
        mysqli_close($conn);
?>
