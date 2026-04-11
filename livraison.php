<?php
session_start();
require_once "includes/session.php";
require_once "includes/json.php";
verifier_role("livreur");

$commandes=lire_json("data/commandes.json");
$utilisateurs=lire_json("data/inscription.json");

if(!empty($_POST) && !empty($_POST["id_commande"])){
	$id_commande=$_POST["id_commande"];
	$action=$_POST["action"];
	for($i=0; $i<count($commandes); $i++){
		if($commandes[$i]["id"]==$id_commande && $commandes[$i]["livreur_email"]==$_SESSION["email"]){
			if($action=="livree"){
				$commandes[$i]["statut"]="livree";
			}
			if($action=="abandonnee"){
				$commandes[$i]["statut"]="abandonnee";
			}
		}
	}
	// réecrit le fichier avec le nouveau statut de livraison
	ecrire_json("data/commandes.json", $commandes);
	header("location: livraison.php");
	exit;
}

// Chercher la commande en_livraison assignée à ce livreur
$commande=null;
for($i=0; $i<count($commandes) && $commande==null; $i++){
	if($commandes[$i]["statut"]=="en_livraison" && $commandes[$i]["livreur_email"]==$_SESSION["email"]){
		$commande=$commandes[$i];
	}
}

// Chercher les infos du client
$client=null;
if($commande!=null){
	for($i=0; $i<count($utilisateurs) && $client==null; $i++){
		if($utilisateurs[$i]["email"]==$commande["client_email"]){
			$client=$utilisateurs[$i];
		}
	}
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<link rel="stylesheet" href="restaurant.css">
<title>The Wonders of Svaneti | Livraison</title>
</head>
<body id="body_livraison">
<header id="header_livraison">
	<div id="livraison_titre">🛵 Livraison en cours</div>
	<?php if($commande!=null){ ?>
	<div id="client_id">
		<div id="livraison_commande_num">#<?php echo $commande["id"]; ?></div>
		<div id="livraison_commande_id">Id commande</div>
	</div>
	<?php } ?>
	<div id="livraison_header_right">
		<a href="home_page.html" id="livraison_retour">← Accueil</a>
		<a href="deconnexion.php" id="deconnexion_button">Se déconnecter</a>
	</div>
</header>
<div id="livraison_container">
<?php if($commande==null){ ?>
	<p id="livraison_vide">Aucune commande à livrer pour le moment.</p>
<?php }else{ ?>
	<div id="livraison_bloc_client">
		<h2 class="livraison_bloc_titre">Client</h2>
		<table class="tab_livraison">
			<tr>
				<td class="livraison_label">Nom</td>
				<td class="livraison_valeur"><?php echo $client["name"]." ".$client["surname"]; ?></td>
			</tr>
			<tr>
				<td class="livraison_label">Téléphone</td>
				<td class="livraison_valeur"><a href="tel:<?php echo $client["phone"]; ?>"><?php echo $client["phone"]; ?></a></td>
			</tr>
		</table>
	</div>
	<div id="livraison_bloc_adresse">
		<h2 class="livraison_bloc_titre">Adresse</h2>
		<table class="tab_livraison">
			<tr>
				<td class="livraison_label">Rue</td>
				<td class="livraison_valeur"><?php echo $commande["adresse_livraison"]["numero"]." ".$commande["adresse_livraison"]["rue"]; ?></td>
			</tr>
			<tr>
				<td class="livraison_label">Ville</td>
				<td class="livraison_valeur"><?php echo $commande["adresse_livraison"]["code_postal"]." ".$commande["adresse_livraison"]["ville"]; ?></td>
			</tr>
			<?php if(!empty($commande["adresse_livraison"]["etage"])){ ?>
			<tr>
				<td class="livraison_label">Étage</td>
				<td class="livraison_valeur"><?php echo $commande["adresse_livraison"]["etage"]; ?></td>
			</tr>
			<?php } ?>
			<?php if(!empty($commande["adresse_livraison"]["interphone"])){ ?>
			<tr>
				<td class="livraison_label">Interphone</td>
				<td class="livraison_valeur"><?php echo $commande["adresse_livraison"]["interphone"]; ?></td>
			</tr>
			<?php } ?>
			<?php if(!empty($commande["adresse_livraison"]["commentaire"])){ ?>
			<tr>
				<td class="livraison_label">Commentaire</td>
				<td class="livraison_valeur"><?php echo $commande["adresse_livraison"]["commentaire"]; ?></td>
			</tr>
			<?php } ?>
		</table>
		<a href="https://maps.google.com/maps?q=<?php echo $commande["adresse_livraison"]["numero"]." ".$commande["adresse_livraison"]["rue"]." ".$commande["adresse_livraison"]["ville"]; ?>" id="bouton_maps" target="_blank">📍 Ouvrir dans Maps</a>
	</div>
	<div id="livraison_bloc_commande">
		<h2 class="livraison_bloc_titre">Commande</h2>
		<table class="tab_livraison">
			<?php for($i=0; $i<count($commande["items"]); $i++){ ?>
			<tr>
				<td class="livraison_label"><?php echo $commande["items"][$i]["nom"]; ?></td>
				<td class="livraison_valeur">x<?php echo $commande["items"][$i]["quantite"]; ?></td>
			</tr>
			<?php } ?>
			<tr>
				<td class="livraison_label">Total</td>
				<td class="livraison_valeur"><?php echo $commande["total"]; ?> €</td>
			</tr>
			<tr>
				<td class="livraison_label">Paiement</td>
				<td class="livraison_valeur">Payé en ligne</td>
			</tr>
		</table>
	</div>
	<div id="livraison_boutons">
		<form action="livraison.php" method="post">
			<input type="hidden" name="id_commande" value="<?php echo $commande["id"]; ?>"/>
			<input type="hidden" name="action" value="livree"/>
			<button type="submit" id="bouton_liv">🎯 Livraison terminée</button>
		</form>
		<form action="livraison.php" method="post">
			<input type="hidden" name="id_commande" value="<?php echo $commande["id"]; ?>"/>
			<input type="hidden" name="action" value="abandonnee"/>
			<button type="submit" id="bouton_abandon">❌ Adresse introuvable</button>
		</form>
	</div>
<?php } ?>
</div>
</body>
</html>
