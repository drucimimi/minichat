<?php

$hostname='localhost';
$dbname='test';
$login='root';
$pass='';

try
{
	// On se connecte à MySQL et on active la détection des erreurs de requête SQL
	$bdd = new PDO("mysql:host=$hostname;dbname=$dbname;charset=utf8", $login, $pass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
}
catch(Exception $e)
{
	// En cas d'erreur, on affiche un message et on arrête tout
        die('Erreur : '.$e->getMessage());
}
?>
