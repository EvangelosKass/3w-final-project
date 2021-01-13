<?php session_start();?>
<html lang="el">
	<head>
		<?php include("includes/frameworks.php")?>
		<?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
			header('Location: /');
			exit();
		}
		?>
	
		<title>Μετεωρολογικές παρατηρήσεις - Σύνδεση</title>
		
		
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
							Σύνδεση
						</div>
						<div class="card-body">
							<form action="php/login.php" method="POST" role="form">
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
							  <button type="submit" class="btn btn-primary">Σύνδεση</button>
							  <br>
							  <a href="register.php" style="padding:8px;">Εγγραφή</a>
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