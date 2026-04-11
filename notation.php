<?php
session_start();
require_once "includes/session.php";
require_once "includes/json.php";
verifier_session();

if($_SESSION["role"]!="client"){
	header("location: connexion.php");
	exit();
}

$commandes=lire_json("data/commandes.json");
$id_commande="";
if(!empty($_GET["id"])){
	$id_commande=$_GET["id"];
}

// cherchons une commande réaliser non notée
$commande=null;
for($i=0; $i<count($commandes) && $commande==null; $i++){
	if($commandes[$i]["id"]==$id_commande && $commandes[$i]["client_email"]==$_SESSION["email"] && $commandes[$i]["statut"]=="livree" && $commandes[$i]["note"]==null){
		$commande=$commandes[$i];
	}
}

$erreur="";

if($commande==null && empty($id_commande)){
	$erreur="Aucune commande à noter.";
}
if($commande==null && !empty($id_commande)){
	$erreur="Cette commande n'existe pas ou a déjà été notée.";
}

if(!empty($_POST) && $commande!=null){
	if(empty($_POST["note_produit"]) || empty($_POST["note_livraison"])){
		$erreur="Veuillez choisir une note pour les produits et la livraison !!";
	}else{
		$note_produit=(int)$_POST["note_produit"];
		$note_livraison=(int)$_POST["note_livraison"];
		if($note_produit<1 || $note_produit>6 || $note_livraison<1 || $note_livraison>6){
			$erreur="Note invalide !!";
		}else{
			$commentaire="";
			if(!empty($_POST["commentaire"])){
				$commentaire=$_POST["commentaire"];
			}
			// Sauvegardons la note dans le JSON
			for($i=0; $i<count($commandes); $i++){
				if($commandes[$i]["id"]==$commande["id"]){
					$commandes[$i]["note"]=[
						"note_produit"=>$note_produit,
						"note_livraison"=>$note_livraison,
						"commentaire"=>$commentaire
					];
				}
			}
			ecrire_json("data/commandes.json", $commandes);
			header("location: profil.php");
			exit();
		}
	}
}

$resume_commande="";
if($commande!=null){
	for($i=0; $i<count($commande["items"]); $i++){
		if($i>0){
			$resume_commande=$resume_commande.", ";
		}
		$resume_commande=$resume_commande.$commande["items"][$i]["nom"]." x".$commande["items"][$i]["quantite"];
	}
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
<link rel="stylesheet" href="restaurant.css">
<title>The Wonders of Svaneti | Notation</title>
</head>
<body id="body_notation">
<header id="header-notation">
	<a href="home_page.html" target="_self"><div class="restaurant_name">The Wonders of Svaneti</div></a>
</header>
<section id="section_notation">
	<div id="container_notation">
		<h2>Noter votre commande</h2>
		<?php if(!empty($erreur)){ ?>
			<p id="erreur_inscription"><?php echo $erreur; ?></p>
		<?php }else{ ?>
		<p id="notation-commande">Commande du <?php echo date("d/m/Y", strtotime($commande["date_commande"])); ?> — <?php echo $resume_commande; ?></p>
		<form action="notation.php?id=<?php echo $id_commande; ?>" method="post" id="form_notation">
		<table id="tab_notation">
			<tr>
				<td><label for="note_produit">Qualité des produits</label></td>
				<td>
					<select name="note_produit" id="note_produit">
						<option value="">-- Choisir une note --</option>
						<option value="6">🌟🌟🌟🌟🌟🌟 5+ — Parfait</option>
						<option value="5">⭐⭐⭐⭐⭐ 5 — Excellent</option>
						<option value="4">⭐⭐⭐⭐ 4 — Bon</option>
						<option value="3">⭐⭐⭐ 3 — Correct</option>
						<option value="2">⭐⭐ 2 — Mauvais</option>
						<option value="1">⭐ 1 — Très mauvais</option>
					</select>
				</td>
			</tr>
			<tr>
				<td><label for="note_livraison">Qualité de la livraison</label></td>
				<td>
					<select name="note_livraison" id="note_livraison">
						<option value="">-- Choisir une note --</option>
						<option value="6">🌟🌟🌟🌟🌟🌟 5+ — Parfait</option>
						<option value="5">⭐⭐⭐⭐⭐ 5 — Excellent</option>
						<option value="4">⭐⭐⭐⭐ 4 — Bon</option>
						<option value="3">⭐⭐⭐ 3 — Correct</option>
						<option value="2">⭐⭐ 2 — Mauvais</option>
						<option value="1">⭐ 1 — Très mauvais</option>
					</select>
				</td>
			</tr>
			<tr>
				<td><label for="commentaire">Commentaire</label></td>
				<td><textarea name="commentaire" id="commentaire" placeholder="Votre avis..."></textarea></td>
			</tr>
			<tr>
				<td colspan="2">
					<button type="submit" id="button_notation">Envoyer ma note</button>
				</td>
			</tr>
		</table>
		</form>
		<?php } ?>
	</div>
</section>
</body>
</html>
