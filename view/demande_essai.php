<?php
session_start();
include_once __DIR__ . '/../controller/EssaiController.php';
include_once __DIR__ . '/../bdd/bdd.php';

$essaiController = new EssaiController($bdd);

// Le formulaire doit être soumis en méthode POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php?page=modeles');
    exit();
}

// Récupérer l'ID de la voiture depuis POST
$idVoiture = isset($_POST['id_voiture']) ? intval($_POST['id_voiture']) : 0;
if ($idVoiture <= 0) {
    die("<p>Erreur : ID de voiture invalide.</p>");
}

// Vérifier que le véhicule existe en base de données
$query = $bdd->prepare("SELECT * FROM voitures WHERE id = :idVoiture");
$query->execute(['idVoiture' => $idVoiture]);
$voiture = $query->fetch(PDO::FETCH_ASSOC);
if (!$voiture) {
    die("<p>Erreur : Véhicule introuvable.</p>");
}

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header('Location: index.php?page=login');
    exit();
}
$idUtilisateur = $_SESSION['user']['ID_Utilisateur'];

// Récupérer les champs du formulaire de demande d'essai
$dateVoulue = isset($_POST['date_voulue']) ? trim($_POST['date_voulue']) : '';
$finValidite = isset($_POST['fin_validite_permis']) ? trim($_POST['fin_validite_permis']) : '';
$anneePermis = isset($_POST['annee_permis']) ? intval($_POST['annee_permis']) : 0;
$accompagnant = isset($_POST['accompagnant']) ? trim($_POST['accompagnant']) : '';

if (empty($dateVoulue) || empty($finValidite) || $anneePermis <= 0) {
    $_SESSION['message'] = "Veuillez remplir tous les champs obligatoires.";
    header('Location: index.php?page=modeles');
    exit();
}

// Appeler la méthode pour créer la demande d'essai (insertion dans la table DemandeEssai)
$result = $essaiController->demanderEssai($idUtilisateur, $idVoiture, $dateVoulue, $finValidite, $anneePermis, $accompagnant);

if (!empty($result['success']) && $result['success']) {
    $_SESSION['message'] = "Votre demande d'essai a été envoyée avec succès !";
} else {
    $_SESSION['message'] = "Erreur : " . htmlspecialchars($result['message']);
}

// Rediriger vers la page des détails du véhicule pour afficher le récapitulatif
header('Location: index.php?page=details_voiture&id=' . $idVoiture);
exit();
?>
