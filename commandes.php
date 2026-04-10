<?php
session_start();
require_once "includes/session.php";
require_once "includes/json.php";
verifier_role("restaurateur");

$commandes=lire_json("data/commandes.json");
$utilisateurs=lire_json("data/inscription.json");

// Séparer les commandes par statut
$a_preparer=[];
$en_livraison=[];
for($i=0; $i<count($commandes); $i++){
    if($commandes[$i]["statut"]=="a_preparer"){
        $a_preparer[]=$commandes[$i];
    }
    if($commandes[$i]["statut"]=="en_livraison"){
        $en_livraison[]=$commandes[$i];
    }
}

// Récupérer le nom d'un client à partir de son email
function get_nom_client($email, $utilisateurs){
    for($i=0; $i<count($utilisateurs); $i++){
        if($utilisateurs[$i]["email"]==$email){
            return $utilisateurs[$i]["name"]." ".$utilisateurs[$i]["surname"];
        }
    }
    return $email;
}

// Récupérer la liste des livreurs disponibles
$livreurs=[];
for($i=0; $i<count($utilisateurs); $i++){
    if($utilisateurs[$i]["role"]=="livreur"){
        $livreurs[]=$utilisateurs[$i];
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<link rel="stylesheet" href="restaurant.css">
<title>The Wonders of Svaneti | Commandes</title>
</head>
<body id="body_commandes">
<header id="header_commandes">
    <div id="commandes_header_left">
        <div id="commandes_titre">Gestion des Commandes</div>
        <p id="commandes_sous_titre">Connecté : <?php echo $_SESSION["name"]." ".$_SESSION["surname"]; ?></p>
    </div>
    <div id="commandes_header_center">
        <a href="home_page.html" id="commandes_retour">← Accueil</a>
    </div>
    <div id="commandes_header_right">
        <div class="commandes_compteur">
            <span class="compteur_valeur"><?php echo count($a_preparer); ?></span>
            <span class="compteur_label">À préparer</span>
        </div>
        <div class="commandes_compteur">
            <span class="compteur_valeur"><?php echo count($en_livraison); ?></span>
            <span class="compteur_label">En livraison</span>
        </div>
        <a href="deconnexion.php" id="deconnexion_button">Se déconnecter</a>
    </div>
</header>
<div id="commandes_container">
    <section class="commandes_section">
        <h2 class="commandes_section_titre">🍳 Commandes à préparer</h2>
        <table class="tab_commandes">
            <tr class="tab_commandes_header">
                <th>N° commande</th>
                <th>Client</th>
                <th>Plats commandés</th>
                <th>Heure</th>
                <th>Total</th>
                <th>Livreur</th>
                <th>Action</th>
            </tr>
            <?php
            for($i=0; $i<count($a_preparer); $i++){
                $c=$a_preparer[$i];
                $nom_client=get_nom_client($c["client_email"], $utilisateurs);
                $heure=date("H\hi", strtotime($c["date_commande"]));

                // Construire la liste des plats
                $liste_plats="";
                for($j=0; $j<count($c["items"]); $j++){
                    if($j>0){
                        $liste_plats=$liste_plats.", ";
                    }
                    $liste_plats=$liste_plats.$c["items"][$j]["nom"]." x".$c["items"][$j]["quantite"];
                }

                echo '<tr>';
                echo '<td>'.$c["id"].'</td>';
                echo '<td>'.$nom_client.'</td>';
                echo '<td>'.$liste_plats.'</td>';
                echo '<td>'.$heure.'</td>';
                echo '<td>'.$c["total"].' €</td>';
                echo '<td>';
                echo '<select class="select_livreur">';
                echo '<option value="">-- Choisir un livreur --</option>';
                for($k=0; $k<count($livreurs); $k++){
                    echo '<option value="'.$livreurs[$k]["email"].'">'.$livreurs[$k]["name"]." ".$livreurs[$k]["surname"].'</option>';
                }
                echo '</select>';
                echo '</td>';
                echo '<td><button type="button" class="bouton_liv">Passer en livraison</button></td>';
                echo '</tr>';
            }
            ?>
        </table>
    </section>
    <section class="commandes_section">
        <h2 class="commandes_section_titre">🛵 Commandes en livraison</h2>
        <table class="tab_commandes">
            <tr class="tab_commandes_header">
                <th>N° commande</th>
                <th>Client</th>
                <th>Plats commandés</th>
                <th>Heure départ</th>
                <th>Total</th>
                <th>Livreur</th>
                <th>Statut</th>
            </tr>
            <?php
            for($i=0; $i<count($en_livraison); $i++){
                $c=$en_livraison[$i];
                $nom_client=get_nom_client($c["client_email"], $utilisateurs);
                $heure=date("H\hi", strtotime($c["date_commande"]));

                $liste_plats="";
                for($j=0; $j<count($c["items"]); $j++){
                    if($j>0) $liste_plats=$liste_plats.", ";
                    $liste_plats=$liste_plats.$c["items"][$j]["nom"]." x".$c["items"][$j]["quantite"];
                }

                $nom_livreur=get_nom_client($c["livreur_email"], $utilisateurs);

                echo '<tr>';
                echo '<td>'.$c["id"].'</td>';
                echo '<td>'.$nom_client.'</td>';
                echo '<td>'.$liste_plats.'</td>';
                echo '<td>'.$heure.'</td>';
                echo '<td>'.$c["total"].' €</td>';
                echo '<td>'.$nom_livreur.'</td>';
                echo '<td class="statut_en_cours">En route</td>';
                echo '</tr>';
            }
            ?>
        </table>
    </section>
</div>
</body>
</html>
