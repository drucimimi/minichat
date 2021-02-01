<!DOCTYPE html>
<html>
  <head>
    <title>Connexion - Minichat</title>
    <meta charset="utf-8" />
		<link rel="stylesheet" href="../assets/bootstrap-3.3.7-dist/css/bootstrap.min.css">
    <script src="../assets/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../assets/css/style.css" />
  </head>
  <body>
    <?php 
      if (!isset($_POST['pseudo']) AND !isset($_POST['mdp'])){
        //Afficher le formulaire
    ?>
    <div class="container login">
      <form action="signin.php" method="post" role="form">
        <label for="pseudo">Pseudo: </label>
        <input type="text" name="pseudo" class="form-control"/>
        <br>

        <label for="mdp">Mot de passe: </label>
        <input type="password" name="mdp" class="form-control"/>
        <br>

        <label class="form-check-label" for="autoconnect">Connexion automatique</label>
        <input type="checkbox" class="form-check-input" name="autoconnect" />
        <br>

        <button type="submit" class="btn btn-default submit">Se connecter</button>
        
        <p class="alert alert-info">Vous n'avez pas encore de compte. Inscrivez-vous <a href="signup.php">ici</a>.</p>
      </form>
    </div>
      <?php
      }
      else {
          require("connexion-sql.php");
          //  Récupération de l'utilisateur et de son pass hashé
          $req = $bdd->prepare('SELECT id, pass FROM membres WHERE pseudo = :pseudo');
          $req->execute(array(
            'pseudo' => $_POST['pseudo']
          ));
          $resultat = $req->fetch();

          // Comparaison du pass envoyé via le formulaire avec la base
          $isPasswordCorrect = password_verify($_POST['mdp'], $resultat['pass']);

          if (!$resultat)
          {
            echo "<script>
              alert('Mauvais identifiant ou mot de passe !');
              window.location.assign('signin.php');
            </script>";
          }
          else
          {
            if ($isPasswordCorrect) {
              //enregistrement de l'id et du pseudo du membre
              session_start();
              $_SESSION['id'] = $resultat['id'];
              $_SESSION['pseudo'] = $_POST['pseudo'];
              header("Location: ../index.php");
             }else {
              echo "<script>
                alert('Mauvais identifiant ou mot de passe !');
                window.location.assign('signin.php');
            </script>";
             }
         }
      }
          
     ?> 
