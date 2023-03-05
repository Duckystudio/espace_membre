    <?php require 'include/header.php'; ?>
    <title>Inscription</title>


    <?php 
    if(isset($_POST['inscription']))
    {

      if(empty($_POST['username']) || !preg_match('/[a-zA-Z0-9]+/', $_POST['username']))
      {
        $message = 'Votre username doit être une chaine de caractéres (alphanumérique) !';
      }
      elseif(empty($_POST['email'])|| !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
      {
        $message =  'Rentrer une adresse email valide !';
      }
      
      elseif(empty($_POST['password']) || $_POST['password'] != $_POST['password2'])
      {
        $message = "Rentrer un mot de passe valide";
      }
      else
      {
        require_once 'include/start_bdd.php';


        $req = $bdd->prepare('SELECT * FROM membres.table_membres WHERE username = :username');

        $req->bindvalue(':username', $_POST['username']);
        $req->execute();
        $result = $req->fetch();

        $req1 = $bdd->prepare('SELECT * FROM membres.table_membres WHERE email = :email');

        $req1->bindvalue(':email', $_POST['email']);
        $req1->execute();
        $result1 = $req1->fetch();

        if($result)
        {
          $message = "Le nom d'utilisateur que vous avez choisi exite déjà";
        }
        elseif($result1)
        {
          $message = "Un compte est dèjà crée à l'aide l'adresse email que vous avez choisie";
        }
        else
        {

          function token_random_string($leng=20){

            $str = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $token = '';
            for($i=0;$i<$leng;$i++){
              $token.=$str[rand(0,strlen($str)-1)];
            }
            return $token;
          }

          $token = token_random_string(20);
          
          $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

          $requete = $bdd->prepare('INSERT INTO membres.table_membres(username, email, password, token) VALUES(:username, :email, :password, :token)');

          $requete->bindvalue(':username', $_POST['username']);
          $requete->bindvalue(':email', $_POST['email']);
          $requete->bindvalue(':password', $password);
          $requete->bindvalue(':token', $token);

          $requete->execute();

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
           $message1 =  "Nous vous avons envoyé par courrier des instructions pour confirmer 
           votre adresse e-mail que vous avez fournie. 
           Vous devriez bientôt les recevoir.";
         }

       }
     }
   } 

   ?>

   <div id="login">
    <h3 class="text-center text-white pt-5">Inscription</h3>
    <div class="container">
      <div id="login-row" class="row justify-content-center align-items-center">
        <div id="login-column" class="col-md-6">
          <div id="login-box" class="col-md-12">

            <center><div class="container" style="background-color:#FB6969;">
              <font color="#8B0505"> <?php if(isset($message)) echo $message;?> </font></div></center> 

              <center><div class="container" style="background-color:#95D588;">
                <font color="#115702" ><?php if(isset($message1)) echo $message1;?></font></div></center>

                <form id="login-form" class="form" action="" method="post">
                 <div class="form-group">
                  <label for="username" class="text-info">Username:</label><br>
                  <input type="text" name="username" id="username" class="form-control">
                </div>
                <div class="form-group">
                  <label for="email" class="text-info">Adresse Email:</label><br>
                  <input type="email" name="email" id="email" class="form-control">
                </div>

                <div class="form-group">
                  <label for="password" class="text-info">Mot de passe:</label><br>
                  <input type="password" name="password" id="password" class="form-control">
                </div>
                <div class="form-group">
                  <label for="password2" class="text-info">Confirmation du mot de passe:</label><br>
                  <input type="password" name="password2" id="password2" class="form-control">
                </div>
                <div class="form-group">
                  <input type="submit" name="inscription" class="btn btn-info btn-md" value="S'incrire">

                  <a href="connexion.php" class="btn btn-info btn-md" >Se connecter</a>
                </div>
                
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
  </html>
