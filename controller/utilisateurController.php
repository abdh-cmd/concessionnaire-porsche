<?php
include('../modele/utilisateurModele.php');
include('../bdd/bdd.php');


if (isset($_POST['action'])) {
	$UtilisateurController = new UtilisateurController($bdd);

	switch ($_POST['action']) {
		case 'inscription':
			$UtilisateurController->create();
			break;
		case 'login':
			$UtilisateurController->login();
			break;
		
		default:
			# code...
			break;
	}
	
}

class UtilisateurController
{

	private $utilisateur;

	function __construct($bdd)
	{
		$this->utilisateur = new utilisateur($bdd);
	}


	public function create()
	{
		$this->utilisateur->ajouterUtilisateur($_POST['nom'], $_POST['prenom'], $_POST['email'], $_POST['password'], $_POST['role']);

		header('Location:http://127.0.0.1/Promo284/porsche2/');
	}


	public function login()
	{

	$user = $this->utilisateur->checkLogin($_POST['email'], $_POST['password']);

	
	if ($user) {
		session_start();
		$_SESSION['user'] = $user;

		header('Location:http://127.0.0.1/Promo284/porsche2/');
	}

	}
}


?>
