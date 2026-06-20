<?php
session_start();
require_once "includes/json.php";
header("Content-Type: application/json");

$data=lire_json("data/menus.json");

$search="";
if(!empty($_GET["search"])){
    $search=$_GET["search"];
}
$categorie="tous";
if(!empty($_GET["categorie"])){
    $categorie=$_GET["categorie"];
}
$saveur="";
if(!empty($_GET["saveur"])){
    $saveur=$_GET["saveur"];
}
$allergene="";
if(!empty($_GET["allergene"])){
    $allergene=$_GET["allergene"];
}
$filtre_favoris=false;
if(!empty($_GET["favoris"])){
    $filtre_favoris=true;
}

$favoris_user=[];
if(!empty($_SESSION["email"])){
    $utilisateurs=lire_json("data/inscription.json");
    for($i=0; $i<count($utilisateurs); $i++){
        if($utilisateurs[$i]["email"]===$_SESSION["email"]){
            if(!empty($utilisateurs[$i]["favoris"])){
                $favoris_user=$utilisateurs[$i]["favoris"];
            }
            break;
        }
    }
}

$image_ids=[
    "m01"=>"img_tamada","m02"=>"img_khachapuri",
    "m03"=>"img_svanetie","m04"=>"img_veggie",
    "p01"=>"img_pkhali","p02"=>"img_badrijani",
    "p03"=>"img_salade","p04"=>"img_cornichons",
    "p05"=>"img_khinkali","p06"=>"img_mtsvadi",
    "p07"=>"img_soko","p08"=>"img_satsivi",
    "p09"=>"img_churchkhela","p10"=>"img_pelamushi",
    "p11"=>"img_nazuki","p12"=>"img_rkatsiteli",
    "p13"=>"img_limonade","p14"=>"img_tan"
];

$sections_def=[
    "menus"=>"Nos Menus",
    "entrees"=>"Entrées",
    "plats"=>"Plats",
    "desserts"=>"Desserts",
    "boissons"=>"Boissons"
];

$sections_affichees=[];
$cats_keys=["menus","entrees","plats","desserts","boissons"];
for($s=0; $s<count($cats_keys); $s++){
    $cat_key=$cats_keys[$s];
    if($categorie!="tous" && $categorie!=$cat_key){
        continue;
    }
    $items_bruts=$data[$cat_key];
    $items_filtres=[];
    for($i=0; $i<count($items_bruts); $i++){
        $item=$items_bruts[$i];
        if(!$item["disponible"]){
            continue;
        }
        if(!empty($search) && strpos(strtolower($item["nom"]), strtolower($search))===false){
            continue;
        }
        if(!empty($saveur)){
            if(empty($item["saveurs"]) || !in_array($saveur, $item["saveurs"])){
                continue;
            }
        }
        if(!empty($allergene)){
            if(!empty($item["allergenes"]) && in_array($allergene, $item["allergenes"])){
                continue;
            }
        }
        if($filtre_favoris){
            if(!in_array($item["id"], $favoris_user)){
                continue;
            }
        }
        $img_id="img_default";
        if(isset($image_ids[$item["id"]])){
            $img_id=$image_ids[$item["id"]];
        }
        $items_filtres[]=[
            "id"=>$item["id"],
            "nom"=>$item["nom"],
            "origine"=>$item["origine"],
            "description"=>$item["description"],
            "prix"=>$item["prix"],
            "img_id"=>$img_id,
            "est_favori"=>in_array($item["id"], $favoris_user)
        ];
    }
    if(count($items_filtres)>0){
        $sections_affichees[]=["titre"=>$sections_def[$cat_key],"items"=>$items_filtres];
    }
}
echo json_encode($sections_affichees, JSON_UNESCAPED_UNICODE);
?>
