<?php session_start();?>
<html lang="el">
	<head>
		<?php include("includes/frameworks.php")?>
		<?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
			header('Location: /');
			exit();
		}?>
	
		<title>Μετεωρολογικές παρατηρήσεις - Εγγραφή</title>
		
		
	</head>

	<body>
	
		<div class="container-fluid">
		
			<div class="row">
				<div class="col nopadding">
				  <?php include("includes/navmenu.php") ?>
				</div>
			</div>
			
			<div class="row justify-content-md-center" style="margin-top:28px;">
				<div class="col-lg-3">
					<div class="card">
						<div class="card-header">
							Εγγραφή
						</div>
						<div class="card-body">
							<form action="php/register.php" method="POST" role="form">
							  <div class="form-group">
								<?php if(isset($_SESSION["error"])):?>
									<div class="alert alert-danger" role="alert"><?=$_SESSION["error"]?></div>
								<?php endif; ?>
								<label for="name">Όνομα</label>
								<input type="text" class="form-control" id="name" name="username" required="true">
							  </div>
							  <div class="form-group">
								<label for="InputPassword">Κωδικός</label>
								<input type="password" class="form-control" id="InputPassword" name="password" required="true">
							  </div>
							  <button type="submit" class="btn btn-primary">Εγγραφή</button>
							</form>
						</div>
					</div>
				</div>
			</div>

		
		</div>
		
	</body>
</html>
<?php
    unset($_SESSION["error"]);
?>