    <?php require 'include/header.php'; 
    require('PHPMailer/PHPMailerAutoload.php'); ?>
    <title>Réinitialisation</title>
  </head><body>

  <?php
  if(isset($_POST['password_forget'])){


   function token_random_string($leng=20){

    $str = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $token = '';
    for($i=0;$i<$leng;$i++){
      $token.=$str[rand(0,strlen($str)-1)];
    }
    return $token;
  }


  if(empty($_POST['email'])|| !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
   $message = "Rentrer une adresse email valide";
  }
  else{

   require('include/start_bdd.php');

   $requete=$bdd->prepare('SELECT * FROM membres.table_membres WHERE email=:email');
   $requete->bindvalue(':email', $_POST['email']);
   $requete->execute();

   $result=$requete->fetch();

   $nombre=$requete->rowCount();

   if($nombre!=1){
    $message = "L'adresse email saisie ne corréspond à aucun utilisateur de notre espace membre";
  }
  else{
    if($result['validation']!=1){

      $token = token_random_string(20);

      $update = $bdd->prepare('UPDATE membres.table_membres SET token =:token WHERE email=:email');
      $update->bindvalue(':token', $token);
      $update->bindvalue(':email', $_POST['email']);
      $update->execute();


      $mail = new PHPMailer();

      $mail ->isSMTP();
      $mail->Host='smtp.gmail.com';
      $mail->SMTPAuth = true;
      $mail->Username='proformations.fr@gmail.com';
      $mail->Password='Votre mot de passe';
      $mail->SMTPSecure ='tls';
      $mail->Port=587;

      $mail->setFrom('proformations.fr@gmail.com', 'Proformations');
      $mail->addAddress($_POST['email']);

      $mail->isHTML(true);

      $mail->Subject='Confirmation d\'email';
      $mail->Body = 'Afin de valider votre adresse email, merci de cliquer sur le lien suivant:

      <a href="http://localhost:8080/espacemembres/verification.php?token='.$token.'&email='.$_POST['email'].' ">Confirmation</a>';


      if(!$mail->send()){
        $message = "Mail non envoyé";
        echo 'Erreurs:'.$mail->ErrorInfo;
      }else{
       $message =  "Votre adresse email n'est pas encore confimée.
       Nous vous avons envoyé par courrier des instructions pour confirmer 
       votre adresse e-mail que vous avez fournie. 
       Vous devriez bientôt les recevoir.";
     }

   }else{

     $token = token_random_string(20);

     $requete1=$bdd->prepare('SELECT * FROM membres.recup_password WHERE email=:email');
     $requete1->bindvalue(':email',$_POST['email']);
     $requete1->execute();

     $nombre1=$requete1->rowCount();

     if($nombre1==0){

      $requete2=$bdd->prepare('INSERT INTO membres.recup_password(email,token) 
        VALUES(:email, :token)');

      $requete2->bindvalue(':email', $_POST['email']);
      $requete2->bindvalue(':token',$token);

      $requete2->execute();

    }else{
      $requete3= $bdd->prepare('UPDATE membres.recup_password SET token=:token WHERE email=:email');
      $requete3->bindvalue(':token', $token);
      $requete3->bindvalue(':email', $_POST['email']);
      $requete3->execute();

    }

    $mail = new PHPMailer();

    $mail ->isSMTP();
    $mail->Host='smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username='proformations.fr@gmail.com';
    $mail->Password='covid2020';
    $mail->SMTPSecure ='tls';
    $mail->Port=587;

    $mail->setFrom('proformations.fr@gmail.com', 'Proformations');
    $mail->addAddress($_POST['email']);

    $mail->isHTML(true);

    $mail->Subject=utf8_decode('Réinitialisation du mot de passe');
    $mail->Body = utf8_decode('Afin de réinitialiser votre mot de passe, merci de cliquer sur le lien suivant:

      <a href="http://localhost:8080/espacemembres/new_password.php?token='.$token.'&email='.$_POST['email'].' ">Réinitialisation du mot de passe</a>');


    if(!$mail->send()){
      $message = "Mail non envoyé";
      echo 'Erreurs:'.$mail->ErrorInfo;
    }else{
     $message1 =  "Nous vous avons envoyé par courrier des instructions pour réinitialiser votre mot de passe. 
     Vous devriez bientôt les recevoir.";
   }

  }
  }
  }

  }


  ?>

  <div id="login">
    <h3 class="text-center text-white pt-5">Mot de passe oublié</h3>
    <h6 class="text-center text-white pt-5">Merci de rentrer votre adresse email ci-dessous, nous vous enverrons des descriptions pour rénitialiser votre mot de passe </h6>

    <div class="container">
      <div id="login-row" class="row justify-content-center align-items-center">
        <div id="login-column" class="col-md-6">
          <div id="login-box" class="col-md-12">

            <center><div class="container" style="background-color:#FB6969;">
              <font color="#8B0505" ><?php if(isset($message)) echo $message;?></font></div></center>

              <center><div class="container" style="background-color:#95D588;">
                <font color="#115702" ><?php if(isset($message1)) echo $message1;?></font></div></center>


                <form id="login-form" class="form" action="" method="post">
                 
                  <div class="form-group">
                    <label for="email" class="text-info">Votre adresse Email:</label><br>
                    <input type="email" name="email" id="email" class="form-control" placeholder='Exemple: dupond@domaine.com'> 
                  </div> 

                  <div class="form-group">
                   
                    <input type="submit" name="password_forget" class="btn btn-info btn-md" value="Réinitialiser mon mot de passe">
                    
                  </div>
                  
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </body>
    </html>

