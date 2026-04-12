<?php
session_start();
require_once "includes/session.php";
require_once "includes/json.php";

if(!isset($_SESSION["panier"])){
    $_SESSION["panier"]=[];
}

$menus_data=lire_json("data/menus.json");

$tous_items=[];
$cats=["entrees","plats","desserts","boissons","menus"];
for($i=0; $i<count($cats); $i++){
    $cat=$cats[$i];
    if(!empty($menus_data[$cat])){
        for($j=0; $j<count($menus_data[$cat]); $j++){
            $item=$menus_data[$cat][$j];
            $tous_items[$item["id"]]=$item;
        }
    }
}

if(!empty($_POST["action"])){
    $action=$_POST["action"];
    if($action=="ajouter" && !empty($_POST["id"])){
        $id=$_POST["id"];
        if(isset($tous_items[$id])){
            $trouve=false;
            for($i=0; $i<count($_SESSION["panier"]); $i++){
                if($_SESSION["panier"][$i]["id"]==$id){
                    $_SESSION["panier"][$i]["quantite"]++;
                    $trouve=true;
                }
            }
            if(!$trouve){
                $_SESSION["panier"][]=[
                    "id"=>$id,
                    "nom"=>$tous_items[$id]["nom"],
                    "prix_unitaire"=>$tous_items[$id]["prix"],
                    "quantite"=>1
                ];
            }
        }
        header("location: panier.php");
        exit();
    }
    if($action=="supprimer" && !empty($_POST["id"])){
        $id=$_POST["id"];
        $nouveau=[];
        // recréons" le tableau sans l'element
        for($i=0; $i<count($_SESSION["panier"]); $i++){
            if($_SESSION["panier"][$i]["id"]!=$id){
                $nouveau[]=$_SESSION["panier"][$i];
            }
        }
        $_SESSION["panier"]=$nouveau;
        header("location: panier.php");
        exit();
    }

    if($action=="modifier" && !empty($_POST["id"])){
        $id=$_POST["id"];
        $qte=intval($_POST["qte"]);
        if($qte<=0){
            $nouveau=[];
            for($i=0; $i<count($_SESSION["panier"]); $i++){
                if($_SESSION["panier"][$i]["id"]!=$id){
                    $nouveau[]=$_SESSION["panier"][$i];
                }
            }
            $_SESSION["panier"]=$nouveau;
        }else{
            for($i=0; $i<count($_SESSION["panier"]); $i++){
                if($_SESSION["panier"][$i]["id"]==$id){
                    $_SESSION["panier"][$i]["quantite"]=$qte;
                }
            }
        }
        header("location: panier.php");
        exit();
    }

    if($action=="vider"){
        $_SESSION["panier"]=[];
        header("location: panier.php");
        exit();
    }

    if($action=="valider" && !empty($_POST["type_commande"])){
        if(empty($_SESSION["email"])){
            $_SESSION["apres_connexion"]=$_SERVER["REQUEST_URI"];
            header("location: connexion.php");
            exit();
        }
        $type=$_POST["type_commande"];
        $quand=$_POST["quand"];
        $date_prevue=null;
        if($quand=="planifiee" && !empty($_POST["date_prevue"])){
            $date_prevue=$_POST["date_prevue"];
        }

        $total=0;
        for($i=0; $i<count($_SESSION["panier"]); $i++){
            $total=$total+($_SESSION["panier"][$i]["prix_unitaire"]*$_SESSION["panier"][$i]["quantite"]);
        }
        // id transaction unique pour cy bank
        $transaction="t".time().rand(100,999);

        $utilisateurs=lire_json("data/inscription.json");
        $utilisateur=null;
        for($i=0; $i<count($utilisateurs) && $utilisateur==null; $i++){
            if($utilisateurs[$i]["email"]==$_SESSION["email"]){
                $utilisateur=$utilisateurs[$i];
            }
        }

        $_SESSION["commande_en_cours"]=[
            "transaction"=>$transaction,
            "panier"=>$_SESSION["panier"],
            "total"=>$total,
            "type"=>$type,
            "date_prevue"=>$date_prevue,
            "adresse"=>$utilisateur
        ];
        // infos pour cy bank
        $vendeur="SUPMECA_E";
        $api_key=substr(md5($vendeur), 1, 15);
        $montant=intval(round($total*100));
        $retour="http://".$_SERVER["HTTP_HOST"]."/Projet_restaurant/retour_paiement.php";
        $control=md5($api_key."#".$transaction."#".$montant."#".$vendeur."#".$retour."#");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<link rel="stylesheet" href="restaurant.css">
<title>The Wonders of Svaneti | Paiement</title>
</head>
<body id="body_panier">
<div id="panier_redirect">
    <p id="panier_redirect_texte">Paiement sécurisé</p>
    <form action="https://www.plateforme-smc.fr/cybank/index.php" method="post">
        <input type="hidden" name="transaction" value="<?php echo $transaction; ?>"/>
        <input type="hidden" name="montant" value="<?php echo $montant; ?>"/>
        <input type="hidden" name="vendeur" value="<?php echo $vendeur; ?>"/>
        <input type="hidden" name="retour" value="<?php echo $retour; ?>"/>
        <input type="hidden" name="control" value="<?php echo $control; ?>"/>
        <button type="submit" id="bouton_payer">Confirmer le paiement</button>
    </form>
</div>
</body>
</html>
<?php
        exit();
    }
}

$total=0;
for($i=0; $i<count($_SESSION["panier"]); $i++){
    $total=$total+($_SESSION["panier"][$i]["prix_unitaire"]*$_SESSION["panier"][$i]["quantite"]);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<link rel="stylesheet" href="restaurant.css">
<title>The Wonders of Svaneti | Panier</title>
</head>
<body id="body_panier">
<header>
<nav id="navbar">
    <ul id="nav_left">
        <a href="presentation_page.php" target="_self"><li class="nav_item" id="indentation_left">Menus</li></a>
        <a href="commandes.php" target="_self"><li class="nav_item">Commandes</li></a>
        <a href="livraison.php" target="_self"><li class="nav_item">Livraison</li></a>
        <a href="admin.php" target="_self"><li class="nav_item">Admin</li></a>
    </ul>
    <a href="home_page.html" target="_self"><div class="restaurant_name">The Wonders of Svaneti</div></a>
    <ul id="nav_right">
        <a href="inscription.php" target="_self"><li class="nav_item" id="indentation_right">Inscription</li></a>
        <a href="profil.php" target="_self"><li class="nav_item">Profil</li></a>
        <a href="connexion.php" target="_self"><li class="nav_item">Connexion</li></a>
    </ul>
</nav>
</header>
<div id="panier_container">
    <h1 id="panier_titre">Votre Panier</h1>
    <?php if(count($_SESSION["panier"])==0){ ?>
    <div id="panier_vide">
        <p>Votre panier est vide.</p>
        <a href="presentation_page.php" id="panier_retour_menu">← Voir la carte</a>
    </div>
    <?php }else{ ?>
    <a href="presentation_page.php" id="panier_retour_menu">← Continuer mes achats</a>
    <div id="panier_corps">
        <div id="panier_gauche">
            <table id="tab_panier">
                <tr class="tab_panier_header">
                    <th>Plat</th>
                    <th>Prix unitaire</th>
                    <th>Quantité</th>
                    <th>Sous-total</th>
                    <th></th>
                </tr>
                <?php for($i=0; $i<count($_SESSION["panier"]); $i++){
                    $item=$_SESSION["panier"][$i];
                    $sous_total=$item["prix_unitaire"]*$item["quantite"];
                ?>
                <tr>
                    <td class="panier_nom"><?php echo $item["nom"]; ?></td>
                    <td class="panier_prix"><?php echo $item["prix_unitaire"]; ?> €</td>
                    <td class="panier_qte">
                        <form action="panier.php" method="post" class="form_qte">
                            <input type="hidden" name="action" value="modifier"/>
                            <input type="hidden" name="id" value="<?php echo $item["id"]; ?>"/>
                            <input type="number" name="qte" value="<?php echo $item["quantite"]; ?>" min="0" class="input_qte"/>
                            <button type="submit" class="bouton_maj_qte">↺</button>
                        </form>
                    </td>
                    <td class="panier_sous_total"><?php echo $sous_total; ?> €</td>
                    <td>
                        <form action="panier.php" method="post">
                            <input type="hidden" name="action" value="supprimer"/>
                            <input type="hidden" name="id" value="<?php echo $item["id"]; ?>"/>
                            <button type="submit" class="bouton_suppr_panier">✕</button>
                        </form>
                    </td>
                </tr>
                <?php } ?>
            </table>
            <form action="panier.php" method="post">
                <input type="hidden" name="action" value="vider"/>
                <button type="submit" id="bouton_vider_panier">Vider le panier</button>
            </form>
        </div>
        <aside id="panier_droite">
            <h2 id="panier_recap_titre">Récapitulatif</h2>
            <div id="panier_total_ligne">
                <span>Total</span>
                <span id="panier_total_valeur"><?php echo $total; ?> €</span>
            </div>
            <form action="panier.php" method="post" id="form_valider">
                <input type="hidden" name="action" value="valider"/>
                <div class="panier_choix_bloc">
                    <p class="panier_choix_titre">Mode de réception :</p>
                    <label class="panier_radio_label">
                        <input type="radio" name="type_commande" value="livraison" checked/> Livraison à domicile
                    </label>
                    <label class="panier_radio_label">
                        <input type="radio" name="type_commande" value="emporter"/> À emporter
                    </label>
                </div>
                <div class="panier_choix_bloc">
                    <p class="panier_choix_titre">Quand :</p>
                    <label class="panier_radio_label">
                        <input type="radio" name="quand" value="immediat" checked/> Dès que possible
                    </label>
                    <label class="panier_radio_label">
                        <input type="radio" name="quand" value="planifiee"/> Programmer pour plus tard
                    </label>
                    <input type="datetime-local" name="date_prevue" id="input_date_prevue" class="panier_date_input"/>
                </div>
                <button type="submit" id="bouton_payer">Payer <?php echo $total; ?> €</button>
            </form>
        </aside>
    </div>
    <?php } ?>
</div>
</body>
</html>
