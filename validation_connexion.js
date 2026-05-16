document.addEventListener("DOMContentLoaded", function(){
    var ids=["Email","password"];
    for(var i=0; i<ids.length; i++){
        var champ=document.getElementById(ids[i]);
        if(champ===null){
            continue;
        }
        if(ids[i]==="password"){
            ajouterOeil(champ);
        }
        var span=document.createElement("span");
        span.className="erreur_champ";
        span.id="err_"+ids[i];
        champ.parentNode.appendChild(span);
    }

    document.getElementById("Email").addEventListener("input", function(){ valider_email(); });
    document.getElementById("password").addEventListener("input", function(){ valider_password(); });

    document.getElementById("form_connexion").addEventListener("submit", function(e){
        var ok=true;
        if(!valider_email()){
            ok=false;
        }
        if(!valider_password()){
            ok=false;
        }
        if(!ok){
            e.preventDefault();
        }
    });
});

function afficher_erreur(id, msg){
    var span=document.getElementById("err_"+id);
    if(span===null){
        return;
    }
    span.textContent=msg;
}

function effacer_erreur(id){
    var span=document.getElementById("err_"+id);
    if(span===null){
        return;
    }
    span.textContent="";
}

function ajouterOeil(champ){
    var btn=document.createElement("button");
    btn.type="button";
    btn.textContent="👁";
    btn.className="btn_oeil";
    btn.addEventListener("click", function(){
        if(champ.type==="password"){
            champ.type="text";
        }else{
            champ.type="password";
        }
    });
    champ.parentNode.appendChild(btn);
}

function valider_email(){
    var val=document.getElementById("Email").value;
    var regex=/^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if(val.length===0){
        afficher_erreur("Email", "Entrez votre email.");
        return false;
    }
    if(!regex.test(val)){
        afficher_erreur("Email", "L'email est invalide.");
        return false;
    }
    effacer_erreur("Email");
    return true;
}

function valider_password(){
    var val=document.getElementById("password").value;
    if(val.length===0){
        afficher_erreur("password", "Entrez votre mot de passe.");
        return false;
    }
    effacer_erreur("password");
    return true;
}
