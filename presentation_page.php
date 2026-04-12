<?php
session_start();
require_once "includes/json.php";

$data=lire_json("data/menus.json");

$search="";
if(!empty($_GET["search"])){
    $search=$_GET["search"];
}
$categorie="tous";
if(!empty($_GET["categorie"])){
    $categorie=$_GET["categorie"];
}
$saveur="";
if(!empty($_GET["saveur"])){
    $saveur=$_GET["saveur"];
}
$allergene="";
if(!empty($_GET["allergene"])){
    $allergene=$_GET["allergene"];
}

$image_ids=[
    "m01"=>"img_tamada","m02"=>"img_khachapuri",
    "m03"=>"img_svanetie","m04"=>"img_veggie",
    "p01"=>"img_pkhali","p02"=>"img_badrijani",
    "p03"=>"img_salade","p04"=>"img_cornichons",
    "p05"=>"img_khinkali","p06"=>"img_mtsvadi",
    "p07"=>"img_soko","p08"=>"img_satsivi",
    "p09"=>"img_churchkhela","p10"=>"img_pelamushi",
    "p11"=>"img_nazuki","p12"=>"img_rkatsiteli",
    "p13"=>"img_limonade","p14"=>"img_tan"
];

$sections_def=[
    "menus"=>"Nos Menus",
    "entrees"=>"Entrées",
    "plats"=>"Plats",
    "desserts"=>"Desserts",
    "boissons"=>"Boissons"
];

$sections_affichees=[];
$cats_keys=["menus","entrees","plats","desserts","boissons"];
for($s=0; $s<count($cats_keys); $s++){
    $cat_key=$cats_keys[$s];
    if($categorie!="tous" && $categorie!=$cat_key){
        continue;
    }
    $items_bruts=$data[$cat_key];
    $items_filtres=[];
    for($i=0; $i<count($items_bruts); $i++){
        $item=$items_bruts[$i];
        if(!$item["disponible"]){
            continue;
        }
        if(!empty($search) && strpos(strtolower($item["nom"]), strtolower($search))===false){
            continue;
        }
        if(!empty($saveur)){
            if(empty($item["saveurs"]) || !in_array($saveur, $item["saveurs"])){
                continue;
            }
        }
        if(!empty($allergene)){
            if(!empty($item["allergenes"]) && in_array($allergene, $item["allergenes"])){
                continue;
            }
        }
        $items_filtres[]=$item;
    }
    if(count($items_filtres)>0){
        $sections_affichees[]=["titre"=>$sections_def[$cat_key],"items"=>$items_filtres];
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<link rel="stylesheet" href="restaurant.css">
<title>The Wonders of Svaneti | Nos Plats</title>
</head>
<body id="body_produits">
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
				<?php if($categorie!="tous"){ ?>
				<input type="hidden" name="categorie" value="<?php echo $categorie; ?>"/>
				<?php } ?>
				<?php if(!empty($saveur)){ ?>
				<input type="hidden" name="saveur" value="<?php echo $saveur; ?>"/>
				<?php } ?>
				<?php if(!empty($allergene)){ ?>
				<input type="hidden" name="allergene" value="<?php echo $allergene; ?>"/>
				<?php } ?>
				<input type="search" placeholder="Rechercher un plat..." id="search_bar_admin" name="search" value="<?php echo $search; ?>"/>
				<button type="submit" id="admin_button">Rechercher</button>
			</form>
		</li>
	</ul>
</nav>
</header>
<div id="barre_carte">
	<div id="barre_carte_texte">
		<h1 id="produits_titre">La Carte</h1>
		<p id="produits_sous_titre">Voyagez au cœur du Caucase, une assiette à la fois</p>
	</div>
</div>
<div id="produits_container">
	<aside id="colone_gauche">
		<div class="filtre_bloc">
			<h3 class="filtre_titre">Catégorie</h3>
			<ul class="filtre_liste">
				<a href="presentation_page.php<?php if(!empty($search)) echo "?search=".$search; ?>">
					<li class="<?php if($categorie=="tous") echo "filtre_item_actif"; else echo "filtre_item"; ?>">Tous</li>
				</a>
				<a href="presentation_page.php?categorie=menus<?php if(!empty($search)) echo "&search=".$search; ?>">
					<li class="<?php if($categorie=="menus") echo "filtre_item_actif"; else echo "filtre_item"; ?>">Menus</li>
				</a>
				<a href="presentation_page.php?categorie=entrees<?php if(!empty($search)) echo "&search=".$search; ?>">
					<li class="<?php if($categorie=="entrees") echo "filtre_item_actif"; else echo "filtre_item"; ?>">Entrées</li>
				</a>
				<a href="presentation_page.php?categorie=plats<?php if(!empty($search)) echo "&search=".$search; ?>">
					<li class="<?php if($categorie=="plats") echo "filtre_item_actif"; else echo "filtre_item"; ?>">Plats</li>
				</a>
				<a href="presentation_page.php?categorie=desserts<?php if(!empty($search)) echo "&search=".$search; ?>">
					<li class="<?php if($categorie=="desserts") echo "filtre_item_actif"; else echo "filtre_item"; ?>">Desserts</li>
				</a>
				<a href="presentation_page.php?categorie=boissons<?php if(!empty($search)) echo "&search=".$search; ?>">
					<li class="<?php if($categorie=="boissons") echo "filtre_item_actif"; else echo "filtre_item"; ?>">Boissons</li>
				</a>
			</ul>
		</div>
		<div class="filtre_bloc">
			<h3 class="filtre_titre">Saveurs</h3>
			<ul class="filtre_liste">
				<a href="presentation_page.php?saveur=epice<?php if(!empty($search)) echo "&search=".$search; ?>">
					<li class="<?php if($saveur=="epice") echo "filtre_item_actif"; else echo "filtre_item"; ?>">Épicé</li>
				</a>
				<a href="presentation_page.php?saveur=fume<?php if(!empty($search)) echo "&search=".$search; ?>">
					<li class="<?php if($saveur=="fume") echo "filtre_item_actif"; else echo "filtre_item"; ?>">Fumé</li>
				</a>
				<a href="presentation_page.php?saveur=vegetarien<?php if(!empty($search)) echo "&search=".$search; ?>">
					<li class="<?php if($saveur=="vegetarien") echo "filtre_item_actif"; else echo "filtre_item"; ?>">Végétarien</li>
				</a>
				<a href="presentation_page.php?saveur=fruite<?php if(!empty($search)) echo "&search=".$search; ?>">
					<li class="<?php if($saveur=="fruite") echo "filtre_item_actif"; else echo "filtre_item"; ?>">Fruité</li>
				</a>
				<a href="presentation_page.php?saveur=grille<?php if(!empty($search)) echo "&search=".$search; ?>">
					<li class="<?php if($saveur=="grille") echo "filtre_item_actif"; else echo "filtre_item"; ?>">Grillé</li>
				</a>
			</ul>
		</div>
		<div class="filtre_bloc">
			<h3 class="filtre_titre">Allergènes</h3>
			<ul class="filtre_liste">
				<a href="presentation_page.php?allergene=noix<?php if(!empty($search)) echo "&search=".$search; ?>">
					<li class="<?php if($allergene=="noix") echo "filtre_item_actif"; else echo "filtre_item"; ?>">Sans noix</li>
				</a>
				<a href="presentation_page.php?allergene=gluten<?php if(!empty($search)) echo "&search=".$search; ?>">
					<li class="<?php if($allergene=="gluten") echo "filtre_item_actif"; else echo "filtre_item"; ?>">Sans gluten</li>
				</a>
				<a href="presentation_page.php?allergene=lactose<?php if(!empty($search)) echo "&search=".$search; ?>">
					<li class="<?php if($allergene=="lactose") echo "filtre_item_actif"; else echo "filtre_item"; ?>">Sans lactose</li>
				</a>
				<a href="presentation_page.php?allergene=oeuf<?php if(!empty($search)) echo "&search=".$search; ?>">
					<li class="<?php if($allergene=="oeuf") echo "filtre_item_actif"; else echo "filtre_item"; ?>">Sans œuf</li>
				</a>
			</ul>
		</div>
	</aside>
	<main id="produits_grille">
		<?php if(count($sections_affichees)==0){ ?>
		<p id="produits_aucun">Aucun plat ne correspond à votre recherche.</p>
		<?php } ?>
		<?php for($s=0; $s<count($sections_affichees); $s++){
			$section=$sections_affichees[$s];
		?>
		<section class="produits_section">
			<h2 class="section_titre"><?php echo $section["titre"]; ?></h2>
			<div class="cards_container">
				<?php for($i=0; $i<count($section["items"]); $i++){
					$item=$section["items"][$i];
					$img_id=isset($image_ids[$item["id"]]) ? $image_ids[$item["id"]] : "img_default";
				?>
				<div class="card_plat">
					<div class="card_img" id="<?php echo $img_id; ?>">Image Not Available</div>
					<div class="card_body">
						<h3 class="card_nom"><?php echo $item["nom"]; ?></h3>
						<p class="card_origine">🇬🇪 <?php echo $item["origine"]; ?></p>
						<p class="card_desc"><?php echo $item["description"]; ?></p>
						<div class="card_footer">
							<span class="card_prix"><?php echo $item["prix"]; ?> €</span>
							<form action="panier.php" method="post" class="form_commander">
								<input type="hidden" name="action" value="ajouter"/>
								<input type="hidden" name="id" value="<?php echo $item["id"]; ?>"/>
								<button type="submit" class="card_bouton">Commander</button>
							</form>
						</div>
					</div>
				</div>
				<?php } ?>
			</div>
		</section>
		<?php } ?>
	</main>
</div>
</body>
</html>
