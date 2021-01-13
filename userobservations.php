<?php session_start();

if (!isset($_GET['user'])) {
	header('Location: /');
	exit();
}
$page=1;
if (isset($_GET['page'])) {
	$page=intval($_GET['page']);
}
$records_per_page = 7;
$offset = ($page-1) * $records_per_page; 


$db = new SQLite3('databases/database.db');
$username = $_GET['user'];
$observations = $db->query("SELECT * FROM observations WHERE user=\"{$username}\" ORDER BY date DESC LIMIT {$records_per_page} OFFSET {$offset}");


$total_pages = ceil ($db->querySingle("SELECT COUNT(*) as count FROM observations WHERE user=\"{$username}\" ORDER BY date DESC")/$records_per_page);

?>
<html lang="el">

	<head>
		<?php include("includes/frameworks.php")?>

		<title>Μετεωρολογικές παρατηρήσεις - Ιστορικό</title>
		
		
	</head>

	<body>

		<div class="container-fluid h-100">

			<div class="row">
				<div class="col nopadding">
				  <?php include("includes/navmenu.php") ?>
				</div>
			</div>
			

			<!-- Modal -->
			<div class="modal fade" id="deleteModalCenter" tabindex="-1" role="dialog" aria-labelledby="ModalLongTitle" aria-hidden="true">
			  <div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
				  <div class="modal-header">
					<h5 class="modal-title" id="ModalLongTitle">Επιβεβαίωση</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					  <span aria-hidden="true">&times;</span>
					</button>
				  </div>
				  <div class="modal-body">
					Διαγραφή της παρατήρησης <div id="obsId"></div>
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Ακύρωση</button>
					<button type="button" class="btn btn-primary" id="delbt">Διαγραφή</button>
				  </div>
				</div>
			  </div>
			</div>
			

			<div class="row justify-content-center">
				<div class="col-lg-6">
					<div class="card my-4">
						<div class="card-header">
							Οι παρατηρήσεις του χρήστη <?=$username;?>
						</div>
						<div class="card-body px-5">
							<?php while($row = $observations->fetchArray()): ?>
							<?php $icon = $db->query("SELECT icon FROM conditions WHERE name=\"{$row['conditions']}\"")->fetchArray()['icon'];?>
							<div class="col-auto line-devide">

								
								
								<div class="row justify-content-center m-0">
									<div class="col-auto my-auto justify-content-center">
										<h2><a style="text-decoration-line: underline;color:maroon;" href="/obsdetails.php?id=<?=$row['id']?>"><?=$row['name']?></a></h2>
									</div>
								</div>
								<div class="row justify-content-center mb-3">
									<small class="text-muted font-italic"><?=date('d/m/Y H:i', $row['date']);?></small>
								</div>
								
								<div class="row ">
								
									<?php if(isset($_SESSION['username']) && $username==$_SESSION['username']):?>
										<div class="col-auto my-auto p-0 m-0">
											<a href="#deleteModalCenter" data-toggle="modal" data-obsid="<?=$row['id']?>" data-obsinfo="<?=$row['name'].' - '.date('d/m/Y H:i', $row['date'])?>"><i class="material-icons large red-text" style="color:#d20000;font-size:3em;border-right: inset;">delete_forever</i></a>
										</div>
									<?php endif;?>
								
									<div class="col-3 text-center justify-content-center"> <!--style="border-left: dotted;border-left-color: #666666;"-->
										<div class="text-center"><i class="wi weather-icon-color-blue <?=$icon?>" style="font-size: 60px;margin-bottom:0.2em;"></i></div>
										<div class="text-center"><p><?=$row['conditions']?></p></div>
									</div>
									
									<div class="col-auto text-center justify-content-center">
										<div><i class="wi weather-icon-color-blue wi-thermometer" style="font-size: 30px;"></i></div>
										<div class="text-center"><h3><?=$row['temperature']?>&#8451;</h3></div>
										<div class="text-center"><p>Θερμοκρασία</p></div>
									</div>
									<div class="col-auto text-center justify-content-center">
										<i class="wi weather-icon-color-blue wi-raindrop" style="font-size: 30px;"></i>
										<div class="text-center"><h3><?=$row['humidity']?>%</h3></div>
										<div class="text-center"><p>Υγρασία</p></div>
									</div>
									<div class="col-auto text-center justify-content-center">
										<i class="wi weather-icon-color-blue wi-barometer" style="font-size: 30px;"></i>
										<div class="text-center"><h3><?=$row['pressure']?> hpa</h3></div>
										<div class="text-center"><p>Πίεση</p></div>
									</div>
								</div>

							</div>
							<?php endwhile; ?>
							
						</div>
					</div>
				</div>
				
				
			</div>
			
			<div class="row justify-content-center">
				<div class="col-auto">
					<nav aria-label="Page navigation" class="m-4">
					  <ul class="pagination" style="place-content: center;">
						<?php if($page-1>0): ?>
						<li class="page-item"><a class="page-link" href="<?="?user=".$username."&page=1"?>">Πρώτη</a></li>
						<li class="page-item"><a class="page-link" href="<?="?user=".$username."&page=".($page-1)?>"><span aria-hidden="true">&laquo;</span></a></li>
						<?php endif; ?>
						<li class="page-item active" aria-current="page"><a class="page-link" href="#"><?=$page?></a></li>
						<?php if($page+1<=$total_pages): ?>
						<li class="page-item"><a class="page-link" href="<?="?user=".$username."&page=".($page+1)?>"><span aria-hidden="true">&raquo;</span></a></li>
						<li class="page-item"><a class="page-link" href="<?="?user=".$username."&page=".$total_pages?>">Τελευταία</a></li>
						<?php endif; ?>
					  </ul>
					</nav>
				</div>
			</div>


		</div>


		<script>
			//triggered when modal is about to be shown
			$('#deleteModalCenter').on('show.bs.modal', function(e) {
				//get data-id attribute of the clicked element
				var obsId = $(e.relatedTarget).data('obsid');
				var obsinfo = $(e.relatedTarget).data('obsinfo');
				
				$("#obsId").html( obsinfo);
				
				
				$("#deleteModalCenter").on("click",".btn-primary", function(){
					$('#delbt').prop('disabled', true);
					$.ajax({
						url: "/php/delobs.php",
						type: "post",
						data: {id:obsId} ,
						success: function (response) {
							location.reload();
						},
						error: function(jqXHR, textStatus, errorThrown) {
						   alert('error please try again');
						}
					});
				});
				
			});

		</script>

	</body>
</html>
