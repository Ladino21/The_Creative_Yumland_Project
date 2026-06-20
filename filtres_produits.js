document.addEventListener("DOMContentLoaded", function(){
    var categorie_courante="tous";
    var saveur_courante="";
    var allergene_courante="";
    var search_courant="";
    var favoris_courant=false;

    var liens_filtre=document.querySelectorAll("[data-champ]");
    for(var i=0; i<liens_filtre.length; i++){
        var li=liens_filtre[i].querySelector("li");
        if(li.className==="filtre_item_actif"){
            var champ=liens_filtre[i].getAttribute("data-champ");
            var valeur=liens_filtre[i].getAttribute("data-valeur");
            if(champ==="categorie"){
                categorie_courante=valeur;
            }
            if(champ==="saveur"){
                saveur_courante=valeur;
            }
            if(champ==="allergene"){
                allergene_courante=valeur;
            }
        }
    }

    search_courant=document.getElementById("search_bar_admin").value;
    for(var i=0; i<liens_filtre.length; i++){
        liens_filtre[i].addEventListener("click", function(evenement){
            evenement.preventDefault();
            var champ=this.getAttribute("data-champ");
            var valeur=this.getAttribute("data-valeur");
            if(champ==="categorie"){
                categorie_courante=valeur;
            }
            if(champ==="saveur"){
                if(saveur_courante===valeur){
                    saveur_courante="";
                }else{
                    saveur_courante=valeur;
                }
            }
            if(champ==="allergene"){
                if(allergene_courante===valeur){
                    allergene_courante="";
                }else{
                    allergene_courante=valeur;
                }
            }
            if(champ==="favoris"){
                favoris_courant=!favoris_courant;
            }
            charger_produits();
        });
    }
    var input_search=document.getElementById("search_bar_admin");
    var liste_suggestions=document.getElementById("liste_suggestions");

    input_search.addEventListener("input", function(){
        var val=input_search.value;
        if(val.length<2){
            liste_suggestions.style.display="none";
            liste_suggestions.innerHTML="";
            return;
        }
        fetch("suggestions_produits.php?search="+encodeURIComponent(val))
        .then(function(reponse){
            return reponse.json();
        })
        .then(function(suggestions){
            if(suggestions.length===0){
                liste_suggestions.style.display="none";
                liste_suggestions.innerHTML="";
                return;
            }
            var html="";
            for(var i=0; i<suggestions.length; i++){
                html=html+'<li class="suggestion_item">'+suggestions[i]+'</li>';
            }
            liste_suggestions.innerHTML=html;
            liste_suggestions.style.display="block";
            var items=liste_suggestions.querySelectorAll(".suggestion_item");
            for(var i=0; i<items.length; i++){
                items[i].addEventListener("click", function(){
                    input_search.value=this.textContent;
                    liste_suggestions.style.display="none";
                    liste_suggestions.innerHTML="";
                    search_courant=input_search.value;
                    charger_produits();
                });
            }
        });
    });

    document.addEventListener("click", function(evenement){
        if(evenement.target!==input_search){
            liste_suggestions.style.display="none";
        }
    });
    document.getElementById("search_form").addEventListener("submit", function(evenement){
        evenement.preventDefault();
        liste_suggestions.style.display="none";
        search_courant=input_search.value;
        charger_produits();
    });

    function charger_produits(){
        var url="filtrer_produits.php?categorie="+categorie_courante;
        if(saveur_courante.length>0){
            url=url+"&saveur="+saveur_courante;
        }
        if(allergene_courante.length>0){
            url=url+"&allergene="+allergene_courante;
        }
        if(search_courant.length>0){
            url=url+"&search="+encodeURIComponent(search_courant);
        }
        if(favoris_courant){
            url=url+"&favoris=1";
        }
        fetch(url)
        .then(function(reponse){
            return reponse.json();
        })
        .then(function(sections){
            afficher_produits(sections);
            mettre_a_jour_filtres();
        });
    }

    function afficher_produits(sections){
        var grille=document.getElementById("produits_grille");
        if(sections.length===0){
            grille.innerHTML='<p id="produits_aucun">Aucun plat ne correspond à votre recherche.</p>';
            return;
        }
        var html="";
        for(var s=0; s<sections.length; s++){
            var section=sections[s];
            html=html+'<section class="produits_section">';
            html=html+'<h2 class="section_titre">'+section.titre+'</h2>';
            html=html+'<div class="cards_container">';
            for(var i=0; i<section.items.length; i++){
                var item=section.items[i];
                html=html+'<div class="card_plat">';
                html=html+'<div class="card_img" id="'+item.img_id+'"></div>';
                html=html+'<div class="card_body">';
                var etoile="";
                if(item.est_favori!==undefined){
                    if(item.est_favori){
                        etoile='<span class="favoris" data-id="'+item.id+'">★</span>';
                    }else{
                        etoile='<span class="favoris" data-id="'+item.id+'">☆</span>';
                    }
                }
                html=html+'<h3 class="card_nom">'+item.nom+etoile+'</h3>';
                html=html+'<p class="card_origine">🇬🇪 '+item.origine+'</p>';
                html=html+'<p class="card_desc">'+item.description+'</p>';
                html=html+'<div class="card_footer">';
                html=html+'<span class="card_prix">'+item.prix+' €</span>';
                html=html+'<form action="panier.php" method="post" class="form_commander">';
                html=html+'<input type="hidden" name="action" value="ajouter"/>';
                html=html+'<input type="hidden" name="id" value="'+item.id+'"/>';
                html=html+'<button type="submit" class="card_bouton">Commander</button>';
                html=html+'</form>';
                html=html+'</div>';
                html=html+'</div>';
                html=html+'</div>';
            }
            html=html+'</div>';
            html=html+'</section>';
        }
        grille.innerHTML=html;
    }

    function mettre_a_jour_filtres(){
        for(var i=0; i<liens_filtre.length; i++){
            var champ=liens_filtre[i].getAttribute("data-champ");
            var valeur=liens_filtre[i].getAttribute("data-valeur");
            var li=liens_filtre[i].querySelector("li");
            var actif=false;
            if(champ==="categorie" && valeur===categorie_courante){
                actif=true;
            }
            if(champ==="saveur" && valeur===saveur_courante){
                actif=true;
            }
            if(champ==="allergene" && valeur===allergene_courante){
                actif=true;
            }
            if(champ==="favoris" && favoris_courant){
                actif=true;
            }
            if(actif){
                li.className="filtre_item_actif";
            }else{
                li.className="filtre_item";
            }
        }
    }
});
