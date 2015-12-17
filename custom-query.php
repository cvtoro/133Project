<!DOCTYPE html>
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
        <p>Submit query below.</p>
    </div>

    <!-- Start of the SQL query dropdown form -->
    <div class='queryform'>

        <!-- Dropdowns for SELECT clause -->
        <span class='pquery'><p >SELECT</p> </span>
        <div class='SELECT'></div>

        <!-- Plus sign for additional SELECT clauses -->
        <img onclick='addDropdown()' ; src="./images/plus_16.png">

        <br>
        <br>

        <!-- Droppdowns for FROM clause-->
        <span class='pquery'><p>FROM </p></span>
        <div class='fromSelect'>
            <select id="fromSelect0" name='FROM[]' onchange="changeAttributes(this.value)" ; class="form-control" form='entityForm'>
                <option></option>
                <option>Article</option>
                <option>Inproceedings</option>
                <option>Proceedings</option>
                <option>Book</option>
                <option>Incollection</option>
                <option>PhDThesis</option>
                <option>MastersThesis</option>
                <option>Writes</option>
                <option>Edits</option>
                <option>Www</option>
                <option>Person</option>
            </select>


        </div>

        <!-- Plus sign for additional FROM clauses -->
        <img onclick='fromJoin()' ; id='fromPlus' src="./images/plus_16.png">
        <br>
        <br>


        <!-- Dropdowns for WHERE clause -->
        <span class='pquery'><p>WHERE</p></span>
        <div class="WHERE">
        </div>

        <!-- Plus sign for additional WHERE clauses -->
        <img onclick='addWhereDropdown()' ; src="./images/plus_16.png">
    </div>

    <br>
    <br>

    <!-- End of the SQL query dropdown form -->

    <!-- End of form. Submit button sends query and loads results page. -->
    <div class="container text-center">
        <form id='entityForm' action='query-results.php' method='post' onsubmit="return validateForm()">
            <button type="submit" class="btn btn-default">Submit</button>
        </form>
    </div>

    <!-- Updates table names, attributes, and submits query to PHP script. -->
    <script>
        fromSelId = 1;

        // Adds dropdown for FROM clause 
        function fromJoin() {
            $(".fromSelect").append("<select name = 'FROM[]' id ='fromSelect" + fromSelId + "' class = 'form-control' form = 'entityForm' onchange = 'changeAttributes(this.value)'> <option></option><option>Article</option><option>Inproceedings</option><option>Proceedings</option><option>Book</option><option>Incollection</option><option>PhdThesis</option><option>MastersThesis</option><option>Writes</option><option>Edits</option><option>Www</option><option>Person</option> </select>");
            fromSelId++;
        }

        attrOptions = ""; // Attribute options according to the selected tables

        // Updates SELECT and WHERE attribute.
        function changeAttributes(tableName) {
            tables = [];
            jnCount = 0;

            // Loop through each FROM dropdown.
            $(".fromSelect").children('select').each(function() {

                var sel = $(this).find(':selected');
                var tableName = $(this).find(':selected').val();

                if (tables.indexOf(tableName) > -1) {
                    tables.push(tableName); 
                    // Loop through FROM clause again
                    $(".fromSelect").children('select').each(function() {
                        var sel1 = $(this).find(':selected');
                        var t = sel1.text();
                        if (sel1.text().split(" ")[0] == tableName) {
                            jnCount++;
                            sel1.text(tableName + " " + tableName[0] + jnCount);
                        }
                    });
                } else {
                    tables.push(tableName);
                }
            });

            // Get the attributes for each table from the SQL database and update
            // the dropdowns accordingly.
            $.ajax({
                type: 'POST',
                url: "grabFields.php",
                data: {
                    tableNames: tables
                },
                success: function(data) {
                    attrOptions = data;
                    changeDropdowns();
                }
            });
        }

        // Return if a value is in an array.
        function isInArray(value, array) {
            return array.indexOf(value) > -1;
        }

        id = 0

        // Adds a SELECT clause dropdown.
        function addDropdown() {
            $(".SELECT").append("<select id = 'SELECT" + id + "'name = 'SELECT[]' class = 'form-control' form = 'entityForm'><option></option>" + attrOptions + "</select>");
            id += 1;
        }

        // Updates the SELECT sand WHERE dropdown values.
        function changeDropdowns() {
            $('.SELECT').children('select').each(function() {
                $("#" + this.id).empty();
                $("#" + this.id).append(attrOptions);
            });

            $('.WHERE').children('select').each(function() {
                $("#" + this.id).empty();
                $("#" + this.id).append(attrOptions);
            });
        }

        idW = 0;

        // Adds a WHERE clause dropdown.
        function addWhereDropdown() {
            $(".WHERE").append("<select id = 'WHERE" + idW + "'name = 'WHERE[]' class = 'form-control' form = 'entityForm'><option></option>" + attrOptions + "</select><br>");
            $(".WHERE").append("<select name = 'WHERE[]' class = 'form-control' form = 'entityForm'><option></option> <option> > </option> <option> < </option> <option> = </option> <option>!=</option>  <option>>=</option>  <option><=</option> <option>LIKE</option></select><br>");
            idW += 1;
            $(".WHERE").append("<select onchange = 'checkForConstant(this.value, this.id)'; id = 'WHERE2" + idW + "' name = 'WHERE[]' class = 'form-control' form = 'entityForm'> <option></option><option>CONSTANT</option>" + attrOptions + "</select><br>");
            $(".WHERE").append("<select name = 'WHERE[]' class = 'form-control' form = 'entityForm' ><option></option> <option>AND</option><option>OR</option></select><br>");
            idW += 1;
        }

        // Checks if the user has input a constant in the WHERE clause.
        function checkForConstant(selection, id) {
            if (selection == "CONSTANT") {
                $("#" + id).replaceWith("<input name = 'WHERE[]' form = 'entityForm' ></input>");
            }
        }

        // Checks that FROM field and SELECT field are non-empty.
        function validateForm() {
            // Check if empty of not.
            if ($("#fromSelect").val() === "") {
                // Alert the user if they did not enter a table.
                alert('A table was not selected in FROM');
                return false;
            }

            if (!(document.getElementById("SELECT0")) || $("#SELECT0").val() === "") {
                alert("No field chosen in SELECT");
                return false;
            }
        }
    </script>
</body>
</html>