<?php
session_start();

if(empty($_SESSION["email"])){
	header("location: connexion.php");
	exit;
}

// Lecture du JSON pour récupérer toutes les infos de l'utilisateur connecté
$fichier="inscription.json";
$contenu=file_get_contents($fichier);
$utilisateurs=json_decode($contenu, true);

$utilisateur=null;
if(is_array($utilisateurs)){
	for($i=0; $i<count($utilisateurs) && $utilisateur===null; $i++){
		if(isset($utilisateurs[$i]["email"]) && $utilisateurs[$i]["email"]==$_SESSION["email"]){
			$utilisateur=$utilisateurs[$i];
		}
	}
}

// Sécurité : si l'utilisateur n'est plus dans le JSON, on déconnecte
if($utilisateur===null){
	session_destroy();
	header("location: connexion.php");
	exit;
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
			<p id="profil_membre">Membre depuis <?php echo date("d/m/Y", strtotime($utilisateur["birthday"])); ?></p>
		</div>
	</div>
	<div id="header_profil_center">
		<a href="home_page.html" id="retour_accueil">← Retour à l'accueil</a>
	</div>
	<div id="header_profil_right">
		<a href="deconnexion.php" id="retour_accueil">Se déconnecter</a>
	</div>
</header>
<div id="profil_container">
	<section id="profil_infos">
		<h2 class="profil_titre">Mes Informations</h2>
		<table id="tab_profil">
			<tr>
				<td><label>Nom</label></td>
				<td><span class="profil_valeur"><?php echo $utilisateur["surname"]; ?></span></td>
				<td><span class="crayon">✏️</span></td>
			</tr>
			<tr>
				<td><label>Prénom</label></td>
				<td><span class="profil_valeur"><?php echo $utilisateur["name"]; ?></span></td>
				<td><span class="crayon">✏️</span></td>
			</tr>
			<tr>
				<td><label>Email</label></td>
				<td><span class="profil_valeur"><?php echo $utilisateur["email"]; ?></span></td>
				<td><span class="crayon">✏️</span></td>
			</tr>
			<tr>
				<td><label>Téléphone</label></td>
				<td><span class="profil_valeur"><?php echo $utilisateur["phone"]; ?></span></td>
				<td><span class="crayon">✏️</span></td>
			</tr>
			<tr>
				<td><label>Adresse</label></td>
				<td><span class="profil_valeur"><?php echo $utilisateur["numero"]." rue ".$utilisateur["rue"].", ".$utilisateur["ville"]; ?></span></td>
				<td><span class="crayon">✏️</span></td>
			</tr>
			<tr>
				<td><label>Mot de passe</label></td>
				<td><span class="profil_valeur">**************</span></td>
				<td><span class="crayon">✏️</span></td>
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
				</tr>
				<tr>
					<td colspan="4" style="text-align:center;font-style:italic;color:#C4622D;">Aucune commande pour l'instant.</td>
				</tr>
			</table>
		</div>
	</aside>
</div>
</body>
</html>
