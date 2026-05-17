document.addEventListener("DOMContentLoaded", function(){
    document.getElementById("commandes_container").addEventListener("click", function(evenement){
        var cible=evenement.target;
        if(!cible.classList.contains("btn_statut")){
            return;
        }
        var bouton=cible;
        var id=bouton.getAttribute("data-id");
        var statut=bouton.getAttribute("data-statut");
        var row_id=bouton.getAttribute("data-row");
        var compteur_id=bouton.getAttribute("data-compteur");
        var formData=new FormData();
        formData.append("id", id);
        formData.append("statut", statut);
        fetch("changer_statut_commande.php", {method:"POST", body:formData})
        .then(function(reponse){
            return reponse.json();
        })
        .then(function(donnees){
            if(donnees.succes){
                var row=document.getElementById(row_id);
                var cellules=row.querySelectorAll("td");
                var num_commande=cellules[0].innerHTML;
                var client=cellules[1].textContent;
                var plats=cellules[2].textContent;
                var heure=cellules[3].textContent;
                var total=cellules[4].textContent;
                row.style.display="none";
                var compteur=document.getElementById(compteur_id);
                if(compteur!==null){
                    compteur.textContent=parseInt(compteur.textContent)-1;
                }
                if(statut==="en_preparation"){
                    var html="";
                    html=html+'<tr id="row_ep_'+id+'">';
                    html=html+'<td>'+num_commande+'</td>';
                    html=html+'<td>'+client+'</td>';
                    html=html+'<td>'+plats+'</td>';
                    html=html+'<td>'+heure+'</td>';
                    html=html+'<td>'+total+'</td>';
                    html=html+'<td><button type="button" class="btn_statut btn_prete" data-id="'+id+'" data-statut="prete" data-row="row_ep_'+id+'" data-compteur="compteur_en_preparation">Prête</button></td>';
                    html=html+'</tr>';
                    document.getElementById("table_en_preparation").querySelector("tbody").insertAdjacentHTML("beforeend", html);
                    var compteur_ep=document.getElementById("compteur_en_preparation");
                    compteur_ep.textContent=parseInt(compteur_ep.textContent)+1;
                }
                if(statut==="prete"){
                    var html="";
                    html=html+'<tr>';
                    html=html+'<td>'+num_commande+'</td>';
                    html=html+'<td>'+client+'</td>';
                    html=html+'<td>'+plats+'</td>';
                    html=html+'<td>'+heure+'</td>';
                    html=html+'<td>'+total+'</td>';
                    html=html+'<td colspan="2">';
                    html=html+'<form action="commandes.php" method="post" class="form_livraison">';
                    html=html+'<input type="hidden" name="commande_id" value="'+id+'"/>';
                    html=html+'<select name="livreur_email" class="select_livreur" required>';
                    html=html+'<option value="">-- Choisir un livreur --</option>';
                    for(var k=0; k<livreurs_data.length; k++){
                        html=html+'<option value="'+livreurs_data[k].email+'">'+livreurs_data[k].name+' '+livreurs_data[k].surname+'</option>';
                    }
                    html=html+'</select>';
                    html=html+'<button type="submit" class="bouton_liv">Passer en livraison</button>';
                    html=html+'</form>';
                    html=html+'</td>';
                    html=html+'</tr>';
                    document.getElementById("table_prete").querySelector("tbody").insertAdjacentHTML("beforeend", html);
                }
            }
        });
    });
});
