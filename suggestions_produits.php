<?php
session_start();
require_once "includes/json.php";
header("Content-Type: application/json");

$search="";
if(!empty($_GET["search"])){
    $search=$_GET["search"];
}

if(strlen($search)<2){
    echo json_encode([]);
    exit();
}

$data=lire_json("data/menus.json");
$cats_keys=["menus","entrees","plats","desserts","boissons"];
$suggestions=[];

for($s=0; $s<count($cats_keys); $s++){
    $cat_key=$cats_keys[$s];
    $items=$data[$cat_key];
    for($i=0; $i<count($items); $i++){
        $item=$items[$i];
        if(!$item["disponible"]){
            continue;
        }
        if(strpos(strtolower($item["nom"]), strtolower($search))!==false){
            $suggestions[]=$item["nom"];
        }
    }
}

echo json_encode($suggestions, JSON_UNESCAPED_UNICODE);
?>
