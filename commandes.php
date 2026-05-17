<?php
session_start();
require_once "includes/session.php";
require_once "includes/json.php";
verifier_role("restaurateur");

$commandes=lire_json("data/commandes.json");
$utilisateurs=lire_json("data/inscription.json");

$a_preparer=[];
$en_preparation=[];
$prete=[];
$en_livraison=[];
for($i=0; $i<count($commandes); $i++){
    if($commandes[$i]["statut"]=="a_preparer"){
        $a_preparer[]=$commandes[$i];
    }
    if($commandes[$i]["statut"]=="en_preparation"){
        $en_preparation[]=$commandes[$i];
    }
    if($commandes[$i]["statut"]=="prete"){
        $prete[]=$commandes[$i];
    }
    if($commandes[$i]["statut"]=="en_livraison"){
        $en_livraison[]=$commandes[$i];
    }
}

if(!empty($_POST["commande_id"]) && !empty($_POST["livreur_email"])){
    for($i=0; $i<count($commandes); $i++){
        if($commandes[$i]["id"]==$_POST["commande_id"]){
            $commandes[$i]["statut"]="en_livraison";
            $commandes[$i]["livreur_email"]=$_POST["livreur_email"];
        }
    }
    ecrire_json("data/commandes.json", $commandes);
    header("location: commandes.php");
    exit();
}

function get_nom_client($email, $utilisateurs){
    for($i=0; $i<count($utilisateurs); $i++){
        if($utilisateurs[$i]["email"]==$email){
            return $utilisateurs[$i]["name"]." ".$utilisateurs[$i]["surname"];
        }
    }
    return $email;
}

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
<link rel="stylesheet" href="restaurant.css?v=3">
<script src="theme.js?v=2"></script>
<script>var livreurs_data=<?php echo json_encode($livreurs, JSON_UNESCAPED_UNICODE); ?>;</script>
<script src="commandes_actions.js" defer></script>
<title>The Wonders of Svaneti | Commandes</title>
</head>
<body id="body_commandes">
<header id="header_commandes">
    <div id="commandes_header_left">
        <div id="commandes_titre">Gestion des Commandes</div>
        <p id="commandes_sous_titre">Connecté : <?php echo $_SESSION["name"]." ".$_SESSION["surname"]; ?></p>
    </div>
    <div id="commandes_header_center">
        <a href="home_page.php" id="commandes_retour">← Accueil</a>
    </div>
    <div id="commandes_header_right">
        <div class="commandes_compteur">
            <span class="compteur_valeur" id="compteur_a_preparer"><?php echo count($a_preparer); ?></span>
            <span class="compteur_label">À préparer</span>
        </div>
        <div class="commandes_compteur">
            <span class="compteur_valeur" id="compteur_en_preparation"><?php echo count($en_preparation); ?></span>
            <span class="compteur_label">En préparation</span>
        </div>
        <div class="commandes_compteur">
            <span class="compteur_valeur"><?php echo count($en_livraison); ?></span>
            <span class="compteur_label">En livraison</span>
        </div>
        <button type="button" id="theme_toggle" onclick="basculer_theme()">🌙</button>
        <a href="deconnexion.php" id="deconnexion_button">Se déconnecter</a>
    </div>
</header>
<div id="commandes_container">
    <section class="commandes_section">
        <h2 class="commandes_section_titre">🍳 Commandes à préparer</h2>
        <table class="tab_commandes" id="table_a_preparer">
            <tr class="tab_commandes_header">
                <th>N° commande</th>
                <th>Client</th>
                <th>Plats commandés</th>
                <th>Heure</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
            <?php
            for($i=0; $i<count($a_preparer); $i++){
                $c=$a_preparer[$i];
                $nom_client=get_nom_client($c["client_email"], $utilisateurs);
                $heure=date("H\hi", strtotime($c["date_commande"]));
                $liste_plats="";
                for($j=0; $j<count($c["items"]); $j++){
                    if($j>0){
                        $liste_plats=$liste_plats.", ";
                    }
                    $liste_plats=$liste_plats.$c["items"][$j]["nom"]." x".$c["items"][$j]["quantite"];
                }
                $prevue="";
                if(!empty($c["date_livraison_prevue"])){
                    $prevue=' <span class="commande_planifiee">⏰ '.date("d/m H\hi", strtotime($c["date_livraison_prevue"])).'</span>';
                }
                echo '<tr id="row_ap_'.$c["id"].'">';
                echo '<td>'.$c["id"].$prevue.'</td>';
                echo '<td>'.$nom_client.'</td>';
                echo '<td>'.$liste_plats.'</td>';
                echo '<td>'.$heure.'</td>';
                echo '<td>'.$c["total"].' €</td>';
                echo '<td><button type="button" class="btn_statut btn_en_preparation" data-id="'.$c["id"].'" data-statut="en_preparation" data-row="row_ap_'.$c["id"].'" data-compteur="compteur_a_preparer">En préparation</button></td>';
                echo '</tr>';
            }
            ?>
        </table>
    </section>
    <section class="commandes_section">
        <h2 class="commandes_section_titre">👨‍🍳 En préparation</h2>
        <table class="tab_commandes" id="table_en_preparation">
            <tr class="tab_commandes_header">
                <th>N° commande</th>
                <th>Client</th>
                <th>Plats commandés</th>
                <th>Heure</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
            <?php
            for($i=0; $i<count($en_preparation); $i++){
                $c=$en_preparation[$i];
                $nom_client=get_nom_client($c["client_email"], $utilisateurs);
                $heure=date("H\hi", strtotime($c["date_commande"]));
                $liste_plats="";
                for($j=0; $j<count($c["items"]); $j++){
                    if($j>0){
                        $liste_plats=$liste_plats.", ";
                    }
                    $liste_plats=$liste_plats.$c["items"][$j]["nom"]." x".$c["items"][$j]["quantite"];
                }
                echo '<tr id="row_ep_'.$c["id"].'">';
                echo '<td>'.$c["id"].'</td>';
                echo '<td>'.$nom_client.'</td>';
                echo '<td>'.$liste_plats.'</td>';
                echo '<td>'.$heure.'</td>';
                echo '<td>'.$c["total"].' €</td>';
                echo '<td><button type="button" class="btn_statut btn_prete" data-id="'.$c["id"].'" data-statut="prete" data-row="row_ep_'.$c["id"].'" data-compteur="compteur_en_preparation">Prête</button></td>';
                echo '</tr>';
            }
            ?>
        </table>
    </section>
    <section class="commandes_section">
        <h2 class="commandes_section_titre">✅ Prêtes à livrer</h2>
        <table class="tab_commandes" id="table_prete">
            <tr class="tab_commandes_header">
                <th>N° commande</th>
                <th>Client</th>
                <th>Plats commandés</th>
                <th>Heure</th>
                <th>Total</th>
                <th colspan="2">Livreur</th>
            </tr>
            <?php
            for($i=0; $i<count($prete); $i++){
                $c=$prete[$i];
                $nom_client=get_nom_client($c["client_email"], $utilisateurs);
                $heure=date("H\hi", strtotime($c["date_commande"]));
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
                echo '<td colspan="2">';
                echo '<form action="commandes.php" method="post" class="form_livraison">';
                echo '<input type="hidden" name="commande_id" value="'.$c["id"].'"/>';
                echo '<select name="livreur_email" class="select_livreur" required>';
                echo '<option value="">-- Choisir un livreur --</option>';
                for($k=0; $k<count($livreurs); $k++){
                    echo '<option value="'.$livreurs[$k]["email"].'">'.$livreurs[$k]["name"]." ".$livreurs[$k]["surname"].'</option>';
                }
                echo '</select>';
                echo '<button type="submit" class="bouton_liv">Passer en livraison</button>';
                echo '</form>';
                echo '</td>';
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
                    if($j>0){
                        $liste_plats=$liste_plats.", ";
                    }
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
