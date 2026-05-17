document.addEventListener("DOMContentLoaded", function(){
    document.getElementById("Email").addEventListener("input", function(){
        valider_email();
    });
    document.getElementById("password").addEventListener("input", function(){
        valider_password();
    });

    document.getElementById("oeil").addEventListener("click", function(){
        var champ=document.getElementById("password");
        if(champ.type==="password"){
            champ.type="text";
        }else{
            champ.type="password";
        }
    });

    document.getElementById("form_connexion").addEventListener("submit", function(evenement){
        var ok=true;
        if(!valider_email()){
            ok=false;
        }
        if(!valider_password()){
            ok=false;
        }
        if(!ok){
            evenement.preventDefault();
        }
    });
});

function valider_email(){
    var val=document.getElementById("Email").value;
    if(val.length===0){
        document.getElementById("erreur_email").textContent="Entrez votre email.";
        return false;
    }
    var pos_arobase=val.indexOf("@");
    if(pos_arobase<=0){
        document.getElementById("erreur_email").textContent="L'email doit contenir un @.";
        return false;
    }
    var pos_point=val.indexOf(".", pos_arobase);
    if(pos_point===-1 || pos_point>=val.length-1){
        document.getElementById("erreur_email").textContent="L'email doit contenir un point après @.";
        return false;
    }
    document.getElementById("erreur_email").textContent="";
    return true;
}

function valider_password(){
    var val=document.getElementById("password").value;
    if(val.length===0){
        document.getElementById("erreur_password").textContent="Entrez votre mot de passe.";
        return false;
    }
    document.getElementById("erreur_password").textContent="";
    return true;
}
