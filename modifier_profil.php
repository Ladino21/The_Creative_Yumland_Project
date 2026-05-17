<?php
session_start();
require_once "includes/session.php";
require_once "includes/json.php";
verifier_session();
header("Content-Type: application/json");

$champ="";
if(isset($_POST["champ"])){
    $champ=$_POST["champ"];
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

if($champ==="surname"){
    $val="";
    if(isset($_POST["valeur"])){
        $val=trim($_POST["valeur"]);
    }
    if(strlen($val)===0){
        echo json_encode(["erreur"=>"Entrez votre nom de famille."]);
        exit();
    }
    if(strlen($val)>30){
        echo json_encode(["erreur"=>"Le nom ne peut pas dépasser 30 caractères."]);
        exit();
    }
    $utilisateurs[$index]["surname"]=$val;
    ecrire_json("data/inscription.json", $utilisateurs);
    $_SESSION["surname"]=$val;
    echo json_encode(["succes"=>true, "valeur"=>$val]);
    exit();
}

if($champ==="name"){
    $val="";
    if(isset($_POST["valeur"])){
        $val=trim($_POST["valeur"]);
    }
    if(strlen($val)===0){
        echo json_encode(["erreur"=>"Entrez votre prénom."]);
        exit();
    }
    if(strlen($val)>15){
        echo json_encode(["erreur"=>"Le prénom ne peut pas dépasser 15 caractères."]);
        exit();
    }
    $utilisateurs[$index]["name"]=$val;
    ecrire_json("data/inscription.json", $utilisateurs);
    $_SESSION["name"]=$val;
    echo json_encode(["succes"=>true, "valeur"=>$val]);
    exit();
}

if($champ==="email"){
    $val="";
    if(isset($_POST["valeur"])){
        $val=trim($_POST["valeur"]);
    }
    if(strlen($val)===0){
        echo json_encode(["erreur"=>"Entrez votre email."]);
        exit();
    }
    if(!filter_var($val, FILTER_VALIDATE_EMAIL)){
        echo json_encode(["erreur"=>"L'email est invalide."]);
        exit();
    }
    for($i=0; $i<count($utilisateurs); $i++){
        if($i!==$index && $utilisateurs[$i]["email"]===$val){
            echo json_encode(["erreur"=>"Cet email est déjà utilisé."]);
            exit();
        }
    }
    $utilisateurs[$index]["email"]=$val;
    ecrire_json("data/inscription.json", $utilisateurs);
    $_SESSION["email"]=$val;
    echo json_encode(["succes"=>true, "valeur"=>$val]);
    exit();
}

if($champ==="phone"){
    $val="";
    if(isset($_POST["valeur"])){
        $val=trim($_POST["valeur"]);
    }
    if(strlen($val)===0){
        echo json_encode(["erreur"=>"Entrez votre numéro de téléphone."]);
        exit();
    }
    if(strlen($val)!==10){
        echo json_encode(["erreur"=>"Le numéro doit contenir exactement 10 chiffres."]);
        exit();
    }
    for($i=0; $i<strlen($val); $i++){
        if($val[$i]<"0" || $val[$i]>"9"){
            echo json_encode(["erreur"=>"Le numéro ne peut contenir que des chiffres."]);
            exit();
        }
    }
    if($val[0]!=="0" || ($val[1]!=="6" && $val[1]!=="7")){
        echo json_encode(["erreur"=>"Le numéro doit commencer par 06 ou 07."]);
        exit();
    }
    $utilisateurs[$index]["phone"]=$val;
    ecrire_json("data/inscription.json", $utilisateurs);
    echo json_encode(["succes"=>true, "valeur"=>$val]);
    exit();
}

if($champ==="adresse"){
    $numero="";
    $rue="";
    $ville="";
    $code_postal="";
    if(isset($_POST["numero"])){
        $numero=trim($_POST["numero"]);
    }
    if(isset($_POST["rue"])){
        $rue=trim($_POST["rue"]);
    }
    if(isset($_POST["ville"])){
        $ville=trim($_POST["ville"]);
    }
    if(isset($_POST["code_postal"])){
        $code_postal=trim($_POST["code_postal"]);
    }
    if(strlen($numero)===0 || strlen($rue)===0 || strlen($ville)===0 || strlen($code_postal)===0){
        echo json_encode(["erreur"=>"Remplissez tous les champs de l'adresse."]);
        exit();
    }
    if(!ctype_digit($numero) || intval($numero)<=0){
        echo json_encode(["erreur"=>"Le numéro de rue est invalide."]);
        exit();
    }
    if(strlen($code_postal)!==5 || !ctype_digit($code_postal)){
        echo json_encode(["erreur"=>"Le code postal doit contenir exactement 5 chiffres."]);
        exit();
    }
    $utilisateurs[$index]["numero"]=$numero;
    $utilisateurs[$index]["rue"]=$rue;
    $utilisateurs[$index]["ville"]=$ville;
    $utilisateurs[$index]["code_postal"]=$code_postal;
    ecrire_json("data/inscription.json", $utilisateurs);
    $valeur_affichee=$numero." rue ".$rue.", ".$ville;
    echo json_encode(["succes"=>true, "valeur"=>$valeur_affichee]);
    exit();
}

if($champ==="password"){
    $ancien="";
    $nouveau="";
    $confirmation="";
    if(isset($_POST["ancien"])){
        $ancien=$_POST["ancien"];
    }
    if(isset($_POST["nouveau"])){
        $nouveau=$_POST["nouveau"];
    }
    if(isset($_POST["confirmation"])){
        $confirmation=$_POST["confirmation"];
    }
    if(strlen($ancien)===0 || strlen($nouveau)===0 || strlen($confirmation)===0){
        echo json_encode(["erreur"=>"Remplissez tous les champs."]);
        exit();
    }
    if($nouveau!==$confirmation){
        echo json_encode(["erreur"=>"Les mots de passe ne concordent pas."]);
        exit();
    }
    if(!password_verify($ancien, $utilisateurs[$index]["password"])){
        echo json_encode(["erreur"=>"L'ancien mot de passe est incorrect."]);
        exit();
    }
    if(strlen($nouveau)<6){
        echo json_encode(["erreur"=>"Le mot de passe doit contenir au moins 6 caractères."]);
        exit();
    }
    $a_chiffre=false;
    $a_special=false;
    $spec=["!","@","#","$","%","&","*","?","+","="];
    for($i=0; $i<strlen($nouveau); $i++){
        if($nouveau[$i]>="0" && $nouveau[$i]<="9"){
            $a_chiffre=true;
        }
        if(in_array($nouveau[$i], $spec)){
            $a_special=true;
        }
    }
    if(!$a_chiffre){
        echo json_encode(["erreur"=>"Le mot de passe doit contenir au moins un chiffre."]);
        exit();
    }
    if(!$a_special){
        echo json_encode(["erreur"=>"Doit contenir au moins un : !, @, #, $, %, &, *, ?, +, ="]);
        exit();
    }
    $utilisateurs[$index]["password"]=password_hash($nouveau, PASSWORD_DEFAULT);
    ecrire_json("data/inscription.json", $utilisateurs);
    echo json_encode(["succes"=>true]);
    exit();
}

echo json_encode(["erreur"=>"Champ inconnu."]);
