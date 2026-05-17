document.addEventListener("DOMContentLoaded", function(){

    function fermer_toutes(){
        document.getElementById("edit_surname").style.display="none";
        document.getElementById("edit_name").style.display="none";
        document.getElementById("edit_email").style.display="none";
        document.getElementById("edit_phone").style.display="none";
        document.getElementById("edit_adresse").style.display="none";
        document.getElementById("edit_password").style.display="none";
        document.getElementById("valeur_surname").style.display="";
        document.getElementById("valeur_name").style.display="";
        document.getElementById("valeur_email").style.display="";
        document.getElementById("valeur_phone").style.display="";
        document.getElementById("valeur_adresse").style.display="";
        document.getElementById("valeur_password").style.display="";
    }

    // -------- NOM --------

    document.getElementById("crayon_surname").addEventListener("click", function(){
        fermer_toutes();
        document.getElementById("valeur_surname").style.display="none";
        document.getElementById("edit_surname").style.display="block";
    });

    document.getElementById("annuler_surname").addEventListener("click", function(){
        document.getElementById("erreur_edit_surname").textContent="";
        fermer_toutes();
    });

    document.getElementById("valider_surname").addEventListener("click", function(){
        var val=document.getElementById("input_surname").value;
        if(val.length===0){
            document.getElementById("erreur_edit_surname").textContent="Entrez votre nom de famille.";
            return;
        }
        var formData=new FormData();
        formData.append("champ", "surname");
        formData.append("valeur", val);
        fetch("modifier_profil.php", {method:"POST", body:formData})
        .then(function(reponse){
            return reponse.json();
        })
        .then(function(donnees){
            if(donnees.succes){
                document.getElementById("valeur_surname").textContent=donnees.valeur;
                document.getElementById("erreur_edit_surname").textContent="";
                fermer_toutes();
            }else{
                document.getElementById("erreur_edit_surname").textContent=donnees.erreur;
            }
        });
    });

    // -------- PRÉNOM --------

    document.getElementById("crayon_name").addEventListener("click", function(){
        fermer_toutes();
        document.getElementById("valeur_name").style.display="none";
        document.getElementById("edit_name").style.display="block";
    });

    document.getElementById("annuler_name").addEventListener("click", function(){
        document.getElementById("erreur_edit_name").textContent="";
        fermer_toutes();
    });

    document.getElementById("valider_name").addEventListener("click", function(){
        var val=document.getElementById("input_name").value;
        if(val.length===0){
            document.getElementById("erreur_edit_name").textContent="Entrez votre prénom.";
            return;
        }
        var formData=new FormData();
        formData.append("champ", "name");
        formData.append("valeur", val);
        fetch("modifier_profil.php", {method:"POST", body:formData})
        .then(function(reponse){
            return reponse.json();
        })
        .then(function(donnees){
            if(donnees.succes){
                document.getElementById("valeur_name").textContent=donnees.valeur;
                document.getElementById("erreur_edit_name").textContent="";
                fermer_toutes();
            }else{
                document.getElementById("erreur_edit_name").textContent=donnees.erreur;
            }
        });
    });

    // -------- EMAIL --------

    document.getElementById("crayon_email").addEventListener("click", function(){
        fermer_toutes();
        document.getElementById("valeur_email").style.display="none";
        document.getElementById("edit_email").style.display="block";
    });

    document.getElementById("annuler_email").addEventListener("click", function(){
        document.getElementById("erreur_edit_email").textContent="";
        fermer_toutes();
    });

    document.getElementById("valider_email").addEventListener("click", function(){
        var val=document.getElementById("input_email").value;
        if(val.length===0){
            document.getElementById("erreur_edit_email").textContent="Entrez votre email.";
            return;
        }
        var formData=new FormData();
        formData.append("champ", "email");
        formData.append("valeur", val);
        fetch("modifier_profil.php", {method:"POST", body:formData})
        .then(function(reponse){
            return reponse.json();
        })
        .then(function(donnees){
            if(donnees.succes){
                document.getElementById("valeur_email").textContent=donnees.valeur;
                document.getElementById("erreur_edit_email").textContent="";
                fermer_toutes();
            }else{
                document.getElementById("erreur_edit_email").textContent=donnees.erreur;
            }
        });
    });

    // -------- TÉLÉPHONE --------

    document.getElementById("crayon_phone").addEventListener("click", function(){
        fermer_toutes();
        document.getElementById("valeur_phone").style.display="none";
        document.getElementById("edit_phone").style.display="block";
    });

    document.getElementById("annuler_phone").addEventListener("click", function(){
        document.getElementById("erreur_edit_phone").textContent="";
        fermer_toutes();
    });

    document.getElementById("valider_phone").addEventListener("click", function(){
        var val=document.getElementById("input_phone").value;
        if(val.length===0){
            document.getElementById("erreur_edit_phone").textContent="Entrez votre numéro de téléphone.";
            return;
        }
        var formData=new FormData();
        formData.append("champ", "phone");
        formData.append("valeur", val);
        fetch("modifier_profil.php", {method:"POST", body:formData})
        .then(function(reponse){
            return reponse.json();
        })
        .then(function(donnees){
            if(donnees.succes){
                document.getElementById("valeur_phone").textContent=donnees.valeur;
                document.getElementById("erreur_edit_phone").textContent="";
                fermer_toutes();
            }else{
                document.getElementById("erreur_edit_phone").textContent=donnees.erreur;
            }
        });
    });

    // -------- ADRESSE --------

    document.getElementById("crayon_adresse").addEventListener("click", function(){
        fermer_toutes();
        document.getElementById("valeur_adresse").style.display="none";
        document.getElementById("edit_adresse").style.display="block";
    });

    document.getElementById("annuler_adresse").addEventListener("click", function(){
        document.getElementById("erreur_edit_adresse").textContent="";
        fermer_toutes();
    });

    document.getElementById("valider_adresse").addEventListener("click", function(){
        var numero=document.getElementById("input_numero").value;
        var rue=document.getElementById("input_rue").value;
        var ville=document.getElementById("input_ville").value;
        var code_postal=document.getElementById("input_code_postal").value;
        if(numero.length===0 || rue.length===0 || ville.length===0 || code_postal.length===0){
            document.getElementById("erreur_edit_adresse").textContent="Remplissez tous les champs de l'adresse.";
            return;
        }
        var formData=new FormData();
        formData.append("champ", "adresse");
        formData.append("numero", numero);
        formData.append("rue", rue);
        formData.append("ville", ville);
        formData.append("code_postal", code_postal);
        fetch("modifier_profil.php", {method:"POST", body:formData})
        .then(function(reponse){
            return reponse.json();
        })
        .then(function(donnees){
            if(donnees.succes){
                document.getElementById("valeur_adresse").textContent=donnees.valeur;
                document.getElementById("erreur_edit_adresse").textContent="";
                fermer_toutes();
            }else{
                document.getElementById("erreur_edit_adresse").textContent=donnees.erreur;
            }
        });
    });

    // -------- MOT DE PASSE --------

    document.getElementById("crayon_password").addEventListener("click", function(){
        fermer_toutes();
        document.getElementById("valeur_password").style.display="none";
        document.getElementById("edit_password").style.display="block";
    });

    document.getElementById("annuler_password").addEventListener("click", function(){
        document.getElementById("input_ancien_mdp").value="";
        document.getElementById("input_nouveau_mdp").value="";
        document.getElementById("input_confirm_mdp").value="";
        document.getElementById("erreur_edit_password").textContent="";
        fermer_toutes();
    });

    document.getElementById("valider_password").addEventListener("click", function(){
        var ancien=document.getElementById("input_ancien_mdp").value;
        var nouveau=document.getElementById("input_nouveau_mdp").value;
        var confirmation=document.getElementById("input_confirm_mdp").value;
        if(ancien.length===0 || nouveau.length===0 || confirmation.length===0){
            document.getElementById("erreur_edit_password").textContent="Remplissez tous les champs.";
            return;
        }
        if(nouveau!==confirmation){
            document.getElementById("erreur_edit_password").textContent="Les mots de passe ne concordent pas.";
            return;
        }
        var formData=new FormData();
        formData.append("champ", "password");
        formData.append("ancien", ancien);
        formData.append("nouveau", nouveau);
        formData.append("confirmation", confirmation);
        fetch("modifier_profil.php", {method:"POST", body:formData})
        .then(function(reponse){
            return reponse.json();
        })
        .then(function(donnees){
            if(donnees.succes){
                document.getElementById("input_ancien_mdp").value="";
                document.getElementById("input_nouveau_mdp").value="";
                document.getElementById("input_confirm_mdp").value="";
                document.getElementById("erreur_edit_password").textContent="";
                fermer_toutes();
            }else{
                document.getElementById("erreur_edit_password").textContent=donnees.erreur;
            }
        });
    });
});
