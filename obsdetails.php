<?php
session_start();

if (!isset($_GET['id'])){
	echo 'Δεν υπάρχει id';
	exit();
}

$id = intval($_GET['id']);


$db = new SQLite3('databases/database.db');
$observation = $db->query("SELECT * FROM observations WHERE id=\"{$id}\"")->fetchArray();
if(!$observation){
	echo 'Δεν υπάρχει το id';
	exit();
}
$latitude = $observation['latitude'];
$longtitude = $observation['longtitude'];
$history_where_query = "latitude<{$latitude}+0.026 AND latitude>{$latitude}-0.026 AND longtitude<{$longtitude}+0.026 AND longtitude>{$longtitude}-0.026";
$history = $db->query("SELECT * FROM observations WHERE {$history_where_query}");
$icon = $db->query("SELECT icon FROM conditions WHERE name=\"{$observation['conditions']}\"")->fetchArray()['icon'];

$max_temp= $db->query("SELECT date,temperature,MAX(temperature) FROM observations WHERE {$history_where_query}")->fetchArray();
$min_temp=$db->query("SELECT date,temperature,MIN(temperature) FROM observations WHERE {$history_where_query}")->fetchArray();;
$max_hum=$db->query("SELECT date,humidity,MAX(humidity) FROM observations WHERE {$history_where_query}")->fetchArray();;
$min_hum=$db->query("SELECT date,humidity,MIN(humidity) FROM observations WHERE {$history_where_query}")->fetchArray();;
$max_press=$db->query("SELECT date,pressure,MAX(pressure) FROM observations WHERE {$history_where_query}")->fetchArray();;
$min_press=$db->query("SELECT date,pressure,MIN(pressure) FROM observations WHERE {$history_where_query}")->fetchArray();;

?>



<html lang="el">

	<head>
		<?php include("includes/frameworks.php")?>
		<title>Μετεωρολογικές παρατηρήσεις - Λεπτομέρειες παρατήρησης</title>

		<!-- maps -->
		<script type="text/javascript">
			$(document).ready(function () {
				var lat = <?=$latitude;?>;
				var longit = <?=$longtitude;?>;
				var mymap = L.map('detailsmap').setView([lat, longit], 8);
				L.tileLayer('https://tiles.stadiamaps.com/tiles/alidade_smooth_dark/{z}/{x}/{y}{r}.png?api_key=7cd687d3-7e57-4146-8d97-fac0747438a3', {
						maxZoom: 15,
						attribution: '&copy; <a href="https://stadiamaps.com/">Stadia Maps</a>, &copy; <a href="https://openmaptiles.org/">OpenMapTiles</a> &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors'
				}).addTo(mymap);

				var marker = L.marker([lat,longit],{
					draggable: false
				}).addTo(mymap);
			});
		</script>

		<!--google charts-->

		<!--Load the AJAX API-->
		<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
		<script type="text/javascript">

		  // Load the Visualization API and the corechart package.
			google.charts.load('current', {'packages':['corechart','gauge']});

		  // Set a callback to run when the Google Visualization API is loaded.
		    google.charts.setOnLoadCallback(drawTempGauge);
			google.charts.setOnLoadCallback(drawPressureGauge);
			google.charts.setOnLoadCallback(drawHumidityGauge);
			google.charts.setOnLoadCallback(drawTempChart);
			google.charts.setOnLoadCallback(drawHumidityChart);
			google.charts.setOnLoadCallback(drawPressureChart);
		  // Callback that creates and populates a data table,
		  // instantiates the pie chart, passes in the data and
		  // draws it.
		  
		  function drawTempGauge() {

			var data = google.visualization.arrayToDataTable([
			  ['Label', 'Value'],
			  ['℃', <?=$observation['temperature']?>]
			]);

			var options = {
			  height: 150,width: 150,
			  redFrom: 38, redTo: 50,
			  yellowFrom:30, yellowTo: 38,
			  minorTicks: 5,
			  max:50,min:-30
			};

			var chart = new google.visualization.Gauge(document.getElementById('temp_gauge'));

			chart.draw(data, options);
		  }
		  function drawPressureGauge() {

			var data = google.visualization.arrayToDataTable([
			  ['Label', 'Value'],
			  ['hpa', <?=$observation['pressure']?>]
			]);

			var options = {
			  height: 150,width: 150,
			  redFrom: 1020, redTo: 1030,
			  yellowFrom:1015, yellowTo: 1020,
			  minorTicks: 5,
			  max:1030,min:975
			};

			var chart = new google.visualization.Gauge(document.getElementById('pressure_gauge'));

			chart.draw(data, options);
		  }
		  function drawHumidityGauge() {

			var data = google.visualization.arrayToDataTable([
			  ['Label', 'Value'],
			  ['%', <?=$observation['humidity']?>]
			]);

			var options = {
			  height: 150,width: 150,
			  redFrom: 85, redTo: 100,
			  yellowFrom:75, yellowTo: 85,
			  minorTicks: 5,
			  max:100,min:0
			};

			var chart = new google.visualization.Gauge(document.getElementById('humidity_gauge'));

			chart.draw(data, options);
		  }
		  
		  
		  function drawTempChart() {
			var data = new google.visualization.DataTable();
			data.addColumn('date', 'Ημερομηνία');
			data.addColumn('number', 'Θερμοκρασία');
		  
			data.addRows([
				<?php while($row = $history->fetchArray()): ?>
				[new Date(<?=$row['date']*1000?>),<?=$row['temperature']?>],
				<?php endwhile; ?>
			]);

			var options = {
			  title: 'Ιστορικό Θερμοκρασίας',
			  curveType: 'function',
			  legend: { position: 'bottom' },
			  colors:['red'],
			  explorer:{
				axis:'horizontal',
				KeepInBounds: true,
				maxZoomIn:8.0
			  }
			};

			var chart = new google.visualization.LineChart(document.getElementById('temperature_chart'));

			chart.draw(data, options);
		  }

		  function drawHumidityChart() {
			var data = new google.visualization.DataTable();
			data.addColumn('date', 'Ημερομηνία');
			data.addColumn('number', 'Υγρασία');
		  
			data.addRows([
				<?php while($row = $history->fetchArray()): ?>
				[new Date(<?=$row['date']*1000?>),<?=$row['humidity']?>],
				<?php endwhile; ?>
			]);

			var options = {
			  title: 'Ιστορικό Υγρασίας',
			  curveType: 'function',
			  legend: { position: 'bottom' },
			  colors:['blue'],
			  explorer:{
				axis:'horizontal',
				KeepInBounds: true,
				maxZoomIn:8.0
			  }
			};

			var chart = new google.visualization.LineChart(document.getElementById('humidity_chart'));

			chart.draw(data, options);
		  }

		  function drawPressureChart() {
			var data = new google.visualization.DataTable();
			data.addColumn('date', 'Ημερομηνία');
			data.addColumn('number', 'Πίεση');
		  
			data.addRows([
				<?php while($row = $history->fetchArray()): ?>
				[new Date(<?=$row['date']*1000?>),<?=$row['pressure']?>],
				<?php endwhile; ?>
			]);

			var options = {
			  title: 'Ιστορικό Πίεσης',
			  curveType: 'function',
			  legend: { position: 'bottom' },
			  colors:['orange'],
			  explorer:{
				axis:'horizontal',
				KeepInBounds: true,
				maxZoomIn:8.0
			  }
			};

			var chart = new google.visualization.LineChart(document.getElementById('pressure_chart'));

			chart.draw(data, options);
		  }


		</script>



	</head>

	<body>

		<div class="container-fluid">

			<div class="row">
				<div class="col nopadding">
				  <?php include("includes/navmenu.php") ?>
				</div>
			</div>

			<div class="row justify-content-md-center" style="margin-top:28px;">
				<div class="col-xl-9">


					<div class="card">
						<div class="card-header">
							<h5>Λεπτομέρειες παρατήρησης</h5>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-md-3">
									<div id="detailsmap"></div>
								</div>
								<div class="col-xl-9">
									<div>
										<h3><?=$observation['name']?></h3>
										<small class="text-muted font-italic">Καταχωρήθηκε <?=date('d/m/y στις H:i', $observation['date']);?> απο <a href="/userobservations.php?user=<?=$observation['user'];?>"><?=$observation['user'];?></a></small>
									</div>
									<div class="row mt-5 align-items-end">
										<div class="col-auto text-center">
											<i class="wi weather-icon-color-blue <?=$icon?>" style="font-size: 7em;margin-bottom:10px"></i>
											<div class="mt-3"><h3> </h3></div>
											<div><p><?=$observation['conditions']?></p></div>
										</div>
										<div class="col-auto text-center">
											<div id="temp_gauge" style="text-align: -webkit-center;"></div>
											<div><p>Θερμοκρασία</p></div>
										</div>
										<div class="col-auto text-center">
											<div id="humidity_gauge" style="text-align: -webkit-center;"></div>
											<div><p>Υγρασία</p></div>
										</div>
										<div class="col-auto text-center">
											<div id="pressure_gauge" style="text-align: -webkit-center;"></div>
											<div><p>Πίεση</p></div>
										</div>
									</div>
									<div><?=$observation['notes']?></div>
								</div>
							</div>
						</div>
					</div>

					<div class="card mt-4 mb-4">
						<div class="card-header">
							<h5>Ιστορικό περιοχής</h5> <small class="text-muted font-italic">(Συμπεριλαμβάνονται μετρήσεις σε ακτίνα ~3km)</small>
						</div>
						<div class="card-body">
							<div class="col">
							
								<!--<div class="dropdown text-center">
								  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Διάστημα</button>
								  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
									<a class="dropdown-item" href="#">24 ώρες</a>
									<a class="dropdown-item" href="#">15 ημέρες</a>
									<a class="dropdown-item" href="#">1 μήνας</a>
									<a class="dropdown-item" href="#">6 μήνες</a>
									<a class="dropdown-item" href="#">1 χρόνος</a>
									<a class="dropdown-item" href="#">Όλα</a>
								  </div>
								</div>-->
								
								<div class="row">
									<div class="col-9 pl-0 pr-0">
										<div id="temperature_chart"></div>
									</div>
									<div class="col-3 pl-0 pr-0 align-self-center">
										Μέγιστο: <?=$max_temp['temperature']?>&#8451;<br><small class="text-muted font-italic">Στις <?=date('d/m/y H:i', $max_temp['date'])?></small><br><br>
										Ελάχιστο: <?=$min_temp['temperature']?>&#8451;<br><small class="text-muted font-italic">Στις <?=date('d/m/y H:i', $min_temp['date'])?></small>
									</div>
								</div>
								<div class="row">
									<div class="col-9 pl-0 pr-0">
										<div id="humidity_chart"></div>
									</div>
									<div class="col-3 pl-0 pr-0 align-self-center">
										Μέγιστο: <?=$max_hum['humidity']?>%<br><small class="text-muted font-italic">Στις <?=date('d/m/y H:i', $max_hum['date'])?></small><br><br>
										Ελάχιστο: <?=$min_hum['humidity']?>%<br><small class="text-muted font-italic">Στις <?=date('d/m/y H:i', $min_hum['date'])?></small>
									</div>
								</div>
								<div class="row">
									<div class="col-9 pl-0 pr-0">
										<div id="pressure_chart"></div>
									</div>
									<div class="col-3 pl-0 pr-0 align-self-center">
										Μέγιστο: <?=$max_press['pressure']?> hpa<br><small class="text-muted font-italic">Στις <?=date('d/m/y H:i', $max_press['date'])?></small><br><br>
										Ελάχιστο: <?=$min_press['pressure']?> hpa<br><small class="text-muted font-italic">Στις <?=date('d/m/y H:i', $min_press['date'])?></small>
									</div>
								</div>


							</div>
						</div>
					</div>

				</div>
			</div>


		</div>

	</body>
</html>
