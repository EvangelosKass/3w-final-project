<?php session_start();
$db = new SQLite3('databases/database.db');
$conditions = $db->query("SELECT * FROM conditions");
?>
<html lang="el">
	<head>
		<?php include("includes/frameworks.php")?>
		<?php if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] == false) {
			header('Location: login.php');
			exit();
		}?>
		<title>Μετεωρολογικές παρατηρήσεις - Νεα καταχώρηση</title>
		
		
		<script>
			$(document).ready(function () {
				var mymap = L.map('submitmap');
					L.tileLayer('https://tiles.stadiamaps.com/tiles/alidade_smooth_dark/{z}/{x}/{y}{r}.png?api_key=7cd687d3-7e57-4146-8d97-fac0747438a3', {
						maxZoom: 15,
						attribution: '&copy; <a href="https://stadiamaps.com/">Stadia Maps</a>, &copy; <a href="https://openmaptiles.org/">OpenMapTiles</a> &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors'
					}).addTo(mymap);
				mymap.fitBounds(new L.LatLngBounds([[41.2,19.6],[34.8,29.1]]));
				
				var marker = L.marker([38.37,25.100],{
					draggable: true
				}).addTo(mymap)
				.bindPopup("<br>Σύρε τον δείκτη<br><br>")
				.openPopup();
				
				marker.on('drag', function (e) {
				  document.getElementById('latitude').value = marker.getLatLng().lat;
				  document.getElementById('longitude').value = marker.getLatLng().lng;
				});
			});
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
				<div class="col-xl-8">
					<div class="card">
						<div class="card-header">
							Νεα καταχώρηση
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-lg-9">
									<div id="submitmap"></div>
								</div>
								<div class="col-md-3">
									<form action="php/addnewobservation.php" method="POST" role="form">
										<div class="form-group row">
											<label for="latitude"  class="col-sm-auto col-form-label">Latitude:</label>
											<input type="text" class="form-control form-control-sm col-lg-6" id="latitude" required="true" name="latitude" placeholder="38.37" readonly>
											<label for="longitude"  class="col-sm-auto col-form-label">Longitude:</label>
											<input type="text" class="form-control form-control-sm col-lg-6" id="longitude" required="true" name="longitude" placeholder="25.100" readonly>
										</div>
										<br>
										<div class="form-group">
											<label for="name">Όνομα</label>
											<input type="text" class="form-control" id="name" required="true" name="name" placeholder="Όνομα τοποθεσίας ή πόλης">
											
											<label for="temperature">Θερμοκρασία (&#8451;)</label>
											<input type="number" step="0.01" min=-273 max=70 class="form-control" id="temperature" required="true" name="temperature" placeholder="24">
											<label for="humidity">Υγρασία (%)</label>
											<input type="number" step="0.01" min=0 max=100 class="form-control" id="humidity" required="true" name="humidity" placeholder="63">
											<label for="pressure">Ατμοσφαιρική πίεση (hpa)</label>
											<input type="number" step="0.01" min=600 max=1400 class="form-control" id="pressure" required="true" name="pressure" placeholder="1013,25">
											<label for="conditions">Συνθήκες</label>
											<select id="conditions" class="form-control" name="conditions" required="true">
												<?php while($row = $conditions->fetchArray()): ?>
													<option><?=$row['name']?></option>
												<?php endwhile; ?>
											</select>
											<label for="notes">Σημειώσεις</label>
											<textarea class="form-control" id="notes" rows="2" name="notes" placeholder="πχ. Άνεμος, υψόμετρο ή άλλες παρατηρήσεις"></textarea>
										</div>
										<button type="submit" class="btn btn-primary">Υποβολή</button>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

		
		</div>
		
	</body>
</html>