Dans cette version tout le panier est au complet : 

On peut ajouter au panier depuis la card, depuis les détails. 
On peut choisir de supprimer 1 modele par 1 ou supprimer tout le panier. 

//Nouveau fichier admin/gerer_clients.php pour récup les clients dans un formulaire par id.
Nouveau fichier controller/ClientController.php pour gérer les clients dans le concess


Un client ne peut demander qu'un essai à la fois pour un véhicule.
Ex : Si un client vient demander un essai sur le véhicule 1, une autre demande d'essai viendra écraser le précédent

Historique des modifications
==============================

| Nom     | Date       | Modification réalisée                                                            | Fichier concerné                                                       |
|---------|------------|----------------------------------------------------------------------------------|------------------------------------------------------------------------|
| Anthony | 12/03/2025 | - Suppression essais dans page détail                                            | modeles.php                                                            |
|         |            | - Rajout essais dans card                                                        | demande_essai.php                                                      |
|         |            | - Affichage essai dans page détail                                               | details_voiture.php                                                    |
| Anthony | 13/03/2025 | - Ajout du bouton "Stats" dans le header pour concessionnaires                   | header.php                                                             |
| Anthony | 13/03/2025 | - Création et intégration de la page statistiques                                | stats.php                                                              |
| Anthony | 13/03/2025 | - Création du StatController et du StatModel pour récupérer et afficher les 
                           statistiques                                                                   | controller/StatController.php, model/StatModel.php                     |


