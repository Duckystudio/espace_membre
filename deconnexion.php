	<?php

	session_start();

	if(isset($_SESSION['id']))
	{
	session_unset();

	session_destroy();


	header('location:index.php');
	}
	else
	{
		echo 'Vous n\'êtes pas connécté ! ';
	}

