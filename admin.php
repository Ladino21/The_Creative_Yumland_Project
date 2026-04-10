<?php
session_start();
require_once "includes/session.php";
require_once "includes/json.php";
verifier_role("admin");

$utilisateurs=lire_json("data/inscription.json");
$commandes=lire_json("data/commandes.json");

// Statistiques
$nb_utilisateurs=count($utilisateurs);

$nb_avec_commande=0;
$nb_en_cours=0;
$total_notes=0;
$nb_notes=0;
for($i=0; $i<count($commandes); $i++){
    if($commandes[$i]["statut"]=="a_preparer"||$commandes[$i]["statut"]=="en_livraison"){
        $nb_en_cours++;
    }
    if(!empty($commandes[$i]["note"])){
        $total_notes=$total_notes+$commandes[$i]["note"]["note_produit"];
        $nb_notes++;
    }
}
$emails_commande=[];
for($i=0; $i<count($commandes); $i++){
    $email_c=$commandes[$i]["client_email"];
    $deja=false;
    for($j=0; $j<count($emails_commande); $j++){
        if($emails_commande[$j]==$email_c){
            $deja=true;
        }
    }
    if(!$deja){
        $emails_commande[]=$email_c;
    }
}
$nb_avec_commande=count($emails_commande);

if($nb_notes>0){
    $note_moyenne=round($total_notes/$nb_notes, 1);
}else{
    $note_moyenne="—";
}

// Recherche et filtre
$recherche="";
if(!empty($_GET["search"])){
    $recherche=$_GET["search"];
}
$filtre="tous";
if(!empty($_GET["filtre"])){
    $filtre=$_GET["filtre"];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<link rel="stylesheet" href="restaurant.css">
<title>The Wonders of Svaneti | Administration</title>
</head>
<body id="body_admin">
<header id="admin_header">
    <div id="admin_header_left">
        <div id="admin_title">Page Administrateur</div>
        <p id="admin_undertitle">Gestion des utilisateurs</p>
    </div>
    <div id="admin_header_right">
        <a href="home_page.html" id="admin_retour">← Retour à l'accueil</a>
        <a href="deconnexion.php" id="deconnexion_button">Se déconnecter</a>
    </div>
</header>
<div id="admin_container">
    <aside id="admin_colonne">
        <div class="statistiques">
            <div class="value_admin"><?php echo $nb_utilisateurs; ?></div>
            <div class="label_admin">Utilisateurs inscrits</div>
        </div>
        <div class="statistiques">
            <div class="value_admin"><?php echo $nb_avec_commande; ?></div>
            <div class="label_admin">Ont passé une commande</div>
        </div>
        <div class="statistiques">
            <div class="value_admin"><?php echo $nb_en_cours; ?></div>
            <div class="label_admin">Commandes en cours</div>
        </div>
        <div class="statistiques">
            <div class="value_admin"><?php echo $note_moyenne; ?> ⭐</div>
            <div class="label_admin">Note moyenne</div>
        </div>
    </aside>
    <main id="admin_res">
        <div id="admin_recherche">
            <form id="admin_form" action="admin.php" method="get">
                <input type="search" placeholder="Rechercher un utilisateur..." id="search_bar_admin" name="search" value="<?php echo $recherche ; ?>"/>
                <button type="submit" id="admin_button">Rechercher</button>
                <select id="admin_list" name="filtre">
                    <option value="tous">Tous les utilisateurs</option>
                    <option value="commandes">Avec commandes</option>
                    <option value="sans">Sans commande</option>
                </select>
            </form>
        </div>
        <table id="tab_admin">
            <tr class="entete">
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Rôle</th>
                <th>Inscription</th>
                <th>Profil</th>
                <th>Actions</th>
            </tr>
            <?php
            for($i=0; $i<count($utilisateurs); $i++){
                $u=$utilisateurs[$i];

                // Filtre avec/sans commande
                if($filtre=="commandes"){
                    $a_commande=false;
                    for($j=0; $j<count($emails_commande); $j++){
                        if($emails_commande[$j]==$u["email"]){
                            $a_commande=true;
                        }
                    }
                    if(!$a_commande){
                        continue; 
                    }
                }
                if($filtre=="sans"){
                    $a_commande=false;
                    for($j=0; $j<count($emails_commande); $j++){
                        if($emails_commande[$j]==$u["email"]){
                            $a_commande=true;
                        }
                    }
                    if($a_commande){
                        continue; 
                    }
                }

                // Filtre recherche texte
                if(!empty($recherche)){
                    $nom_complet=strtolower($u["name"]." ".$u["surname"]);
                    $email_lower=strtolower($u["email"]);
                    $recherche_lower=strtolower($recherche);
                    if(strpos($nom_complet, $recherche_lower)===false && strpos($email_lower, $recherche_lower)===false){
                        continue;
                    }
                }
                echo '<tr>';
                echo '<td>'.$u["surname"].'</td>';
                echo '<td>'.$u["name"].'</td>';
                echo '<td>'.$u["email"].'</td>';
                echo '<td>'.$u["phone"].'</td>';
                echo '<td>'.$u["role"].'</td>';
                echo '<td>'.date("d/m/Y", strtotime($u["date_inscription"])).'</td>';
                echo '<td><a href="profil.php?email='.urlencode($u["email"]).'" class="redirection_profil">Voir</a></td>';
                echo '<td>';
                echo '<button type="button" class="bouton_admin_action">Bloquer</button>';
                echo '<button type="button" class="bouton_admin_action">Premium</button>';
                echo '</td>';
                echo '</tr>';
            }
            ?>
        </table>
    </main>
</div>
</body>
</html>

