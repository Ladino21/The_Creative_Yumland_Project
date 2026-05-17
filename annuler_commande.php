<?php
session_start();
require_once "includes/session.php";
require_once "includes/json.php";
verifier_session();
header("Content-Type: application/json");

$id="";
if(isset($_POST["id"])){
    $id=$_POST["id"];
}

if(strlen($id)===0){
    echo json_encode(["erreur"=>"ID manquant."]);
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

if($commandes[$index]["client_email"]!==$_SESSION["email"]){
    echo json_encode(["erreur"=>"Accès refusé."]);
    exit();
}

if($commandes[$index]["statut"]!=="a_preparer"){
    echo json_encode(["erreur"=>"Cette commande ne peut plus être annulée."]);
    exit();
}

$commandes[$index]["statut"]="abandonnee";
ecrire_json("data/commandes.json", $commandes);
echo json_encode(["succes"=>true]);
?>
