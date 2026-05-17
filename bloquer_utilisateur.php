<?php
session_start();
require_once "includes/session.php";
require_once "includes/json.php";
verifier_role("admin");
header("Content-Type: application/json");

$email="";
if(isset($_POST["email"])){
    $email=$_POST["email"];
}
$action="";
if(isset($_POST["action"])){
    $action=$_POST["action"];
}

if(strlen($email)===0 || ($action!=="bloquer" && $action!=="debloquer")){
    echo json_encode(["erreur"=>"Paramètres invalides."]);
    exit();
}

$utilisateurs=lire_json("data/inscription.json");
$index=-1;
for($i=0; $i<count($utilisateurs); $i++){
    if($utilisateurs[$i]["email"]===$email){
        $index=$i;
        break;
    }
}

if($index===-1){
    echo json_encode(["erreur"=>"Utilisateur introuvable."]);
    exit();
}

if($action==="bloquer"){
    $utilisateurs[$index]["bloque"]=true;
}else{
    $utilisateurs[$index]["bloque"]=false;
}

ecrire_json("data/inscription.json", $utilisateurs);
echo json_encode(["succes"=>true]);
?>
