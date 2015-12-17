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
              <a class="navbar-brand" href="index.html">DBLP Explorer</a>
            </div>

          </div>
        </nav>

        <div class="jumbotron text-center">
            <h1>DBLP Explorer</h1>
            <p>Submit query below.</p>


        </div>

        <!-- THE SQL UI -->



        <!-- this is a class for styling the UI in main.css..all styling goes there -->
        <div class = 'queryform'>

          <!-- SELECT , all dropwdown for select go here-->
          <!-- the <span> is just to help with styling -->
        <span class = 'pquery'><p >SELECT</p> </span>
                <img
        <!-- all dropdown of SELECT will be appended to this div  -->
        <div class = 'SELECT'></div>
        <img onclick = 'addDropdown()';  src="./images/add182.png">

        <br> <br>
          <!-- FROM , all dropdown for from go here-->
        <span class = 'pquery'><p>FROM </p></span>
                      <div class = 'fromSelect'>
                          <select  id = "fromSelect0" name = 'FROM[]' onchange = "changeAttributes(this.value)"; class = "form-control" form = 'entityForm'>
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

                    <!-- the plus sign, calls a function defined below -->
                    <img onclick = 'fromJoin()'; id= 'fromPlus' src="./images/add182.png">
                 <br> <br>


        <!-- WHERE , add dropdowns here-->

        <span class = 'pquery'><p>WHERE</p></span>
         <div class = "WHERE" >








         </div>
         <img onclick = 'addWhereDropdown()';  src="./images/add182.png">


        </div>

        <br><br>

        <!-- END of SQL UI -->

      <!-- FORM, sends the dropdown user selects to page3.php -->
      <div class="container text-center">
         <form  id = 'entityForm' action ='page3.php' method = 'post' onsubmit="return validateForm()" >
             <button type="submit" class="btn btn-default">Submit</button>
        </form>


        </div>
        <script>
        fromSelId = 1;
        function fromJoin(){


          $(".fromSelect").append("<select name = 'FROM[]' id ='fromSelect"+ fromSelId +"' class = 'form-control' form = 'entityForm' onchange = 'changeAttributes(this.value)'> <option></option><option>Article</option><option>Inproceedings</option><option>Proceedings</option><option>Book</option><option>Incollection</option><option>PhdThesis</option><option>MastersThesis</option><option>Writes</option><option>Edits</option><option>Www</option><option>Person</option> </select>");
          fromSelId++;

        }
        attrOptions = ""; //all attribute options according to the selected tables


        function changeAttributes(tableName){
            tables = [];
           
            jnCount = 0;



           //loop through each FROM dropdown, get the tableName in array tables[]
            $(".fromSelect").children('select').each(function(){

              var sel = $(this).find(':selected');
               var tableName = $(this).find(':selected').val();
               console.log(tableName);
               //trying to add a duplicated table
                if (tables.indexOf(tableName) > -1){

                  tables.push(tableName); //push first
                  //loop through from again
                   $(".fromSelect").children('select').each(function(){

                      var sel1 = $(this).find(':selected');
                      var t = sel1.text();
                      // console.log(sel1.text().split(" ")[0]);
                      if (sel1.text().split(" ")[0] == tableName){
                        jnCount++;
                        sel1.text(tableName +" "+tableName[0]+jnCount);
                      }

                   });



                }
                else{
                  tables.push(tableName);

                }



            });
            // console.log(tables);


            //get the fields for each table using SQL
            $.ajax({
              type: 'POST',
              url: "grabFields.php",
              data: { tableNames: tables },
              success: function(data)
                        {
                            attrOptions = data;
                            // console.log(attrOptions);
                            //update all dropdowns in WHERE and SELECT
                            changeDropdowns();
                        }
            });




          


        }

        function isInArray(value, array) {
          return array.indexOf(value) > -1;
        }

        id = 0
        function addDropdown(){
          $(".SELECT").append("<select id = 'SELECT" + id +"'name = 'SELECT[]' class = 'form-control' form = 'entityForm'><option></option>" + attrOptions+ "</select>");
          id += 1;
        }
        function changeDropdowns(){
       

          $('.SELECT').children('select').each(function () {
              $("#" +this.id).empty();
              $("#" +this.id).append(attrOptions);
          });
          

          $('.WHERE').children('select').each(function () {
              $("#" +this.id).empty();
              $("#" +this.id).append(attrOptions);
          });
        }
        idW = 0;
        function addWhereDropdown(){
          $(".WHERE").append("<select id = 'WHERE" + idW +"'name = 'WHERE[]' class = 'form-control' form = 'entityForm'><option></option>" + attrOptions + "</select><br>" );
          $(".WHERE").append("<select name = 'WHERE[]' class = 'form-control' form = 'entityForm'><option></option> <option> > </option> <option> < </option> <option> = </option> <option>!=</option>  <option>>=</option>  <option><=</option> <option>LIKE</option></select><br>" );
          idW +=1;
          $(".WHERE").append("<select onchange = 'checkForConstant(this.value, this.id)'; id = 'WHERE2" + idW +"' name = 'WHERE[]' class = 'form-control' form = 'entityForm'> <option></option><option>CONSTANT</option>" + attrOptions + "</select><br>" );
          $(".WHERE").append("<select name = 'WHERE[]' class = 'form-control' form = 'entityForm' ><option></option> <option>AND</option><option>OR</option></select><br>" );
          idW += 1;
        }
        function checkForConstant(selection, id){
          if (selection == "CONSTANT"){
              $("#"+id).replaceWith("<input name = 'WHERE[]' form = 'entityForm' ></input>");
          }
        }

        

        // check here for empty FROM field and empty SELECT field
        //  Bind the event handler to the "submit" JavaScript event
        function validateForm(){
               
            // Check if empty of not
            if ($("#fromSelect").val() === "" ) {
               //convert to dialog
                alert('A table was not selected in FROM');

                return false;
            }

            if(!(document.getElementById("SELECT0")) || $("#SELECT0").val() === "" ){ //eleement does not exist

                alert("No field chosen in SELECT");
                return false;
            }
          //loop through all where dropdowns looking for a malformed query there


        }
        </script>
    </body>
</html>
