<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>Inscription - Minichat</title>
    <link rel="stylesheet" href="../assets/bootstrap-3.3.7-dist/css/bootstrap.min.css">
    <script src="../assets/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../assets/css/style.css" />
  </head>
  <body>
    <?php
        if (!isset($_POST['pseudo']) AND !isset($_POST['mdp']) AND !isset($_POST['mdp2']) AND !isset($_POST['mail'])){
          //Afficher le formulaire
    ?>
    <div class="container signup">
      <form action="signup.php" method="post" role="form">
        <label for="pseudo">Pseudo: </label>
        <input type="text" name="pseudo" class="form-control" />
        <br>

        <label for="mdp">Mot de passe: </label>
        <input type="password" name="mdp" class="form-control" />
        <br>

        <label for="mdp2">Retapez votre mot de passe: </label>
        <input type="password" name="mdp2" class="form-control" />
        <br>

        <label for="mail">Adresse email: </label>
        <input type="email" name="mail" class="form-control" />
                
        <button type="submit" class="btn btn-default submit">S'inscrire</button>
      </form>
    </div>

      <?php
        }
        else {
          require("connexion-sql.php");
          $req = $bdd->prepare('SELECT * FROM membres where pseudo = ?');
          $req->execute(array($_POST['pseudo']));
          $donnees = $req->fetch();
          if (!empty($donnees)) {
            echo "Ce pseudo est déjà utilisé.";
          }
          elseif ($_POST['mdp'] != $_POST['mdp2']){
            echo "Les mots de passe ne correspondent pas.";
          }
          elseif (!preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#i", $_POST['mail'])){
            echo "Vous avez saisi une adresse email non conforme.";
          }
          else{
            //Hachage du mot de passe
            $pass_hache = password_hash($_POST['mdp'], PASSWORD_DEFAULT);

            // Insertion
            $req = $bdd->prepare('INSERT INTO membres(pseudo, pass, email, date_inscription) VALUES(:pseudo, :pass, :email, CURDATE())');
            $req->execute(array(
              'pseudo' => $_POST['pseudo'],
              'pass' => $pass_hache,
              'email' => $_POST['mail']
            ));
            echo "<p>Vous êtes maintenant inscrit. Il ne vous reste plus qu'à vous connecter !<br />
            Vous serez redirigé vers la page de connexion dans quelques secondes.</p>";
            sleep(10);
            header("Location: signin.php");
          }
        }
      ?>
 </body>
</html>
