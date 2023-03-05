<?php require 'include/header.php'; ?>
<title>Profil</title>
</head><body>
</div>

<?php


if(isset($_SESSION['id']))
{
	?>
	<div id="login">
		<h3 class="text-center text-white pt-5">Profil</h3>
		<div class="container">
			<div id="login-row" class="row justify-content-center align-items-center">
				<div id="login-column" class="col-md-6">
					<div id="login-box" class="col-md-12">

						<table>
							<tr><td>Nom d'utilisateur:</td><td><?=$_SESSION['username'] ?> </td></tr>
							<tr><td>Adresse email:</td><td><?=$_SESSION['email'] ?> </td></tr>
							<tr><td><a href="modif_profil.php"> Modifier mon profil</td></tr>

						</table>

						<?php
					}

					?>

				</body>
				</html>