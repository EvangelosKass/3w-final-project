<?php session_start();
$db = new SQLite3('databases/database.db');
$observations = $db->query("SELECT * FROM observations ORDER BY date DESC LIMIT 12");
?>
<html lang="el">

	<head>
		<?php include("includes/frameworks.php")?>

		<title>Μετεωρολογικές παρατηρήσεις - Αρχική</title>

		<script>
			$(document).ready(function () {
				var mymap = L.map('mapid');
				L.tileLayer('https://tiles.stadiamaps.com/tiles/alidade_smooth_dark/{z}/{x}/{y}{r}.png?api_key=7cd687d3-7e57-4146-8d97-fac0747438a3', {
					maxZoom: 15,
					attribution: '&copy; <a href="https://stadiamaps.com/">Stadia Maps</a>, &copy; <a href="https://openmaptiles.org/">OpenMapTiles</a> &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors'
				}).addTo(mymap);
				mymap.fitBounds(new L.LatLngBounds([[41.2,19.6],[34.8,29.1]]));

				
				refreshMarkers(mymap);
				//mymap.on("zoomend", function (e) { console.log("ZOOMEND", e); });
				
			});
			
			
			function refreshMarkers(mymap) {
				<?php while($row = $observations->fetchArray()): ?>
				<?php $icon = $db->query("SELECT icon FROM conditions WHERE name=\"{$row['conditions']}\"")->fetchArray()['icon'];$temperature=$row['temperature'];?>
				L.marker([<?=$row['latitude']?>,<?=$row['longtitude']?>]).addTo(mymap)
					.bindPopup('<a href="/obsdetails.php?id=<?=$row['id']?>" style="text-decoration:none;" class="text-reset"><i class="wi weather-icon-color-blue <?=$icon?>" style="font-size: 28px; padding:4px;"></i><br><div class="temppopup"><?=$temperature?>&#8451;</div></a>',{
						closeOnClick: false,
						autoClose: false,
						closeButton: false})
					.openPopup();
				<?php endwhile; ?>
			}
			
		</script>

	</head>

	<body>

		<div class="container-fluid h-100">

			<div class="row">
				<div class="col nopadding">
				  <?php include("includes/navmenu.php") ?>
				</div>
			</div>

			<div class="row full-height-maprow">
				<div class="col-xl nopadding flex-grow-1">
				  <div id="mapid"></div>

				</div>
				<div class="col-md-3 recent">
				  <div style="color:#ffffff;text-align: center;"><h3>Πρόσφατα</h3></div>

					<?php while($row = $observations->fetchArray()): ?>
					<?php $icon = $db->query("SELECT icon FROM conditions WHERE name=\"{$row['conditions']}\"")->fetchArray()['icon'];?>
					<div class="card mt-2">
						<a href="/obsdetails.php?id=<?=$row['id']?>" style="text-decoration:none;" class="text-reset">
							<div class="card-body p-2">
								<div class="row">
									<div class="col-sm-4 align-self-center text-center">
										<i class="wi weather-icon-color-blue <?=$icon?> text-center" style="font-size: 50px;margin:0.1em;"></i>
										<div><small><?=$row['conditions']?></small></div>
									</div>
									<div class="col" style="border-left: dotted;border-left-color: #666666;">
										<div><h4><?=$row['name']?></h4></div>
										<div><h4><i class="wi weather-icon-color-blue wi-thermometer" style="font-size: 20px;"></i> <?=$row['temperature']?>&#8451;</h4></div>
										<div style="text-align: end;"><small class="text-muted font-italic"><?=date('H:i d/m', $row['date']);?></small></div>
									</div>
								</div>
							</div>
						</a>
					</div>
					<?php endwhile; ?>


				</div>
			</div>


		</div>

	</body>
</html>
