<?php
require_once "includes/json.php";
$erreur="";

if(!empty($_POST)){
	if(empty($_POST["name"]) || empty($_POST["surname"])){
		$erreur="Vous n'avez rien entré !!";
	}
	if(empty($erreur)){
		$name=$_POST["name"];
		$surname=$_POST["surname"];
		$long1=strlen($name);
		$long2=strlen($surname);
		if($long1>15){
			$erreur="Votre prénom est trop long !!";
		}
		if(empty($erreur) && $long2>30){
			$erreur="Votre nom de famille est trop long !!";
		}
		if(empty($erreur)){
			$name[0]=strtoupper($name[0]);
			$surname=strtoupper($surname);
			$speciaux=["-"," "];
			for($i=0; $i<$long1 && empty($erreur); $i++){
				$elmt=$name[$i];
				if(is_numeric($elmt)){
					$erreur="Votre prénom ne peut pas contenir des chiffres !!";
				}
				if(empty($erreur) && !ctype_alpha($elmt) && !in_array($elmt, $speciaux)){
					$erreur="Le prénom ne peut contenir que des lettres, tirets (-) et espaces !!";
				}
			}
			for($i=0; $i<$long2 && empty($erreur); $i++){
				$elmt=$surname[$i];
				if(is_numeric($elmt)){
					$erreur="Votre nom de famille ne peut pas contenir des chiffres !!";
				}
				if(empty($erreur) && !ctype_alpha($elmt) && !in_array($elmt, $speciaux)){
					$erreur="Le nom de famille ne peut contenir que des lettres, tirets (-) et espaces !!";
				}
			}
		}
	}
	if(empty($erreur)){
		if(empty($_POST["email"])){
			$erreur="Entrez votre email !!";
		}else{
			$email=$_POST["email"];
			if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
				$erreur="L'email est invalide !!";
			}
			if(empty($erreur)){
				$utilisateurs_tmp=lire_json("data/inscription.json");
				for($i=0; $i<count($utilisateurs_tmp) && empty($erreur); $i++){
					if(isset($utilisateurs_tmp[$i]["email"]) && $utilisateurs_tmp[$i]["email"]==$email){
						$erreur="Cet email est déjà utilisé !!";
					}
				}
			}
		}
	}
	if(empty($erreur)){
		if(empty($_POST["phone"])){
			$erreur="Entrez votre numéro de téléphone !!";
		}else{
			$phone=$_POST["phone"];
			$long_phone=strlen($phone);
			if($long_phone!=10){
				$erreur="Le numéro de téléphone doit contenir exactement 10 chiffres !!";
			}
			for($i=0; $i<$long_phone && empty($erreur); $i++){
				if(!is_numeric($phone[$i])){
					$erreur="Le numéro de téléphone ne peut contenir que des chiffres !!";
				}
			}
			if(empty($erreur) && ($phone[0] != "0" || ($phone[1] != "6" && $phone[1] != "7"))){
				$erreur="Le numéro de téléphone doit commencer par 06 ou 07 !!";
			}
		}
	}
	if(empty($erreur)){
		if(empty($_POST["password1"]) || empty($_POST["password2"])){
			$erreur="Entrez votre mot de passe !!";
		}else{
			$password1=$_POST["password1"];
			$password2=$_POST["password2"];
			if($password1 != $password2){
				$erreur="Les mots de passe ne concordent pas !!";
			}
			if(empty($erreur)){
				$long_mdp=strlen($password1);
				if($long_mdp<6){
					$erreur="Votre mot de passe doit contenir au moins 6 caractères !!";
				}
				if(empty($erreur)){
					$a_chiffre=false;
					$a_special=false;
					$spec=["!", "@", "#", "$", "%", "&", "*", "?", "+", "="];
					for($i=0; $i<$long_mdp; $i++){
						if(is_numeric($password1[$i])){ $a_chiffre=true; }
						if(in_array($password1[$i], $spec)){ $a_special=true; }
					}
					if(!$a_chiffre){
						$erreur="Le mot de passe doit contenir au moins un chiffre !!";
					}
					if(empty($erreur) && !$a_special){
						$erreur="Le mot de passe doit contenir au moins un de ces caractères spéciaux : !, @, #, $, %, &, *, ?, +, = ";
					}
				}
			}
		}
	}
	if(empty($erreur)){
		if(empty($_POST["birthday"])){
			$erreur="Entrez votre date d'anniversaire !!";
		}else{
			$birthday=$_POST["birthday"];
			$date_naissance=new DateTime($birthday);
			$auj=new DateTime();
			$difference=$auj->diff($date_naissance);
			$age=$difference->y;
			if($age<16){
				$erreur="Vous devez avoir au moins 16 ans !!";
			}
		}
	}
	if(empty($erreur)){
		if(empty($_POST["numero"])){
			$erreur="Entrez votre numéro de rue !!";
		}else{
			$numero=$_POST["numero"];
			for($i=0; $i<strlen($numero) && empty($erreur); $i++){
				if(!is_numeric($numero[$i])){
					$erreur="Le numéro de rue ne peut contenir que des chiffres !!";
				}
			}
			if(empty($erreur) && (int)$numero <= 0){
				$erreur="Le numéro de rue doit être positif !!";
			}
		}
	}
	if(empty($erreur)){
		if(empty($_POST["rue"])){
			$erreur="Entrez votre rue !!";
		}else{
			$rue=$_POST["rue"];
			if(strlen($rue)<3){
				$erreur="Le nom de la rue est trop court !!";
			}
			$speciaux_rue=["-"," "];
			for($i=0; $i<strlen($rue) && empty($erreur); $i++){
				if(!ctype_alpha($rue[$i]) && !is_numeric($rue[$i]) && !in_array($rue[$i], $speciaux_rue)){
					$erreur="La rue ne peut contenir que des lettres, chiffres, tirets (-) et espaces !!";
				}
			}
		}
	}
	if(empty($erreur)){
		if(empty($_POST["ville"])){
			$erreur="Entrez votre ville !!";
		}else{
			$ville=$_POST["ville"];
			if(strlen($ville)<2){
				$erreur="Le nom de la ville est trop court !!";
			}
			$speciaux_ville=["-"," "];
			for($i=0; $i<strlen($ville) && empty($erreur); $i++){
				if(!ctype_alpha($ville[$i]) && !in_array($ville[$i], $speciaux_ville)){
					$erreur="La ville ne peut contenir que des lettres, tirets (-) et espaces !!";
				}
			}
		}
	}
	if(empty($erreur)){
		if(empty($_POST["code_postal"])){
			$erreur="Entrez votre code postal !!";
		}else{
			$code_postal=$_POST["code_postal"];
			if(strlen($code_postal)!=5){
				$erreur="Le code postal doit contenir exactement 5 chiffres !!";
			}
			for($i=0; $i<5 && empty($erreur); $i++){
				if(!is_numeric($code_postal[$i])){
					$erreur="Le code postal ne peut contenir que des chiffres !!";
				}
			}
		}
	}

	if(empty($erreur)){
		$mdp=password_hash($password1,PASSWORD_DEFAULT);
		$utilisateurs=lire_json("data/inscription.json");
		$nouvel_utilisateur=[
			"name"=>$name,
			"surname"=>$surname,
			"email"=>$email,
			"phone"=>$phone,
			"birthday"=>$birthday,
			"numero"=>$numero,
			"rue"=>$rue,
			"ville"=>$ville,
			"code_postal"=>$code_postal,
			"password"=>$mdp,
			"role"=>"client",
			"statut"=>"actif",
			"date_inscription"=>date("Y-m-d")
		];

		$utilisateurs[]=$nouvel_utilisateur;
		ecrire_json("data/inscription.json", $utilisateurs);
		$erreur="Inscription réussie !!";
	}
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<link rel="stylesheet" href="restaurant.css?v=3">
<script src="theme.js?v=2"></script>
<script src="validation_inscription.js" defer></script>
<title>The Wonders of Svaneti | Formulaire d'inscription</title>

<?php if($erreur=="Inscription réussie !!"){ ?>
<meta http-equiv="refresh" content="2;url=home_page.php">
<?php } ?>

</head>
<body id="body_inscription">
<button type="button" id="theme_toggle" class="theme_toggle_flottant" onclick="basculer_theme()">🌙</button>
<section id="section_inscription">
	<div id="container_form">
		<h2>Formulaire d'inscription</h2>
		<?php if(!empty($erreur)){ 
			echo '<p id="erreur_inscription">'.$erreur.'</p>'; 
		} ?>
		<form action="inscription.php" method="post" id="form_inscription">
		<table id="tab_inscription">
			<tr>
				<td><label for="name">Prénom</label></td>
				<td>
					<input type="text" placeholder="Prénom" name="name" id="name"/>
					<span class="erreur_champ" id="erreur_name"></span>
				</td>
			</tr>
			<tr>
				<td><label for="surname">Nom de famille</label></td>
				<td>
					<input type="text" placeholder="Nom de famille" name="surname" id="surname"/>
					<span class="erreur_champ" id="erreur_surname"></span>
				</td>
			</tr>
			<tr>
				<td><label for="Email2">Email</label></td>
				<td>
					<input type="email" placeholder="Email" name="email" id="Email2"/>
					<span class="erreur_champ" id="erreur_email"></span>
				</td>
			</tr>
			<tr>
				<td><label for="password1">Mot de passe</label></td>
				<td>
					<input type="password" placeholder="Mot de passe" name="password1" id="password1"/>
					<button type="button" id="oeil1" class="btn_oeil">👁</button>
					<span class="erreur_champ" id="erreur_password1"></span>
				</td>
			</tr>
			<tr>
				<td><label for="password2">Confirmation mot de passe</label></td>
				<td>
					<input type="password" placeholder="Confirmer mot de passe" name="password2" id="password2"/>
					<button type="button" id="oeil2" class="btn_oeil">👁</button>
					<span class="erreur_champ" id="erreur_password2"></span>
				</td>
			</tr>
			<tr>
				<td><label for="numero">Numéro de rue</label></td>
				<td>
					<input type="text" name="numero" id="numero" placeholder="12"/>
					<span class="erreur_champ" id="erreur_numero"></span>
				</td>
			</tr>
			<tr>
				<td><label for="rue">Rue</label></td>
				<td>
					<input type="text" name="rue" id="rue" placeholder="Entrez la rue"/>
					<span class="erreur_champ" id="erreur_rue"></span>
				</td>
			</tr>
			<tr>
				<td><label for="ville">Ville</label></td>
				<td>
					<input type="text" name="ville" id="ville" placeholder="Ville"/>
					<span class="erreur_champ" id="erreur_ville"></span>
				</td>
			</tr>
			<tr>
				<td><label for="code_postal">Code postal</label></td>
				<td>
					<input type="text" name="code_postal" id="code_postal" placeholder="95000"/>
					<span class="erreur_champ" id="erreur_code_postal"></span>
				</td>
			</tr>
			<tr>
				<td><label for="phone">Téléphone</label></td>
				<td>
					<input type="tel" placeholder="0612345678" name="phone" id="phone"/>
					<span class="erreur_champ" id="erreur_phone"></span>
				</td>
			</tr>
			<tr>
				<td><label for="birthday">Date d'anniversaire</label></td>
				<td>
					<input type="date" name="birthday" id="birthday"/>
					<span class="erreur_champ" id="erreur_birthday"></span>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<button type="submit">S'inscrire</button>
				</td>
			</tr>
		</table>
		</form>
	</div>
</section>
</body>
</html>

