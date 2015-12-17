<!DOCTYPE html>
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

    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="index.php">DBLP Explorer</a>
            </div>

        </div>
    </nav>

    <div class="jumbotron text-center">
        <h1>DBLP Explorer</h1>
        <p>Hi! Welcome to the DBLP Explorer. Please select one of two options below.</p>
    </div>

    <div class="container text-center">
        <h5>Know some SQL? Make your own custom SQL query against our DBLP database.</h5>
        <a class="btn btn-default" href="custom-query.php">Custom Query </a>
        <br>
        <br>
        <h5>Interested in a more visual experience? View a templated visualization of the number of publications per journal per year in DBLP.</h5>
        <a class="btn btn-default" href="visualization-query.php">Query Visualization</a>
    </div>

</body>
</html>