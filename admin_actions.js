document.addEventListener("DOMContentLoaded", function(){
    var boutons=document.querySelectorAll(".btn_bloquer");
    for(var i=0; i<boutons.length; i++){
        boutons[i].addEventListener("click", function(){
            var bouton=this;
            var email=bouton.getAttribute("data-email");
            var action=bouton.getAttribute("data-action");
            var formData=new FormData();
            formData.append("email", email);
            formData.append("action", action);
            fetch("bloquer_utilisateur.php", {method:"POST", body:formData})
            .then(function(reponse){
                return reponse.json();
            })
            .then(function(donnees){
                if(donnees.succes){
                    if(action==="bloquer"){
                        bouton.textContent="Débloquer";
                        bouton.setAttribute("data-action", "debloquer");
                        bouton.classList.add("btn_bloque_actif");
                    }else{
                        bouton.textContent="Bloquer";
                        bouton.setAttribute("data-action", "bloquer");
                        bouton.classList.remove("btn_bloque_actif");
                    }
                }
            });
        });
    }
});
