<!DOCTYPE html>
<html>
  <head>
    <title>Minichat</title>
    <meta charset="utf-8" />
    <script src="assets/js/jquery-3.5.1.js"></script>
    <link rel="stylesheet" href="assets/bootstrap-3.3.7-dist/css/bootstrap.min.css">
    <script src="assets/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="assets/css/style.css" />
  </head>
  <body>
    <div class="container">
        <?php
          session_start(); 
          if (isset($_SESSION['id']) AND isset($_SESSION['pseudo'])){
            //Afficher le message ainsi que le menu suivant
            echo '<div class="row">';
            echo '<div class="col-9 col-sm-9">';
            echo '<p class="alert alert-info info">Bonjour ' . $_SESSION['pseudo'] . '</p>';
            echo '</div>';
        ?>
        <nav class="navbar navbar-inverse col-3 col-sm-3">
          <div class="collapse navbar-collapse js-navbar-collapse">
            <ul class="nav navbar-nav">
              <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $_SESSION['pseudo'] ?> <span class="glyphicon glyphicon-menu-down"></span></a>
                <ul class="dropdown-menu">
                  <li><a href="admin/profil.php">Paramètres du profil</a></li>
                  <li><a href="admin/logon.php">Déconnexion</a></li>
                </ul>
              </li>
            </ul>
          </div>
        </nav>
      </div>
      <!--Formulaire-->
      <div class="row">
        <form id="form" action="assets/php/tp-minichat.php" method="post" role="form">
          <div class="col-9 col-sm-9">
            <textarea name="msg" rows="3" class="form-control" placeholder="Ecrivez votre message ici"></textarea> 
          </div>
          <div class="col-3 col-sm-3">
            <button type="submit" class="btn btn-default" class="submit">Envoyer</button>
          </div>
        </form>
      </div>
    
      <div class="row" id="msgs">
      <!-- Connexion avec la base de données -->
      <?php 
        require("admin/connexion-sql.php");

        //Compte le nombre de messages
        $req = $bdd->query("SELECT COUNT(id) AS nbposts FROM minichat");
        $data = $req->fetch();
        $nbposts = $data ['nbposts']; //le nombre de messages au total
        $nbperpage = 5; //nbre de messsages par page
        $nbpage = ceil($nbposts / $nbperpage); //nbre de pages total arrondi à l'entier

        //Vérifier si p existe et est compris entre 0 et le nombre de pages total
        if(isset($_GET['page']) AND $_GET['page']>0 AND $_GET['page']<=$nbpage){
          $numpage = $_GET['page']; //numéro de page
        } else {
          $numpage = 1;
        }
      
        //Affichage de 5 messages du plus récent au plus ancien par page
        $req = $bdd->query("SELECT pseudo, message FROM minichat ORDER by id DESC LIMIT ".(($numpage-1)*$nbperpage).",$nbperpage");

        //Affichage de chaque message
        while ($donnees = $req->fetch()) {
          echo '<p><strong>' . htmlspecialchars($donnees['pseudo']) .  '</strong> : ' . htmlspecialchars($donnees['message']) . '</p>';
        }
      
        //Système de pagination
        for ($i=1; $i <=$nbpage; $i++) {
          if($i==$numpage){
            echo " $i /"; //montrer qu'on est sur la page (retire le lien)
          }
          else {
            echo " <a href=\"index.php?page=$i\">$i</a> /"; //affichage des numéros de page
          }
        }

        $req->closeCursor();      
      ?>
      </div> 
      <?php
        } else {
              //Afficher le bouton de connexion
      ?>
      <div class="row">
        <div class="col-9 col-sm-9">
          <p class="alert alert-info info">Veuillez vous connecter pour accéder au minichat !</p>
        </div>
        <div class="col-3 col-sm-3">
          <a class="btn btn-primary" id="connect" href="admin/signin.php"><span class="glyphicon glyphicon-log-in"></span> Connexion</a>
        </div>
      </div>
      <?php
        }
      ?>
    </div>       
  </body>
</html>
