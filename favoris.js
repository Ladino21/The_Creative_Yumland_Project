document.addEventListener("DOMContentLoaded", function(){
    document.addEventListener("click", function(evenement){
        var cible=evenement.target;
        if(!cible.classList.contains("favoris")){
            return;
        }
        var id=cible.getAttribute("data-id");
        var formData=new FormData();
        formData.append("id", id);
        fetch("favoris.php", {method:"POST", body:formData})
        .then(function(reponse){
            return reponse.json();
        })
        .then(function(donnees){
            if(donnees.succes){
                if(donnees.favori){
                    cible.textContent="★";
                }else{
                    cible.textContent="☆";
                }
            }
        });
    });
});
