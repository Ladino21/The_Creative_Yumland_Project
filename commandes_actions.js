document.addEventListener("DOMContentLoaded", function(){
    var boutons=document.querySelectorAll(".btn_statut");
    for(var i=0; i<boutons.length; i++){
        boutons[i].addEventListener("click", function(){
            var bouton=this;
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
                    row.style.display="none";
                    var compteur=document.getElementById(compteur_id);
                    if(compteur!==null){
                        var valeur=parseInt(compteur.textContent);
                        compteur.textContent=valeur-1;
                    }
                }
            });
        });
    }
});
