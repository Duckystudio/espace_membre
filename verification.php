<?php require 'include/header.php';
require_once 'include/start_bdd.php';
?>
<title>Verification</title>
</head><body>

<?php
if($_GET){

	if(isset($_GET['email'])){
		$email = $_GET['email'];
	}
	if(isset($_GET['token'])){
		$token = $_GET['token'];
	}


	if(!empty($email) && !empty($token)){

		$requete = $bdd->prepare('SELECT * FROM membres.table_membres WHERE email=:email AND token=:token');
		$requete->bindvalue(':email',$email);
		$requete->bindvalue(':token',$token);

		$requete->execute();

		$nombre=$requete->rowCount();

		if($nombre==1){
			$update = $bdd->prepare('UPDATE membres.table_membres SET validation=:validation, token=:token WHERE email=:email');

			$update->bindvalue(':validation',1);
			$update->bindvalue(':token','valide');
			$update->bindvalue(':email',$email);

			$resultUpdate=$update->execute();

			if($resultUpdate){
				echo "<script type=\"text/javascript\">
				alert('Votre adresse email est bien confirmée');
				document.location.href='connexion.php';
			</script>";


		}
	}
}
}















