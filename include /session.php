<?php
// Verif connexion
function verifier_session(){
    if(empty($_SESSION["email"])){
        header("location:connexion.php");
        exit;
    }
}

// Verif bon rôle (pour admin.php)
function verifier_role($role_requis){
    verifier_session();
    if($_SESSION["role"]!==$role_requis){
        header("location:connexion.php");
        exit;
    }
}
?>
