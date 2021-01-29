<?php 
  require("../../admin/connexion-sql.php");
  session_start();

//Insertion des données dans la table minichat créé préalablement
  $req = $bdd->prepare('INSERT INTO minichat(pseudo, message) VALUES (:pseudo, :message)');
  $req->execute(array(
    'pseudo' => $_SESSION['pseudo'],
    'message' => $_POST['msg']
  ));
  //Redirection HTTP vers cette même page
  header('Location: ../../index.php');
?>
