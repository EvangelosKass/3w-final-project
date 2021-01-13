<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="/"><i class="material-icons md-light">home</i></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="submit.php">Νέα Καταχώρηση</a>
      </li>
		<?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
			echo '<li class="nav-item active">';
			echo '<a class="nav-link" href="/userobservations.php?user='.$_SESSION['username'].'">Οι παρατηρήσεις μου</a>';
			echo '</li>';
			echo '<li class="nav-item active">';
			echo '<a class="nav-link" href="php/logout.php">Αποσύνδεση</a>';
			echo '</li>';
		}else{
			echo '<li class="nav-item active">';
			echo '<a class="nav-link" href="register.php">Εγγραφή</a>';
			echo '</li>';
			echo '<li class="nav-item active">';
			echo '<a class="nav-link" href="login.php">Σύνδεση</a>';
			echo '</li>';
		}
		?>
    </ul>
	<a class="nav-link" style="color:white;" href="/documentation">Documentation</a>
  </div>
</nav>
