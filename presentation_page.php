<?php
session_start();
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
		<a href="commandes.php" target="_self"><li class="nav_item">Commandes</li></a>
		<a href="livraison.php" target="_self"><li class="nav_item">Livraison</li></a>
		<a href="admin.php" target="_self"><li class="nav_item">Admin</li></a>
		<a href="notation.php" target="_self"><li class="nav_item">Notation</li></a>
	</ul>
	<a href="home_page.php" target="_self"><div class="restaurant_name">The Wonders of Svaneti</div></a>
	<ul id="nav_right">
		<a href="inscription.php" target="_self"><li class="nav_item" id="indentation_right">Inscription</li></a>
		<a href="profil.php" target="_self"><li class="nav_item">Profil</li></a>
		<a href="connexion.php" target="_self"><li class="nav_item">Connexion</li></a>
		<li class="nav_item_special">
			<form id="search_form" action="#" method="get">
				<input type="search" placeholder="Rechercher un plat..." id="search_bar_admin" name="search"/>
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
				<li class="filtre_item_actif">Tous</li>
				<li class="filtre_item">Menus</li>
				<li class="filtre_item">Entrées</li>
				<li class="filtre_item">Plats</li>
				<li class="filtre_item">Desserts</li>
				<li class="filtre_item">Boissons</li>
			</ul>
		</div>
		<div class="filtre_bloc">
			<h3 class="filtre_titre">Saveurs</h3>
			<ul class="filtre_liste">
				<li class="filtre_item">Épicé</li>
				<li class="filtre_item">Fumé</li>
				<li class="filtre_item">Végétarien</li>
				<li class="filtre_item">Fruité</li>
				<li class="filtre_item">Grillé</li>
			</ul>
		</div>
		<div class="filtre_bloc">
			<h3 class="filtre_titre">Allergènes</h3>
			<ul class="filtre_liste">
				<li class="filtre_item">Sans noix</li>
				<li class="filtre_item">Sans gluten</li>
				<li class="filtre_item">Sans lactose</li>
				<li class="filtre_item">Sans œuf</li>
			</ul>
		</div>
	</aside>
	<main id="produits_grille">
		<section class="produits_section">
			<h2 class="section_titre">Nos Menus</h2>
			<div class="cards_container">
				<div class="card_plat">
					<div class="card_img" id="img_tamada">Image Not Available</div>
					<div class="card_body">
						<h3 class="card_nom">Le Tamada</h3>
						<p class="card_origine">🇬🇪 Géorgie — Menu Dégustation</p>
						<p class="card_desc">Le festin du maître de cérémonie. Pkhali, Khinkali, Mtsvadi, Churchkhela et vin Rkatsiteli.</p>
						<div class="card_footer">
							<span class="card_prix">45,00 €</span>
							<form action="panier.php" method="post" class="form_commander">
								<input type="hidden" name="action" value="ajouter"/>
								<input type="hidden" name="id" value="m01"/>
								<button type="submit" class="card_bouton">Commander</button>
							</form>
						</div>
					</div>
				</div>
				<div class="card_plat">
					<div class="card_img" id="img_khachapuri">Image Not Available</div>
					<div class="card_body">
						<h3 class="card_nom">Menu Khachapuri</h3>
						<p class="card_origine">🇬🇪 Adjarie & Mingrélie</p>
						<p class="card_desc">Voyage autour du fromage géorgien. Deux Khachapuri régionaux, sulguni et Kada.</p>
						<div class="card_footer">
							<span class="card_prix">32,00 €</span>
							<form action="panier.php" method="post" class="form_commander">
								<input type="hidden" name="action" value="ajouter"/>
								<input type="hidden" name="id" value="m02"/>
								<button type="submit" class="card_bouton">Commander</button>
							</form>
						</div>
					</div>
				</div>
				<div class="card_plat">
					<div class="card_img" id="img_svanetie">Image Not Available</div>
					<div class="card_body">
						<h3 class="card_nom">Menu Svanétie</h3>
						<p class="card_origine">🇬🇪 Montagnes de Svanétie</p>
						<p class="card_desc">Les saveurs des sommets. Salade géorgienne, Chakapuli, Satsivi et Pelamushi.</p>
						<div class="card_footer">
							<span class="card_prix">38,00 €</span>
							<form action="panier.php" method="post" class="form_commander">
								<input type="hidden" name="action" value="ajouter"/>
								<input type="hidden" name="id" value="m03"/>
								<button type="submit" class="card_bouton">Commander</button>
							</form>
						</div>
					</div>
				</div>
				<div class="card_plat">
					<div class="card_img" id="img_veggie">Image Not Available</div>
					<div class="card_body">
						<h3 class="card_nom">Menu Végétarien</h3>
						<p class="card_origine">🇬🇪 L'Esprit du Caucase</p>
						<p class="card_desc">Pkhali trio, Badrijani, Lobiani, Ajapsandali et Nazuki.</p>
						<div class="card_footer">
							<span class="card_prix">28,00 €</span>
							<form action="panier.php" method="post" class="form_commander">
								<input type="hidden" name="action" value="ajouter"/>
								<input type="hidden" name="id" value="m04"/>
								<button type="submit" class="card_bouton">Commander</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</section>
		<section class="produits_section">
			<h2 class="section_titre">Entrées</h2>
			<div class="cards_container">
				<div class="card_plat">
					<div class="card_img" id="img_pkhali">Image Not Available</div>
					<div class="card_body">
						<h3 class="card_nom">Pkhali</h3>
						<p class="card_origine">🇬🇪 Géorgie — Végétarien</p>
						<p class="card_desc">Amuse-bouche aux légumes et noix du Caucase. Épinards, betterave ou haricots verts.</p>
						<div class="card_footer">
							<span class="card_prix">8,00 €</span>
							<form action="panier.php" method="post" class="form_commander">
								<input type="hidden" name="action" value="ajouter"/>
								<input type="hidden" name="id" value="p01"/>
								<button type="submit" class="card_bouton">Commander</button>
							</form>
						</div>
					</div>
				</div>
				<div class="card_plat">
					<div class="card_img" id="img_badrijani">Image Not Available</div>
					<div class="card_body">
						<h3 class="card_nom">Badrijani</h3>
						<p class="card_origine">🇬🇪 Géorgie — Végétarien</p>
						<p class="card_desc">Aubergines grillées fourrées à la pâte de noix, grenade et coriandre fraîche.</p>
						<div class="card_footer">
							<span class="card_prix">9,00 €</span>
							<form action="panier.php" method="post" class="form_commander">
								<input type="hidden" name="action" value="ajouter"/>
								<input type="hidden" name="id" value="p02"/>
								<button type="submit" class="card_bouton">Commander</button>
							</form>
						</div>
					</div>
				</div>
				<div class="card_plat">
					<div class="card_img" id="img_salade">Image Not Available</div>
					<div class="card_body">
						<h3 class="card_nom">Salade Géorgienne</h3>
						<p class="card_origine">🇬🇪 Géorgie</p>
						<p class="card_desc">Tomates, concombres, oignons rouges, coriandre et aneth, huile de noix.</p>
						<div class="card_footer">
							<span class="card_prix">7,00 €</span>
							<form action="panier.php" method="post" class="form_commander">
								<input type="hidden" name="action" value="ajouter"/>
								<input type="hidden" name="id" value="p03"/>
								<button type="submit" class="card_bouton">Commander</button>
							</form>
						</div>
					</div>
				</div>
				<div class="card_plat">
					<div class="card_img" id="img_cornichons">Image Not Available</div>
					<div class="card_body">
						<h3 class="card_nom">Cornichons Géorgiens</h3>
						<p class="card_origine">🇬🇪 Géorgie</p>
						<p class="card_desc">Cornichons marinés aux herbes fraîches, ail et épices khmeli suneli.</p>
						<div class="card_footer">
							<span class="card_prix">5,00 €</span>
							<form action="panier.php" method="post" class="form_commander">
								<input type="hidden" name="action" value="ajouter"/>
								<input type="hidden" name="id" value="p04"/>
								<button type="submit" class="card_bouton">Commander</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</section>
		<section class="produits_section">
			<h2 class="section_titre">Plats</h2>
			<div class="cards_container">
				<div class="card_plat">
					<div class="card_img" id="img_khinkali">Image Not Available</div>
					<div class="card_body">
						<h3 class="card_nom">Khinkali</h3>
						<p class="card_origine">🇬🇪 Svanétie & Kakhétie</p>
						<p class="card_desc">Raviolis géorgiens juteux farcis à la viande épicée, bouillon intérieur. Se mange à la main.</p>
						<div class="card_footer">
							<span class="card_prix">14,00 €</span>
							<form action="panier.php" method="post" class="form_commander">
								<input type="hidden" name="action" value="ajouter"/>
								<input type="hidden" name="id" value="p05"/>
								<button type="submit" class="card_bouton">Commander</button>
							</form>
						</div>
					</div>
				</div>
				<div class="card_plat">
					<div class="card_img" id="img_mtsvadi">Image Not Available</div>
					<div class="card_body">
						<h3 class="card_nom">Mtsvadi</h3>
						<p class="card_origine">🇬🇪 Géorgie</p>
						<p class="card_desc">Brochettes de viande grillée au feu de bois, marinées à la sauce tkemali de prune sauvage.</p>
						<div class="card_footer">
							<span class="card_prix">16,00 €</span>
							<form action="panier.php" method="post" class="form_commander">
								<input type="hidden" name="action" value="ajouter"/>
								<input type="hidden" name="id" value="p06"/>
								<button type="submit" class="card_bouton">Commander</button>
							</form>
						</div>
					</div>
				</div>
				<div class="card_plat">
					<div class="card_img" id="img_soko">Image Not Available</div>
					<div class="card_body">
						<h3 class="card_nom">Soko Kecze</h3>
						<p class="card_origine">🇬🇪 Géorgie</p>
						<p class="card_desc">Champignons sauvages cuits au four dans une poêle en argile avec sulguni fondu et beurre.</p>
						<div class="card_footer">
							<span class="card_prix">13,00 €</span>
							<form action="panier.php" method="post" class="form_commander">
								<input type="hidden" name="action" value="ajouter"/>
								<input type="hidden" name="id" value="p07"/>
								<button type="submit" class="card_bouton">Commander</button>
							</form>
						</div>
					</div>
				</div>
				<div class="card_plat">
					<div class="card_img" id="img_satsivi">Image Not Available</div>
					<div class="card_body">
						<h3 class="card_nom">Satsivi</h3>
						<p class="card_origine">🇬🇪 Géorgie occidentale</p>
						<p class="card_desc">Volaille froide nappée d'une sauce onctueuse aux noix, safran d'Iran et épices khmeli suneli.</p>
						<div class="card_footer">
							<span class="card_prix">17,00 €</span>
							<form action="panier.php" method="post" class="form_commander">
								<input type="hidden" name="action" value="ajouter"/>
								<input type="hidden" name="id" value="p08"/>
								<button type="submit" class="card_bouton">Commander</button>
							</form>
						</div>
					</div>
				</div>
	
			</div>
		</section>
		<section class="produits_section">
			<h2 class="section_titre">Desserts</h2>
			<div class="cards_container">
				<div class="card_plat">
					<div class="card_img" id="img_churchkhela">Image Not Available</div>
					<div class="card_body">
						<h3 class="card_nom">Churchkhela</h3>
						<p class="card_origine">🇬🇪 Kakhétie</p>
						<p class="card_desc">Noix et noisettes enfilées sur une ficelle et enrobées de jus de raisin concentré séché.</p>
						<div class="card_footer">
							<span class="card_prix">6,00 €</span>
							<form action="panier.php" method="post" class="form_commander">
								<input type="hidden" name="action" value="ajouter"/>
								<input type="hidden" name="id" value="p09"/>
								<button type="submit" class="card_bouton">Commander</button>
							</form>
						</div>
					</div>
				</div>
				<div class="card_plat">
					<div class="card_img" id="img_pelamushi">Image Not Available</div>
					<div class="card_body">
						<h3 class="card_nom">Pelamushi</h3>
						<p class="card_origine">🇬🇪 Géorgie</p>
						<p class="card_desc">Pudding traditionnel à base de jus de raisin rouge et farine de maïs, parsemé de noix.</p>
						<div class="card_footer">
							<span class="card_prix">7,00 €</span>
							<form action="panier.php" method="post" class="form_commander">
								<input type="hidden" name="action" value="ajouter"/>
								<input type="hidden" name="id" value="p10"/>
								<button type="submit" class="card_bouton">Commander</button>
							</form>
						</div>
					</div>
				</div>
				<div class="card_plat">
					<div class="card_img" id="img_nazuki">Image Not Available</div>
					<div class="card_body">
						<h3 class="card_nom">Nazuki</h3>
						<p class="card_origine">🇬🇪 Géorgie</p>
						<p class="card_desc">Pain sucré et épicé à la cannelle, cardamome et clou de girofle. Douceur des fêtes géorgiennes.</p>
						<div class="card_footer">
							<span class="card_prix">5,50 €</span>
							<form action="panier.php" method="post" class="form_commander">
								<input type="hidden" name="action" value="ajouter"/>
								<input type="hidden" name="id" value="p11"/>
								<button type="submit" class="card_bouton">Commander</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</section>
		<section class="produits_section">
			<h2 class="section_titre">Boissons</h2>
			<div class="cards_container">
				<div class="card_plat">
					<div class="card_img" id="img_rkatsiteli">Image Not Available</div>
					<div class="card_body">
						<h3 class="card_nom">Rkatsiteli Ambré</h3>
						<p class="card_origine">🇬🇪 Kakhétie — Vin de 8000 ans</p>
						<p class="card_desc">Vin ambré géorgien fermenté en amphore Qvevri. Notes de noix, miel et abricot sec.</p>
						<div class="card_footer">
							<span class="card_prix">9,00 €</span>
							<form action="panier.php" method="post" class="form_commander">
								<input type="hidden" name="action" value="ajouter"/>
								<input type="hidden" name="id" value="p12"/>
								<button type="submit" class="card_bouton">Commander</button>
							</form>
						</div>
					</div>
				</div>
				<div class="card_plat">
					<div class="card_img" id="img_limonade">Image Not Available</div>
					<div class="card_body">
						<h3 class="card_nom">Limonade Lagidze</h3>
						<p class="card_origine">🇬🇪 Tbilissi depuis 1887</p>
						<p class="card_desc">Limonade artisanale géorgienne aux sirops naturels : poire, crème de vanille ou estragon.</p>
						<div class="card_footer">
							<span class="card_prix">4,50 €</span>
							<form action="panier.php" method="post" class="form_commander">
								<input type="hidden" name="action" value="ajouter"/>
								<input type="hidden" name="id" value="p13"/>
								<button type="submit" class="card_bouton">Commander</button>
							</form>
						</div>
					</div>
				</div>
				<div class="card_plat">
					<div class="card_img" id="img_tan">Image Not Available</div>
					<div class="card_body">
						<h3 class="card_nom">Tan</h3>
						<p class="card_origine">🇦🇲 Arménie — voisin caucasien</p>
						<p class="card_desc">Boisson traditionnelle à base de yaourt fermenté, eau gazeuse et sel. Rafraîchissante et légère.</p>
						<div class="card_footer">
							<span class="card_prix">3,50 €</span>
							<form action="panier.php" method="post" class="form_commander">
								<input type="hidden" name="action" value="ajouter"/>
								<input type="hidden" name="id" value="p14"/>
								<button type="submit" class="card_bouton">Commander</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</section>
	</main>
</div>
</body>
</html>
