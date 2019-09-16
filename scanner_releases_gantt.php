<?php

  $nav_selected = "SCANNER"; 
  $left_buttons = "YES"; 
  $left_selected = "RELEASESGANTT"; 

  include("./nav.php");
  global $db;

  ?>


<div class="right-content">
    <div class="container">

      <h3 style = "color: #01B0F1;">Scanner -> System Releases Gantt</h3>
	  <div id="chart_div" style="overflow-x: scroll;"></div>



    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	<script type="text/javascript">
		google.charts.load('current', {'packages':['gantt']});
		google.charts.setOnLoadCallback(drawChart);

		function daysToMilliseconds(days) {
		  return days * 24 * 60 * 60 * 1000;
		}
		
			                <?php

$sql = "SELECT * from releases ORDER BY rtm_date ASC;";
$result = $db->query($sql);
$data = array();
$i = 0;

                if ($result->num_rows > 0) {
                    // output data of each row
                    while($row = $result->fetch_assoc()) {
                        $data[$i] = $row;
						$i++;
                    }//end while
                }//end if
                
				
                 $result->close();
                ?>

		function drawChart() {

	
		  var data = new google.visualization.DataTable();
		  data.addColumn('string', 'Task ID');
		  data.addColumn('string', 'Task Name');
		  data.addColumn('date', 'Start Date');
		  data.addColumn('date', 'End Date');
		  data.addColumn('number', 'Duration');
		  data.addColumn('number', 'Percent Complete');
		  data.addColumn('string', 'Dependencies');



		  

		 
		  var array = Object.values(<?php echo json_encode($data) ?>);
		  array.forEach(function(row,i) {
			  array[i] = Object.values(row);
			  
			  array[i].forEach(function(row, k) {
				  if(k === 4 || k === 5 || k === 6 || k === 7) {
					  if(array[i][k] === '0000-00-00') {
						  array[i][k] = null;
					  }
					  else {
						array[i][k] = new Date(array[i][k]);
					  }
				  }
				  if(k === 8 || k === 9) {
					  if(k === 9) {
						  if(array[i][3] === 'Draft') {
							  array[i][k] = 10;
						  }
						  else if(array[i][3] === 'Active') {
							  array[i][k] = 50;
						  }
						  else {
							  array[i][k] = 100;
						  }
					  }
					  else {
						  if(array[i][7] && array[i][4]) {
							array[i][k] = array[i][7].getTime() - array[i][4].getTime();
						  }
						  else if(array[i][7]) {
							array[i][k] = array[i][5].getTime();
						  }
						  else {
							array[i][k] = 0;
						  }
					  }
				  }
				  if(k === 10) {
					  array[i][k] = null;
				  }

				  
			  });
			  
			  for(j = 11; j >=0; j--) {
				  if(j === 2 || j === 3 || j === 6 || j === 5) {
					  array[i].splice(j,1);
				  }

			  }
		  });
		  console.log(array); 

		  data.addRows(array);


		  var options = {
			height: 600
		  };

		  var chart = new google.visualization.Gantt(document.getElementById('chart_div'));

		  chart.draw(data, options);
		  
		}
	</script>

        
        TODO: work in progress

        

 <style>
   tfoot {
     display: table-header-group;
   }
 </style>

  <?php include("./footer.php"); ?>
