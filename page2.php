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
              <a class="navbar-brand" href="#">DBLP Modeling</a>
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
         <button onclick = "addParens()" type="button" class="btn btn-primary btn-xs">( )</button><br>


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
        newOptions = ""; //just the newly added attributes by most recent table selected

        selfJoinCount = 1;

        tables = [];
        function changeAttributes(tableName){

            //check that table hasn't already been selected
            if(tables.indexOf(tableName+".Editor") <= -1){
                $.ajax({
                  type: 'POST',
                  url: "grabFields.php",
                  data: { tableName: tableName },
                  success: function(data)
                            {
                                attrOptions += data;
                                newOptions = data;
                                //appends new options to dropdowns that already exist
                                changeDropdowns();
                            }
                });
          }




        //table has already been selected
        //self join condition
        else{
          changeTable = [];
          var newVal;
          //loop through each FROM dropdown
            $(".fromSelect").children('select').each(function(){
              newVal = tableName + " "+tableName.charAt(0) + selfJoinCount;
              nickName = tableName.charAt(0) + selfJoinCount;
                //loop through all options
                $("#"+this.id + "> option").each(function() {
                    if($(this).text() == tableName) {
                        changeTable.push(nickName);
                        $(this).text(newVal);
                        selfJoinCount++;
                        newVal = tableName + " "+tableName.charAt(0) + selfJoinCount;  //also have to change other dropdowns, SELECT and WHERE!!

                    }
                });

            });
            //change the select dropdown
            for (i = 0; i < changeTable.length; i++) {
                changeAttributes(changeTable[i]);
            }
            deleteAttr(tableName, ".SELECT");
        }


        }
        //tablename to remove, and selector of dropdown to remove it from
        function deleteAttr(tableName, selector){

          //before this..force it to create the dropdown?
                $(selector).children('select').each(function(){

                      console.log(selector);
                    //loop through all options
                    $("#"+this.id + "> option").each(function() {
                        if($(this).text().indexOf(tableName+".") >= 0) {
                            $(this).remove();
                        }
                    });
               });
        }


        id = 0
        function addDropdown(){
          $(".SELECT").append("<select id = 'SELECT" + id +"'name = 'SELECT[]' class = 'form-control' form = 'entityForm'><option></option>" + attrOptions+ "</select>");
          id += 1;
        }
        function changeDropdowns(){
          console.log(newOptions);
          $('.SELECT').children('select').each(function () {
              // console.log(this.id);
              $("#" +this.id).append(newOptions);
          });
          $('.WHERE').children('select').each(function () {
              // console.log(this.id);
              $("#" +this.id).append(newOptions);
          });
        }
        idW = 0;
        function addWhereDropdown(){
          $(".WHERE").append("<select id = '" + idW +"'name = 'WHERE[]' class = 'form-control' form = 'entityForm'><option></option>" + attrOptions + "</select><br>" );
          $(".WHERE").append("<select name = 'WHERE[]' class = 'form-control' form = 'entityForm'><option></option> <option> > </option> <option> < </option> <option> = </option> <option>!=</option>  <option>>=</option>  <option><=</option> <option>LIKE</option></select><br>" );
          idW +=1;
          $(".WHERE").append("<select onchange = 'checkForConstant(this.value, this.id)'; id = 'WhereSelect2" + idW +"' name = 'WHERE[]' class = 'form-control' form = 'entityForm'> <option></option><option>CONSTANT</option>" + attrOptions + "</select><br>" );
          $(".WHERE").append("<select name = 'WHERE[]' class = 'form-control' form = 'entityForm' ><option></option> <option>AND</option><option>OR</option></select><br>" );
          idW += 1;
        }
        function checkForConstant(selection, id){
          if (selection == "CONSTANT"){
              $("#"+id).replaceWith("<input name = 'WHERE[]' form = 'entityForm' ></input>");
          }
        }

        function addParens(){
        var len = $(".WHERE").children('div > select').length;
        // len -= 1;

        var children = $('.WHERE').children();
        var firstDropdown = children.first().find("option:selected"); //the first child
        var lastDropdown = children.eq(len).find("option:selected"); //the second to last child
        firstDropdown.text('(' + firstDropdown.text() );
        lastDropdown.text(lastDropdown.text() + ')');

          //append a parenthesis at the front

        }


        // check here for empty FROM field and empty SELECT field
        //  Bind the event handler to the "submit" JavaScript event
        function validateForm(){
               console.log($("#fromSelect").value );
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
