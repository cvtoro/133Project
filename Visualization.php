
<html>
<head>


	<?php
	$beginYear = $_POST["beginYr"];
	$endYear = $_POST["endYr"];




    try{
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 


        $query = "SELECT DISTINCT Article.journal FROM Article";

        $result = mysqli_query($conn, $query);
        if (!($result = mysqli_query($conn, $query))){
          echo("<p class = 'bg-warning'>Sorry could not process your query </p>". mysqli_error($conn) );
            //redirect


        }
 
        else{
              

				$json = "[";
      	  //fetch each journal
            while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
	
			   $jrnl = $row['journal'];
			   // echo $jrnl . "<br>";

				$query2 = "SELECT Article.year, COUNT(*) FROM Article WHERE Article.journal = '". $jrnl . 
						"' AND Article.year >= " . $beginYear. " AND Article.year <= ". $endYear . " GROUP BY Article.year";
				// echo $query2 . "<br>";

				$result2 = mysqli_query($conn, $query2);
			    if (!($result2)){
			              // echo(mysqli_error($conn) . "<br>" );
			             


			    }
			    //fetch the count of each year  
				else{
					$article = [];
					$totalCount = 0;

					while($row2 = mysqli_fetch_array($result2,MYSQLI_ASSOC)){

						$year = $row2['year'];
						$count = $row2['COUNT(*)'];
						// echo $year . "<br>";
						// echo $count . "<br>";
						$temp = [(int)$year, (int)$count];
						$totalCount = $totalCount + $count;
						// echo $totalCount . "<br>";
						array_push($article, $temp);

					}
					
					if (!(empty($article)) && !(empty($jrnl)) ){
					$list = json_encode($article);
					// echo $list . "<br>";
					$json = $json . "{\"articles\":" . $list. ",\"total\": ".$totalCount. ",\"name\": \" ". $jrnl." \" },\n";
					
					}
				}
               
			 
			}
		
          

            
            $json = rtrim($json, "\n");

            $json = rtrim($json, ",");
            $json .= "]";
            $jsonFile = fopen($_SERVER['DOCUMENT_ROOT']."/133Project/data.json", "w") or die("unable to open file!");


            fwrite($jsonFile, $json);
            // echo $json;
            fclose($jsonFile);


        }

    }
    catch (mysqli_sql_exception $e){
            throw new MySQLiQueryException($SQL, $e->getMessage(), $e->getCode());

    }

    //Close connection
    mysqli_close($conn); 

	?>

<script src="//d3js.org/d3.v3.min.js"></script>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">


<style type="text/css">
h1{
	margin-top:10%;
}
#body{
	/*margin-left: 300px;*/
}
body{font-family: Arial, sans-serif;font-size:10px;}
.axis path,.axis line {fill: none;stroke:#b6b6b6;shape-rendering: crispEdges;}
/*.tick line{fill:none;stroke:none;}*/
.tick text{fill:#999;}
g.journal.active{cursor:pointer;}
text.label{font-size:12px;font-weight:bold;cursor:pointer;}
text.value{font-size:12px;font-weight:bold;}
</style>
</head>
<body>
	<div id = "body">
		<nav class="navbar navbar-inverse navbar-fixed-top">
	      <div class="container-fluid">
	        <div class="navbar-header">
	          <a class="navbar-brand" href="#">DBLP Modeling</a>
	        </div>

	      </div>

	    </nav>
	<div class="container text-center" >
		<h1>Number of Articles Per Journal Per Year</h1>
		<h6>Each node represents how many articles were written that year for that journal</h6>
		<br>
	

<script type="text/javascript">
function truncate(str, maxLength, suffix) {
	if(str.length > maxLength) {
		str = str.substring(0, maxLength + 1); 
		str = str.substring(0, Math.min(str.length, str.lastIndexOf(" ")));
		str = str + suffix;
	}
	return str;
}

var margin = {top: 20, right: 200, bottom: 0, left: 200},
	width = 600,
	height = 15000;

var start_year = <?php echo $beginYear ?>;
	end_year =  <?php echo $endYear ?>;

// var c = d3.scale.category20c();
var c = d3.scale.category20();


var x = d3.scale.linear()
	.range([0, width]);

var xAxis = d3.svg.axis()
	.scale(x)
	.orient("top");

var formatYears = d3.format("0000");
xAxis.tickFormat(formatYears);

var svg = d3.select("body").append("svg")
	.attr("width", width + margin.left + margin.right)
	.attr("height", height + margin.top + margin.bottom)
	.style("margin-left", margin.left + "px")
	.append("g")
	.attr("transform", "translate(" + margin.left + "," + margin.top + ")");


d3.json("data.json", function(error, data) {
		if (error) throw error; 

	x.domain([start_year, end_year]);
	var xScale = d3.scale.linear()
		.domain([start_year, end_year])
		.range([0, width]);

	svg.append("g")
		.attr("class", "x axis")
		.attr("transform", "translate(0," + 0 + ")")
		.call(xAxis);

    
	var len = data.length;
	for (var j = 0; j < len; j++) {

		try{
		console.log(data[j]);
		var g = svg.append("g").attr("class","journal");

		var circles = g.selectAll("circle")
			.data(data[j]['articles'])
			.enter()
			.append("circle");

		var text = g.selectAll("text")
			.data(data[j]['articles'])
			.enter()
			.append("text");

		var rScale = d3.scale.linear()
			.domain([0, d3.max(data[j]['articles'], function(d) { return d[1]; })])
			.range([2, 9]);

		circles
			.attr("cx", function(d, i) { return xScale(d[0]); })
			.attr("cy", j*20+20)
			.attr("r", function(d) { return rScale(d[1]); })
			.style("fill", function(d) { return c(j); });

		text
			.attr("y", j*20+25)
			.attr("x",function(d, i) { return xScale(d[0])-5; })
			.attr("class","value")
			.text(function(d){ return d[1]; })
			.style("fill", function(d) { return c(j); })
			.style("display","none");

		g.append("text")
			.attr("y", j*20+25)
			.attr("x",width+20)
			.attr("class","label")
			.text(truncate(data[j]['name'],50,"..."))
			.style("fill", function(d) { return c(j); })
			.on("mouseover", mouseover)
			.on("mouseout", mouseout);



		}
		catch(err){
			console.log(err);
			continue;
		}
	};

	function mouseover(p) {
		var g = d3.select(this).node().parentNode;
		d3.select(g).selectAll("circle").style("display","none");
		d3.select(g).selectAll("text.value").style("display","block");
	}

	function mouseout(p) {
		var g = d3.select(this).node().parentNode;
		d3.select(g).selectAll("circle").style("display","block");
		d3.select(g).selectAll("text.value").style("display","none");
	}


});



</script>
</div>


</body>
</html>