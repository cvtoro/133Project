<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>
    </title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" href="./css/main.css">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

    <style>
        h1 {
            margin-top: 5%;
        }
        
        body {
            text-align: center;
        }
    </style>

</head>

<body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="index.php">DBLP Explorer </a>
            </div>

        </div>
    </nav>
    <h1>Please select a year range for the data:</h1>
    <form method="post" action="visualization.php" class="form-inline">
        Start Year:
        <input name="beginYr" class="form-control"></input>
        End Year:
        <input name="endYr" class="form-control"></input>
        <button type="submit" class="btn btn-default">Submit</button>
    </form>

</body>

</html>