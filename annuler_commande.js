document.addEventListener("DOMContentLoaded", function(){
    var boutons=document.querySelectorAll(".btn_annuler");
    for(var i=0; i<boutons.length; i++){
        boutons[i].addEventListener("click", function(){
            var bouton=this;
            var id=bouton.getAttribute("data-id");
            var statut_cell_id=bouton.getAttribute("data-statut-cell");
            var formData=new FormData();
            formData.append("id", id);
            fetch("annuler_commande.php", {method:"POST", body:formData})
            .then(function(reponse){
                return reponse.json();
            })
            .then(function(donnees){
                if(donnees.succes){
                    document.getElementById(statut_cell_id).textContent="Annulée";
                    bouton.style.display="none";
                }
            });
        });
    }
});
