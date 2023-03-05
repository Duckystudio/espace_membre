    <?php require 'include/header.php'; ?>
    <?php

    if(isset($_POST['connexion']))
    {

      $email    = $_POST['email'];
      $password =$_POST['password'];


      require_once 'include/start_bdd.php';

      $requete = $bdd->prepare('SELECT * FROM membres.table_membres WHERE email=:email');
      $requete->execute(array('email'=>$email ));
      $result = $requete->fetch();

      if(!$result){
       $message = "Merci de rentrer une adresse email valide";
     }
     elseif($result['validation']==0){
      function token_random_string($leng=20){

        $str = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $token = '';
        for($i=0;$i<$leng;$i++){
          $token.=$str[rand(0,strlen($str)-1)];
        }
        return $token;
      }

      $token = token_random_string(20);

      $update = $bdd->prepare('UPDATE membres.table_membres SET token=:token WHERE email =:email');
      $update->bindvalue(':token', $token);
      $update->bindvalue(':email',$_POST['email']);
      $update->execute();


      require('PHPMailer/PHPMailerAutoload.php');

      $mail = new PHPMailer();

      $mail ->isSMTP();
      $mail->Host='smtp.gmail.com';
      $mail->SMTPAuth = true;
      $mail->Username='proformations.fr@gmail.com';
      $mail->Password='Simosimo-281964';
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
       $message =  "Nous vous avons envoyé par courrier des instructions pour confirmer 
                    votre adresse e-mail que vous avez fournie. 
                    Vous devriez bientôt les recevoir.";
     }
   }
















































   else
   {
     $passwordIsOk = password_verify($password, $result['password']);

     if($passwordIsOk)
     {
      session_start();

      $_SESSION['id'] = $result['id'];
      $_SESSION['username'] = $result['username'];
      $_SESSION['email'] = $email;

      if(isset($_POST['sesouvenir']))
      {
       setcookie("email", $_POST['email']);
       setcookie("password", $_POST['password']);
     }
     else
     {
       if(isset($_COOKIE['email']))
       {
        setcookie($_COOKIE['email'], "");

      }
      if(isset($_COOKIE['password']))
      {
        setcookie($_COOKIE['password'], "");
      }

    }
    header('location:index.php');
  }
  else
  {
    $message = "Merci de rentrer un mot de passe valide !";
  }

}

}

  ?>


    <div id="login">
        <h3 class="text-center text-white pt-5">Connexion</h3>
        <div class="container">
            <div id="login-row" class="row justify-content-center align-items-center">
                <div id="login-column" class="col-md-6">
                    <div id="login-box" class="col-md-12">

                        <center>
                            <div class="container" style="background-color:#FB6969;">
                                <font color="#8B0505">
                                    <?php if(isset($message)) echo $message;?>
                                </font>
                            </div>
                        </center>

                        <form id="login-form" class="form" action="" method="post">

                            <div class="form-group">
                                <label for="email" class="text-info">Adresse Email:</label><br>
                                <input type="email" name="email" id="email" class="form-control"
                                    value=<?php if(isset($_COOKIE['email'])) {echo $_COOKIE['email'];} ?>>
                            </div>

                            <div class="form-group">
                                <label for="password" class="text-info">Mot de passe:</label><br>
                                <input type="password" name="password" id="password" class="form-control"
                                    value=<?php if(isset($_COOKIE['password'])) {echo $_COOKIE['password'];} ?>>
                            </div>

                            <div class="form-group">
                                <label for="sesouvenir" class="text-info">Se souvenir de moi
                                    <input type="checkbox" name="sesouvenir" id="sesouvenir"></label>

                            </div>


                            <div class="form-group">

                                <input type="submit" name="connexion" class="btn btn-info btn-md" value="Se connecter">
                                <a href="password_forget.php">Mot de passe oublié</a>

                            </div>


                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>

    </html>