<?php
session_start();
$erreur="";

if(!empty($_SESSION["email"])){
	$role=$_SESSION["role"];
	if($role=="admin"){
		header("location: admin.php");
		exit();
	}
	if($role=="restaurateur"){
		header("location: commandes.php");
		exit();
	}
	if($role=="livreur"){
		header("location: livraison.php");
		exit();
	}
	header("location: profil.php");
	exit();
}

$email_cookie="";
if(isset($_COOKIE["cy_email"])){
	$email_cookie=$_COOKIE["cy_email"];
}

// tourne en premier lors d'une première connexion
if(!empty($_POST)){
	// email
	if(empty($_POST["email"])){
		$erreur="Entrez votre email !!";
	}else{
		$email=$_POST["email"];
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
			$erreur="L'email est invalide !!";
		}
	}
	// mot de passe
	if(empty($erreur)){
		if(empty($_POST["password"])){
			$erreur="Entrez votre mot de passe !!";
		}
	}

	// vérification des identifiants (emails) dans le fichier JSON
	if(empty($erreur)){
		$password=$_POST["password"];
		$fichier="inscription.json";
		$contenu=file_get_contents($fichier);
		$utilisateurs=json_decode($contenu, true);

		$utilisateur_trouve=null;
		if(is_array($utilisateurs)){
			for($i=0; $i<count($utilisateurs) && $utilisateur_trouve===null; $i++){
				if(isset($utilisateurs[$i]["email"]) && $utilisateurs[$i]["email"]==$email){
					$utilisateur_trouve=$utilisateurs[$i];
				}
			}
		}

		// Message identique pour email inconnu et mauvais mdp — sécurité
		if($utilisateur_trouve===null || !password_verify($password, $utilisateur_trouve["password"])){
			$erreur="Email ou mot de passe incorrect !!";
		}else{
			// Connexion réussie — on stocke les infos en session
			$_SESSION["email"]=$utilisateur_trouve["email"];
			$_SESSION["name"]=$utilisateur_trouve["name"];
			$_SESSION["surname"]=$utilisateur_trouve["surname"];
			$_SESSION["role"]=$utilisateur_trouve["role"];

			// Cookie "se souvenir de moi" (30 jours)
			if(!empty($_POST["remind"])){
				setcookie("cy_email", $email, time()+30*24*3600, "/");
			}else{
				setcookie("cy_email", "", time()-3600, "/");
			}

			// Redirection selon le rôle
			$role=$utilisateur_trouve["role"];
			if($role=="admin"){
				header("location: admin.php");
				exit;
			}
			if($role=="restaurateur"){
				header("location: commandes.php");
				exit;
			}
			if($role=="livreur"){
				header("location: livraison.php");
				exit;
			}
			header("location: profil.php");
			exit;
		}
	}
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<link rel="stylesheet" href="restaurant.css">
<title>The Wonders of Svaneti | Formulaire de connexion</title>
</head>
<body id="body_connexion">
<section id="section_form">
	<div id="container">
		<h2>Formulaire de connexion</h2>
		<?php if(!empty($erreur)){
			echo '<p id="erreur_connexion">'.$erreur.'</p>';
		} ?>
		<form action="connexion.php" method="post" id="form_connexion">
		<table id="tab_connexion">
			<tr>
				<td><label for="Email">Email</label></td>
				<td><input type="email" placeholder="Email" name="email" id="Email" value="<?php echo $email_cookie; ?>"/></td>
			</tr>
			<tr>
				<td><label for="password">Mot de passe</label></td>
				<td><input type="password" placeholder="Mot de passe" name="password" id="password"/></td>
			</tr>
			<tr>
				<td><label for="remind_me">Se souvenir de moi</label></td>
				<td><input type="checkbox" name="remind" id="remind_me" <?php if(!empty($email_cookie)){echo "checked";} ?>/></td>
			</tr>
			<tr id="test">
				<td colspan="2" class="container_button">
					<a href="inscription.php" target="_self" id="inscription">Je ne possède pas de compte</a>
				</td>
			</tr>
			<tr>
				<td colspan="2" class="container_button">
					<button type="submit" id="connexion_button">Se connecter</button>
				</td>
			</tr>
		</table>
		</form>
	</div>
</section>
</body>
</html>


