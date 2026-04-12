<?php
session_start();
require_once "includes/session.php";
require_once "includes/json.php";
verifier_session();

$utilisateurs=lire_json("data/inscription.json");

$mode_admin=false;
$email_cible=$_SESSION["email"];
if($_SESSION["role"]=="admin" && !empty($_GET["email"])){
	$mode_admin=true;
	$email_cible=$_GET["email"];
}

$utilisateur=null;
for($i=0; $i<count($utilisateurs) && $utilisateur==null; $i++){
	if($utilisateurs[$i]["email"]==$email_cible){
		$utilisateur=$utilisateurs[$i];
	}
}

if($utilisateur==null){
	if($mode_admin){
		header("location: admin.php");
		exit();
	}
	session_destroy();
	header("location: connexion.php");
	exit();
}

$toutes_commandes=lire_json("data/commandes.json");
$mes_commandes=[];
for($i=0; $i<count($toutes_commandes); $i++){
	if($toutes_commandes[$i]["client_email"]==$email_cible){
		$mes_commandes[]=$toutes_commandes[$i];
	}
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<link rel="stylesheet" href="restaurant.css">
<title>The Wonders of Svaneti | Profil</title>
</head>
<body id="body_profil">
<header id="header_profil">
	<div id="header_profil_left">
		<div id="avatar_img"></div>
		<div id="header_profil_text">
			<h1 id="profil_nom"><?php echo $utilisateur["name"]." ".$utilisateur["surname"]; ?></h1>
			<p id="profil_membre">Membre depuis <?php echo date("d/m/Y", strtotime($utilisateur["date_inscription"])); ?></p>
		</div>
	</div>
	<div id="header_profil_center">
		<?php if($mode_admin){ ?>
		<a href="admin.php" id="retour_accueil">← Retour à l'admin</a>
		<?php }else{ ?>
		<a href="home_page.php" id="retour_accueil">← Retour à l'accueil</a>
		<?php } ?>
	</div>
	<div id="header_profil_right">
		<a href="deconnexion.php" id="deconnexion_button">Se déconnecter</a>
	</div>
</header>
<div id="profil_container">
	<section id="profil_infos">
		<h2 class="profil_titre">Mes Informations</h2>
		<table id="tab_profil">
			<tr>
				<td><label>Nom</label></td>
				<td><span class="profil_valeur"><?php echo $utilisateur["surname"]; ?></span></td>
				<?php if(!$mode_admin){ ?><td><span class="crayon">✏️</span></td><?php } ?>
			</tr>
			<tr>
				<td><label>Prénom</label></td>
				<td><span class="profil_valeur"><?php echo $utilisateur["name"]; ?></span></td>
				<?php if(!$mode_admin){ ?><td><span class="crayon">✏️</span></td><?php } ?>
			</tr>
			<tr>
				<td><label>Email</label></td>
				<td><span class="profil_valeur"><?php echo $utilisateur["email"]; ?></span></td>
				<?php if(!$mode_admin){ ?><td><span class="crayon">✏️</span></td><?php } ?>
			</tr>
			<tr>
				<td><label>Téléphone</label></td>
				<td><span class="profil_valeur"><?php echo $utilisateur["phone"]; ?></span></td>
				<?php if(!$mode_admin){ ?><td><span class="crayon">✏️</span></td><?php } ?>
			</tr>
			<tr>
				<td><label>Adresse</label></td>
				<td><span class="profil_valeur"><?php echo $utilisateur["numero"]." rue ".$utilisateur["rue"].", ".$utilisateur["ville"]; ?></span></td>
				<?php if(!$mode_admin){ ?><td><span class="crayon">✏️</span></td><?php } ?>
			</tr>
			<tr>
				<td><label>Mot de passe</label></td>
				<td><span class="profil_valeur">**************</span></td>
				<?php if(!$mode_admin){ ?><td><span class="crayon">✏️</span></td><?php } ?>
			</tr>
		</table>
	</section>
	<aside id="profil_aside">
		<div id="fidele_bloc">
			<h2 class="profil_titre">Compte Fidélité</h2>
			<div id="fidele_points">
				<span id="points_valeur">0</span>
				<span id="points_fidele">points</span>
			</div>
			<div id="fidele_barre_fond">
				<div id="fidele_barre_remplie" style="width:0%;"></div>
			</div>
			<p id="fidele_info">Passez votre première commande pour gagner des points !</p>
		</div>
		<div id="historique_bloc">
			<h2 class="profil_titre">Historique des commandes</h2>
			<table id="tab_historique">
				<tr class="tab_header">
					<th>Date</th>
					<th>Commande</th>
					<th>Total</th>
					<th>Statut</th>
					<th>Note</th>
				</tr>
				<?php if(count($mes_commandes)==0){ ?>
				<tr>
					<td colspan="5" style="text-align:center;font-style:italic;color:#C4622D;">Aucune commande pour l'instant.</td>
				</tr>
				<?php } ?>
				<?php for($i=0; $i<count($mes_commandes); $i++){
					$cmd=$mes_commandes[$i];
					$resume="";
					for($j=0; $j<count($cmd["items"]); $j++){
						if($j>0){
							$resume=$resume.", ";
						}
						$resume=$resume.$cmd["items"][$j]["nom"]." x".$cmd["items"][$j]["quantite"];
					}
				?>
				<tr>
					<td><?php echo date("d/m/Y", strtotime($cmd["date_commande"])); ?></td>
					<td><?php echo $resume; ?></td>
					<td><?php echo $cmd["total"]; ?> €</td>
					<td><?php echo $cmd["statut"]; ?></td>
					<td>
					<?php if($cmd["statut"]=="livree" && $cmd["note"]==null && !$mode_admin){ ?>
						<a href="notation.php?id=<?php echo $cmd["id"]; ?>" class="lien_noter">Noter</a>
					<?php }elseif($cmd["note"]!=null){ ?>
						<span class="note_donnee">⭐ <?php echo $cmd["note"]["note_produit"]; ?>/6</span>
					<?php }else{ ?>
						—
					<?php } ?>
					</td>
				</tr>
				<?php } ?>
			</table>
		</div>
	</aside>
</div>
</body>
</html>

