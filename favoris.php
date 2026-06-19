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

$utilisateurs=lire_json("data/inscription.json");
$index=-1;
for($i=0; $i<count($utilisateurs); $i++){
    if($utilisateurs[$i]["email"]===$_SESSION["email"]){
        $index=$i;
        break;
    }
}
if($index===-1){
    echo json_encode(["erreur"=>"Utilisateur introuvable."]);
    exit();
}

if(empty($utilisateurs[$index]["favoris"])){
    $utilisateurs[$index]["favoris"]=[];
}

$favoris=$utilisateurs[$index]["favoris"];
$est_favori=false;
$nouveau_favoris=[];
for($i=0; $i<count($favoris); $i++){
    if($favoris[$i]===$id){
        $est_favori=true;
    }else{
        $nouveau_favoris[]=$favoris[$i];
    }
}
if(!$est_favori){
    $nouveau_favoris[]=$id;
}

$utilisateurs[$index]["favoris"]=$nouveau_favoris;
ecrire_json("data/inscription.json", $utilisateurs);
echo json_encode(["succes"=>true, "favori"=>!$est_favori]);
?>
