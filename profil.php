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

$labels_statut=[
	"a_preparer"=>"En attente",
	"en_preparation"=>"En préparation",
	"prete"=>"Prête",
	"en_livraison"=>"En livraison",
	"livree"=>"Livrée",
	"abandonnee"=>"Annulée"
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<link rel="stylesheet" href="restaurant.css?v=3">
<script src="theme.js?v=2"></script>
<script src="modification_profil.js" defer></script>
<script src="annuler_commande.js" defer></script>
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
		<button type="button" id="theme_toggle" onclick="basculer_theme()">🌙</button>
		<a href="deconnexion.php" id="deconnexion_button">Se déconnecter</a>
	</div>
</header>
<div id="profil_container">
	<section id="profil_infos">
		<h2 class="profil_titre">Mes Informations</h2>
		<table id="tab_profil">
			<tr>
				<td><label>Nom</label></td>
				<td>
					<span class="profil_valeur" id="valeur_surname"><?php echo $utilisateur["surname"]; ?></span>
					<?php if(!$mode_admin){ ?>
					<div class="zone_edition" id="edit_surname">
						<input type="text" id="input_surname" value="<?php echo $utilisateur["surname"]; ?>"/>
						<span class="erreur_champ" id="erreur_edit_surname"></span>
						<div class="zone_edition_boutons">
							<button type="button" id="valider_surname" class="btn_profil_valider">Valider</button>
							<button type="button" id="annuler_surname" class="btn_profil_annuler">Annuler</button>
						</div>
					</div>
					<?php } ?>
				</td>
				<?php if(!$mode_admin){ ?><td><span class="crayon" id="crayon_surname">✏️</span></td><?php } ?>
			</tr>
			<tr>
				<td><label>Prénom</label></td>
				<td>
					<span class="profil_valeur" id="valeur_name"><?php echo $utilisateur["name"]; ?></span>
					<?php if(!$mode_admin){ ?>
					<div class="zone_edition" id="edit_name">
						<input type="text" id="input_name" value="<?php echo $utilisateur["name"]; ?>"/>
						<span class="erreur_champ" id="erreur_edit_name"></span>
						<div class="zone_edition_boutons">
							<button type="button" id="valider_name" class="btn_profil_valider">Valider</button>
							<button type="button" id="annuler_name" class="btn_profil_annuler">Annuler</button>
						</div>
					</div>
					<?php } ?>
				</td>
				<?php if(!$mode_admin){ ?><td><span class="crayon" id="crayon_name">✏️</span></td><?php } ?>
			</tr>
			<tr>
				<td><label>Email</label></td>
				<td>
					<span class="profil_valeur" id="valeur_email"><?php echo $utilisateur["email"]; ?></span>
					<?php if(!$mode_admin){ ?>
					<div class="zone_edition" id="edit_email">
						<input type="email" id="input_email" value="<?php echo $utilisateur["email"]; ?>"/>
						<span class="erreur_champ" id="erreur_edit_email"></span>
						<div class="zone_edition_boutons">
							<button type="button" id="valider_email" class="btn_profil_valider">Valider</button>
							<button type="button" id="annuler_email" class="btn_profil_annuler">Annuler</button>
						</div>
					</div>
					<?php } ?>
				</td>
				<?php if(!$mode_admin){ ?><td><span class="crayon" id="crayon_email">✏️</span></td><?php } ?>
			</tr>
			<tr>
				<td><label>Téléphone</label></td>
				<td>
					<span class="profil_valeur" id="valeur_phone"><?php echo $utilisateur["phone"]; ?></span>
					<?php if(!$mode_admin){ ?>
					<div class="zone_edition" id="edit_phone">
						<input type="tel" id="input_phone" value="<?php echo $utilisateur["phone"]; ?>"/>
						<span class="erreur_champ" id="erreur_edit_phone"></span>
						<div class="zone_edition_boutons">
							<button type="button" id="valider_phone" class="btn_profil_valider">Valider</button>
							<button type="button" id="annuler_phone" class="btn_profil_annuler">Annuler</button>
						</div>
					</div>
					<?php } ?>
				</td>
				<?php if(!$mode_admin){ ?><td><span class="crayon" id="crayon_phone">✏️</span></td><?php } ?>
			</tr>
			<tr>
				<td><label>Adresse</label></td>
				<td>
					<span class="profil_valeur" id="valeur_adresse"><?php echo $utilisateur["numero"]." rue ".$utilisateur["rue"].", ".$utilisateur["ville"]; ?></span>
					<?php if(!$mode_admin){ ?>
					<div class="zone_edition" id="edit_adresse">
						<input type="text" id="input_numero" value="<?php echo $utilisateur["numero"]; ?>" placeholder="N° de rue"/>
						<input type="text" id="input_rue" value="<?php echo $utilisateur["rue"]; ?>" placeholder="Rue"/>
						<input type="text" id="input_ville" value="<?php echo $utilisateur["ville"]; ?>" placeholder="Ville"/>
						<input type="text" id="input_code_postal" value="<?php echo $utilisateur["code_postal"]; ?>" placeholder="Code postal"/>
						<span class="erreur_champ" id="erreur_edit_adresse"></span>
						<div class="zone_edition_boutons">
							<button type="button" id="valider_adresse" class="btn_profil_valider">Valider</button>
							<button type="button" id="annuler_adresse" class="btn_profil_annuler">Annuler</button>
						</div>
					</div>
					<?php } ?>
				</td>
				<?php if(!$mode_admin){ ?><td><span class="crayon" id="crayon_adresse">✏️</span></td><?php } ?>
			</tr>
			<tr>
				<td><label>Mot de passe</label></td>
				<td>
					<span class="profil_valeur" id="valeur_password">**************</span>
					<?php if(!$mode_admin){ ?>
					<div class="zone_edition" id="edit_password">
						<input type="password" id="input_ancien_mdp" placeholder="Ancien mot de passe"/>
						<input type="password" id="input_nouveau_mdp" placeholder="Nouveau mot de passe"/>
						<input type="password" id="input_confirm_mdp" placeholder="Confirmer le nouveau"/>
						<span class="erreur_champ" id="erreur_edit_password"></span>
						<div class="zone_edition_boutons">
							<button type="button" id="valider_password" class="btn_profil_valider">Valider</button>
							<button type="button" id="annuler_password" class="btn_profil_annuler">Annuler</button>
						</div>
					</div>
					<?php } ?>
				</td>
				<?php if(!$mode_admin){ ?><td><span class="crayon" id="crayon_password">✏️</span></td><?php } ?>
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
					$label_statut=$cmd["statut"];
					if(isset($labels_statut[$cmd["statut"]])){
						$label_statut=$labels_statut[$cmd["statut"]];
					}
				?>
				<tr id="cmd_row_<?php echo $cmd["id"]; ?>">
					<td><?php echo date("d/m/Y", strtotime($cmd["date_commande"])); ?></td>
					<td><?php echo $resume; ?></td>
					<td><?php echo $cmd["total"]; ?> €</td>
					<td id="statut_<?php echo $cmd["id"]; ?>"><?php echo $label_statut; ?></td>
					<td>
					<?php if($cmd["statut"]=="livree" && $cmd["note"]==null && !$mode_admin){ ?>
						<a href="notation.php?id=<?php echo $cmd["id"]; ?>" class="lien_noter">Noter</a>
					<?php }elseif($cmd["note"]!=null){ ?>
						<span class="note_donnee">⭐ <?php echo $cmd["note"]["note_produit"]; ?>/6</span>
					<?php }elseif($cmd["statut"]=="a_preparer" && !$mode_admin){ ?>
						<button type="button" class="btn_annuler" data-id="<?php echo $cmd["id"]; ?>" data-row="cmd_row_<?php echo $cmd["id"]; ?>" data-statut-cell="statut_<?php echo $cmd["id"]; ?>">Annuler</button>
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
