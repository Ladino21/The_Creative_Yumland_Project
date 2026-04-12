<?php
session_start();
require_once "includes/session.php";
require_once "includes/json.php";

$erreur="";

if(empty($_GET["transaction"]) || empty($_GET["montant"]) || empty($_GET["vendeur"]) || empty($_GET["statut"]) || empty($_GET["control"])){
    $erreur="Paiement annulé ou refusé par la banque.";
}

if(empty($erreur)){
    $transaction=$_GET["transaction"];
    $montant=$_GET["montant"];
    $vendeur=$_GET["vendeur"];
    $statut=$_GET["statut"];
    $control=$_GET["control"];

    $api_key=substr(md5($vendeur), 1, 15);
    $control_attendu=md5($api_key."#".$transaction."#".$montant."#".$vendeur."#".$statut."#");

    if($control!=$control_attendu){
        $erreur="Contrôle de sécurité échoué.";
    }
}

if(empty($erreur)){
    if(empty($_SESSION["commande_en_cours"])){
        $erreur="Session expirée. Votre panier a été vidé.";
    }
}

if(empty($erreur)){
    $cmd_session=$_SESSION["commande_en_cours"];
    if($cmd_session["transaction"]!=$transaction){
        $erreur="Transaction invalide.";
    }
}

if(empty($erreur)){
    $cmd_session=$_SESSION["commande_en_cours"];
    $commandes=lire_json("data/commandes.json");

    // ajoutons la prochaine commande à commandes.json
    $max=0;
    for($i=0; $i<count($commandes); $i++){
        $num=intval(substr($commandes[$i]["id"], 1)); // transforme "c038" --> intval("038") --> 38
        if($num>$max){
            $max=$num;
        }
    }
    $num_suivant=$max+1;
    if($num_suivant<10){
        $nouveau_id="c00".$num_suivant;
    }elseif($num_suivant<100){
        $nouveau_id="c0".$num_suivant;
    }else{
        $nouveau_id="c".$num_suivant;
    }
    $adresse_livraison=null;
    if($cmd_session["type"]=="livraison"){
        $u=$cmd_session["adresse"];
        $adresse_livraison=[
            "numero"=>$u["numero"],
            "rue"=>$u["rue"],
            "ville"=>$u["ville"],
            "code_postal"=>$u["code_postal"],
            "etage"=>$cmd_session["etage"],
            "interphone"=>$cmd_session["interphone"],
            "commentaire"=>$cmd_session["commentaire_livraison"]
        ];
    }
    $items=[];
    for($i=0; $i<count($cmd_session["panier"]); $i++){
        $p=$cmd_session["panier"][$i];
        $items[]=[
            "id"=>$p["id"],
            "nom"=>$p["nom"],
            "quantite"=>$p["quantite"],
            "prix_unitaire"=>$p["prix_unitaire"]
        ];
    }

    $nouvelle_commande=[
        "id"=>$nouveau_id,
        "client_email"=>$_SESSION["email"],
        "items"=>$items,
        "total"=>$cmd_session["total"],
        "type"=>$cmd_session["type"],
        "adresse_livraison"=>$adresse_livraison,
        "statut"=>"a_preparer",
        "livreur_email"=>null,
        "date_commande"=>date("Y-m-d\TH:i:s"),
        "date_livraison_prevue"=>$cmd_session["date_prevue"],
        "paiement"=>"paye",
        "note"=>null
    ];

    $commandes[]=$nouvelle_commande;
    ecrire_json("data/commandes.json", $commandes);

    $_SESSION["panier"]=[];
    unset($_SESSION["commande_en_cours"]);

    header("location: profil.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<link rel="stylesheet" href="restaurant.css">
<title>The Wonders of Svaneti | Erreur paiement</title>
</head>
<body id="body_panier">
<header>
<nav id="navbar">
    <ul id="nav_left">
        <a href="presentation_page.php" target="_self"><li class="nav_item" id="indentation_left">Menus</li></a>
    </ul>
    <a href="home_page.php" target="_self"><div class="restaurant_name">The Wonders of Svaneti</div></a>
    <ul id="nav_right">
        <?php if(!empty($_SESSION["email"])){ ?>
        <a href="profil.php" target="_self"><li class="nav_item" id="indentation_right">Profil</li></a>
        <a href="deconnexion.php" id="deconnexion_button">Se déconnecter</a>
        <?php }else{ ?>
        <a href="connexion.php" target="_self"><li class="nav_item" id="indentation_right">Connexion</li></a>
        <?php } ?>
    </ul>
</nav>
</header>
<div id="panier_container">
    <div id="panier_erreur_bloc">
        <h1 id="panier_erreur_titre">Paiement échoué</h1>
        <p id="panier_erreur_msg"><?php echo $erreur; ?></p>
        <a href="panier.php" id="panier_retour_menu">← Retour au panier</a>
    </div>
</div>
</body>
</html>
