<?php
require('PHPMailer/PHPMailerAutoload.php');

$mail = new PHPMailer();

$mail ->isSMTP();
$mail->Host='smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username='proformations.fr@gmail.com';
$mail->Password='Aminelina281964';
$mail->SMTPSecure ='tls';
$mail->Port=587;

$mail->setFrom('proformations.fr@gmail.com', 'Proformations');
$mail->addAddress('proformationsdigital2020@gmail.com');

$mail->isHTML(true);

$mail->Subject='Cet email est un test';
$mail->Body = 'Afin de valider votre adresse email, merci de cliquer sur le lien suivant';

if(!$mail->send()){
	echo "Mail non envoyé";
	echo 'Erreurs:'.$mail->ErrorInfo;
}else{
	echo "Voitre email a bien été envoyé";
}