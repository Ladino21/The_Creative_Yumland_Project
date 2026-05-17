<?php
session_start();
require_once "includes/session.php";
require_once "includes/json.php";
verifier_role("restaurateur");
header("Content-Type: application/json");

$id="";
if(isset($_POST["id"])){
    $id=$_POST["id"];
}
$statut="";
if(isset($_POST["statut"])){
    $statut=$_POST["statut"];
}

$statuts_valides=["en_preparation","prete"];
$valide=false;
for($i=0; $i<count($statuts_valides); $i++){
    if($statuts_valides[$i]===$statut){
        $valide=true;
    }
}

if(strlen($id)===0 || !$valide){
    echo json_encode(["erreur"=>"Paramètres invalides."]);
    exit();
}

$commandes=lire_json("data/commandes.json");
$index=-1;
for($i=0; $i<count($commandes); $i++){
    if($commandes[$i]["id"]===$id){
        $index=$i;
        break;
    }
}

if($index===-1){
    echo json_encode(["erreur"=>"Commande introuvable."]);
    exit();
}

$commandes[$index]["statut"]=$statut;
ecrire_json("data/commandes.json", $commandes);
echo json_encode(["succes"=>true]);
?>
