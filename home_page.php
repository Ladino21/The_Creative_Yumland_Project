<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
<title>The wonders of Svaneti | page d'accueil</title>
<link rel="stylesheet" href="restaurant.css">
</head>
<body id="home_page">
<header>
<nav id="navbar">
	<ul id="nav_left">
		<a href="presentation_page.php" target="_self"><li class="nav_item" id="indentation_left">Menus</li></a>
		<?php if(!empty($_SESSION["role"]) && $_SESSION["role"]=="restaurateur"){ ?>
		<a href="commandes.php" target="_self"><li class="nav_item">Commandes</li></a>
		<?php } ?>
		<?php if(!empty($_SESSION["role"]) && $_SESSION["role"]=="livreur"){ ?>
		<a href="livraison.php" target="_self"><li class="nav_item">Livraison</li></a>
		<?php } ?>
		<?php if(!empty($_SESSION["role"]) && $_SESSION["role"]=="admin"){ ?>
		<a href="admin.php" target="_self"><li class="nav_item">Admin</li></a>
		<?php } ?>
		<?php if(!empty($_SESSION["role"]) && $_SESSION["role"]=="client"){ ?>
		<a href="notation.php" target="_self"><li class="nav_item">Notation</li></a>
		<?php } ?>
	</ul>
	<a href="home_page.php" target="_self"><div class="restaurant_name">The Wonders of Svaneti</div></a>
	<ul id="nav_right">
		<?php if(!empty($_SESSION["email"])){ ?>
		<a href="profil.php" target="_self"><li class="nav_item" id="indentation_right">Profil</li></a>
		<a href="deconnexion.php" id="deconnexion_button">Se déconnecter</a>
		<?php }else{ ?>
		<a href="inscription.php" target="_self"><li class="nav_item" id="indentation_right">Inscription</li></a>
		<a href="connexion.php" target="_self"><li class="nav_item">Connexion</li></a>
		<?php } ?>
		<li class="nav_item_special">
			<form id="search_form" action="presentation_page.php" method="get">
				<input type="search" placeholder="Rechercher un plat..." id="search_bar_admin" name="search"/>
				<button type="submit" id="admin_button">Rechercher</button>
			</form>
		</li>
	</ul>
</nav>
</header>
<div id="content_container">
<section id="hero_image">
</section>
	<aside id="presentation">
		<div class="presentation_title">L'esprit de la Svanétie dans votre assiette</div>
		<div id="presentation_text">Au Wonders of Svaneti, nous rendons hommage à la Géorgie authentique, berceau d'une des plus anciennes et riches traditions culinaires au monde.
Notre cuisine 100% géorgienne puise ses racines dans les recettes transmises de génération en génération. Épices parfumées, fromages généreux, herbes fraîches et viandes tendres s'unissent pour créer des plats qui réchauffent le cœur et l'âme.
Que vous découvriez pour la première fois les saveurs du Caucase ou que vous cherchiez à retrouver les goûts de votre enfance, notre table est un lieu de partage où chaque repas devient une célébration.
		</div>
	</aside>
</div>
<div id="bonus_accueil">
<div class="presentation_title">L'instant Culture</div>
<div id="culture_accueil">
La Géorgie, nichée entre les montagnes du Caucase et les rivages de la mer Noire, est l'une des civilisations les plus anciennes du monde. Berceau de la vigne il y a plus de 8 000 ans, ce pays a forgé au fil des siècles une culture du partage et de la table comme nulle part ailleurs.
Au cœur de cette culture se trouve le Supra — le grand banquet géorgien — une célébration de la vie, de l'amitié et de la mémoire. Chaque Supra est guidé par le Tamada, le maître de cérémonie, dont le rôle est de porter les toasts et de conduire les convives à travers un voyage de mots, d'émotions et de saveurs. Car en Géorgie, un toast n'est pas une simple formule — c'est un art, un poème, un hommage à la vie.
Chez The Wonders of Svaneti, nous perpétuons cet esprit. Chaque assiette raconte une région, chaque épice évoque une tradition. Laissez-vous guider.
<div id="config"><a href="presentation_page.php" target="_self" id="redirection_menu">Menus</a></div>
</div>
</div>
</body>
</html>
