<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Mon Profil - Minichat</title>
		<link rel="stylesheet" href="../assets/bootstrap-3.3.7-dist/css/bootstrap.min.css">
    	<script src="../assets/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
    	<link rel="stylesheet" href="../assets/css/style.css" />
	</head>
	<body>
		<div class="container">
			<?php session_start(); ?>
			<a class="btn btn-primary" id="btn-home" href="../index.php"><span class="glyphicon glyphicon-arrow-left"></span> Retour sur le minichat</a>
			<div class="info-membre">
				<h1>Vos informations</h1>
				<p>Pseudo: <?php echo $_SESSION['pseudo'] ?></p>
				<?php
					require("connexion-sql.php");
					$req = $bdd->prepare('SELECT email FROM membres WHERE pseudo = :pseudo');
					$req->execute(array(
						'pseudo' => $_SESSION['pseudo']
					));
					$donnees = $req->fetch();
				?>
				<p>Adresse mail: <?php echo $donnees['email'] ?></p>
			</div>
			<?php
			if (!isset($_POST['mdp-old']) AND !isset($_POST['mdp-new'])){
				//Afficher le formulaire
			?>
			<h2>Changement de votre mot de passe</h2>
			<form action="profil.php" method="post" role="form">

				<label for="mdp-old">Mot de passe actuel: </label>
				<input type="password" name="mdp-old" class="form-control"/>

				<label for="mdp-new">Nouveau mot de passe: </label>
				<input type="password" name="mdp-new" class="form-control"/>
				
				<button type="submit" class="btn btn-default submit">Enregistrer</button>
			</form>
			<?php
			}
			else {
				if ($_POST['mdp-old'] == $_POST['mdp-new']){
			?>
			<script>
				alert("Le mot de passe actuel et le nouveau sont identiques !");
				window.location.assign("profil.php");
			</script>
			<?php
				}
				else{
					//Hachage du mot de passe
					$pass_hache = password_hash($_POST['mdp-new'], PASSWORD_DEFAULT);

					//Modification
				$req->closeCursor();
					$req = $bdd->prepare('UPDATE membres SET pass = :pass WHERE pseudo = :pseudo');
					$req->execute(array(
					'pass' => $pass_hache,
					'pseudo' => $_SESSION['pseudo']
					));
			?>
			<script>
			//Informe dans une boîte de dialogue que les modifications ont été prises en compte
				alert("Les modifications de votre profil ont bien été effectuées.");
				window.location.assign("profil.php"); //redirection vers cette même page, une fois que l'utilisateur valide l'action
			</script>
			<?php 
				}
			}
			?>
		</div>
	</body>
</html>
