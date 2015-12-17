<?php

$tableNames = $_POST["tableNames"];

$dbname = "DBLP";
$servername = "localhost";

// Fill an associative array with count of each table. Used for self-joining.
$tableNamesCount = array();
foreach ($tableNames as $tableName) {
    if (array_key_exists($tableName, $tableNamesCount)) {    
        $cnt = $tableNamesCount[$tableName];
        $cnt += 1;
        $tableNamesCount[$tableName] = $cnt;
    }
    else {
        $tableNamesCount += array(
            $tableName => 1
        );
    }
}

// Fetch the columns for each table selected.
foreach ($tableNamesCount as $key => $value) {
    if ($value == 1) { // If the table has only been selected once.
        $query = "SELECT column_name FROM information_schema.columns WHERE table_schema = 'DBLP' AND table_name = '$key'";
        
        try {
            // Create connection.
            $conn = new mysqli($servername, $username, $password, $dbname);
            // Check connection.
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            
            $result = mysqli_query($conn, $query);
            if (!($result = mysqli_query($conn, $query))) {
                echo ("<p class = 'bg-warning'>Sorry! Could not process your query: $query</p>");
            }
            
            else {
                echo "<option>" . $key . ".* </option>";    
                $attrOptions = "";
                while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {    
                    echo "<option>" . $key . "." . $row['column_name'] . "</option>";
                }
            }
        }
        catch (mysqli_sql_exception $e) {
            throw new MySQLiQueryException($SQL, $e->getMessage(), $e->getCode());
        }
    }
    else { // The table has been selected more than once, so update the names (e.g., Article -> Article A1)
        for ($i = 1; $i <= $value; $i++) {
            $query = "SELECT column_name FROM information_schema.columns WHERE table_schema = 'DBLP' AND table_name = '$key'";
            
            $tableName = $key[0] . $i;
            try {
                // Create connection.
                $conn = new mysqli($servername, $username, $password, $dbname);
                // Check connection.
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
                
                $result = mysqli_query($conn, $query);
                if (!($result = mysqli_query($conn, $query))) {
                    echo ("<p class = 'bg-warning'>Sorry! Could not process your query: $query</p>");    
                } else {
                    echo "<option>" . $tableName . ".* </option>";
                    $attrOptions = "";
                    while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
                        echo "<option>" . $tableName . "." . $row['column_name'] . "</option>";
                    }
                }
            }
            catch (mysqli_sql_exception $e) {
                throw new MySQLiQueryException($SQL, $e->getMessage(), $e->getCode());
            }
        }
    }
}
// Close the connnection.
mysqli_close($conn);
?>
