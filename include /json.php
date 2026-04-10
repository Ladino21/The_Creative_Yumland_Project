<?php
function lire_json($fichier){
    $contenu=file_get_contents($fichier);
    $data=json_decode($contenu, true);
    if(!is_array($data)){
        return [];
    }
    return $data;
}
function ecrire_json($fichier,$data){
    file_put_contents($fichier, json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
}
?>
