document.addEventListener("DOMContentLoaded", function(){
    document.getElementById("name").addEventListener("input", function(){
        valider_name();
    });
    document.getElementById("surname").addEventListener("input", function(){
        valider_surname();
    });
    document.getElementById("Email2").addEventListener("input", function(){
        valider_email();
    });
    document.getElementById("password1").addEventListener("input", function(){
        valider_password1();
        valider_password2();
    });
    document.getElementById("password2").addEventListener("input", function(){
        valider_password2();
    });
    document.getElementById("numero").addEventListener("input", function(){
        valider_numero();
    });
    document.getElementById("rue").addEventListener("input", function(){
        valider_rue();
    });
    document.getElementById("ville").addEventListener("input", function(){
        valider_ville();
    });
    document.getElementById("code_postal").addEventListener("input", function(){
        valider_code_postal();
    });
    document.getElementById("phone").addEventListener("input", function(){
        valider_phone();
    });
    document.getElementById("birthday").addEventListener("change", function(){
        valider_birthday();
    });

    document.getElementById("oeil1").addEventListener("click", function(){
        var champ=document.getElementById("password1");
        if(champ.type==="password"){
            champ.type="text";
        }else{
            champ.type="password";
        }
    });

    document.getElementById("oeil2").addEventListener("click", function(){
        var champ=document.getElementById("password2");
        if(champ.type==="password"){
            champ.type="text";
        }else{
            champ.type="password";
        }
    });

    document.getElementById("form_inscription").addEventListener("submit", function(evenement){
        var ok=true;
        if(!valider_name()){
            ok=false;
        }
        if(!valider_surname()){
            ok=false;
        }
        if(!valider_email()){
            ok=false;
        }
        if(!valider_password1()){
            ok=false;
        }
        if(!valider_password2()){
            ok=false;
        }
        if(!valider_numero()){
            ok=false;
        }
        if(!valider_rue()){
            ok=false;
        }
        if(!valider_ville()){
            ok=false;
        }
        if(!valider_code_postal()){
            ok=false;
        }
        if(!valider_phone()){
            ok=false;
        }
        if(!valider_birthday()){
            ok=false;
        }
        if(!ok){
            evenement.preventDefault();
        }
    });
});

function est_lettre(c){
    var lettres="abcdefghijklmnopqrstuvwxyzàâäéèêëîïôùûüçæœ";
    return lettres.indexOf(c.toLowerCase())!==-1;
}

function valider_name(){
    var val=document.getElementById("name").value;
    var speciaux=["-"," "];
    if(val.length===0){
        document.getElementById("erreur_name").textContent="Entrez votre prénom.";
        return false;
    }
    if(val.length>15){
        document.getElementById("erreur_name").textContent="Le prénom ne peut pas dépasser 15 caractères.";
        return false;
    }
    for(var i=0; i<val.length; i++){
        var c=val[i];
        if(c>="0" && c<="9"){
            document.getElementById("erreur_name").textContent="Le prénom ne peut pas contenir de chiffres.";
            return false;
        }
        if(speciaux.indexOf(c)===-1 && !est_lettre(c)){
            document.getElementById("erreur_name").textContent="Le prénom ne peut contenir que des lettres, tirets et espaces.";
            return false;
        }
    }
    document.getElementById("erreur_name").textContent="";
    return true;
}

function valider_surname(){
    var val=document.getElementById("surname").value;
    var speciaux=["-"," "];
    if(val.length===0){
        document.getElementById("erreur_surname").textContent="Entrez votre nom de famille.";
        return false;
    }
    if(val.length>30){
        document.getElementById("erreur_surname").textContent="Le nom ne peut pas dépasser 30 caractères.";
        return false;
    }
    for(var i=0; i<val.length; i++){
        var c=val[i];
        if(c>="0" && c<="9"){
            document.getElementById("erreur_surname").textContent="Le nom ne peut pas contenir de chiffres.";
            return false;
        }
        if(speciaux.indexOf(c)===-1 && !est_lettre(c)){
            document.getElementById("erreur_surname").textContent="Le nom ne peut contenir que des lettres, tirets et espaces.";
            return false;
        }
    }
    document.getElementById("erreur_surname").textContent="";
    return true;
}

function valider_email(){
    var val=document.getElementById("Email2").value;
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

function valider_password1(){
    var val=document.getElementById("password1").value;
    var spec=["!","@","#","$","%","&","*","?","+","="];
    if(val.length===0){
        document.getElementById("erreur_password1").textContent="Entrez votre mot de passe.";
        return false;
    }
    if(val.length<6){
        document.getElementById("erreur_password1").textContent="Le mot de passe doit contenir au moins 6 caractères.";
        return false;
    }
    var a_chiffre=false;
    var a_special=false;
    for(var i=0; i<val.length; i++){
        if(val[i]>="0" && val[i]<="9"){
            a_chiffre=true;
        }
        if(spec.indexOf(val[i])!==-1){
            a_special=true;
        }
    }
    if(!a_chiffre){
        document.getElementById("erreur_password1").textContent="Le mot de passe doit contenir au moins un chiffre.";
        return false;
    }
    if(!a_special){
        document.getElementById("erreur_password1").textContent="Doit contenir au moins un : !, @, #, $, %, &, *, ?, +, =";
        return false;
    }
    document.getElementById("erreur_password1").textContent="";
    return true;
}

function valider_password2(){
    var val1=document.getElementById("password1").value;
    var val2=document.getElementById("password2").value;
    if(val2.length===0){
        document.getElementById("erreur_password2").textContent="Confirmez votre mot de passe.";
        return false;
    }
    if(val1!==val2){
        document.getElementById("erreur_password2").textContent="Les mots de passe ne concordent pas.";
        return false;
    }
    document.getElementById("erreur_password2").textContent="";
    return true;
}

function valider_numero(){
    var val=document.getElementById("numero").value;
    if(val.length===0){
        document.getElementById("erreur_numero").textContent="Entrez votre numéro de rue.";
        return false;
    }
    for(var i=0; i<val.length; i++){
        if(val[i]<"0" || val[i]>"9"){
            document.getElementById("erreur_numero").textContent="Le numéro ne peut contenir que des chiffres.";
            return false;
        }
    }
    if(parseInt(val)<=0){
        document.getElementById("erreur_numero").textContent="Le numéro doit être positif.";
        return false;
    }
    document.getElementById("erreur_numero").textContent="";
    return true;
}

function valider_rue(){
    var val=document.getElementById("rue").value;
    var speciaux=["-"," "];
    if(val.length===0){
        document.getElementById("erreur_rue").textContent="Entrez votre rue.";
        return false;
    }
    if(val.length<3){
        document.getElementById("erreur_rue").textContent="Le nom de la rue est trop court.";
        return false;
    }
    for(var i=0; i<val.length; i++){
        var c=val[i];
        if(speciaux.indexOf(c)===-1 && !est_lettre(c) && !(c>="0" && c<="9")){
            document.getElementById("erreur_rue").textContent="La rue ne peut contenir que des lettres, chiffres, tirets et espaces.";
            return false;
        }
    }
    document.getElementById("erreur_rue").textContent="";
    return true;
}

function valider_ville(){
    var val=document.getElementById("ville").value;
    var speciaux=["-"," "];
    if(val.length===0){
        document.getElementById("erreur_ville").textContent="Entrez votre ville.";
        return false;
    }
    if(val.length<2){
        document.getElementById("erreur_ville").textContent="Le nom de la ville est trop court.";
        return false;
    }
    for(var i=0; i<val.length; i++){
        var c=val[i];
        if(speciaux.indexOf(c)===-1 && !est_lettre(c)){
            document.getElementById("erreur_ville").textContent="La ville ne peut contenir que des lettres, tirets et espaces.";
            return false;
        }
    }
    document.getElementById("erreur_ville").textContent="";
    return true;
}

function valider_code_postal(){
    var val=document.getElementById("code_postal").value;
    if(val.length===0){
        document.getElementById("erreur_code_postal").textContent="Entrez votre code postal.";
        return false;
    }
    if(val.length!==5){
        document.getElementById("erreur_code_postal").textContent="Le code postal doit contenir exactement 5 chiffres.";
        return false;
    }
    for(var i=0; i<5; i++){
        if(val[i]<"0" || val[i]>"9"){
            document.getElementById("erreur_code_postal").textContent="Le code postal ne peut contenir que des chiffres.";
            return false;
        }
    }
    document.getElementById("erreur_code_postal").textContent="";
    return true;
}

function valider_phone(){
    var val=document.getElementById("phone").value;
    if(val.length===0){
        document.getElementById("erreur_phone").textContent="Entrez votre numéro de téléphone.";
        return false;
    }
    if(val.length!==10){
        document.getElementById("erreur_phone").textContent="Le numéro doit contenir exactement 10 chiffres.";
        return false;
    }
    for(var i=0; i<val.length; i++){
        if(val[i]<"0" || val[i]>"9"){
            document.getElementById("erreur_phone").textContent="Le numéro ne peut contenir que des chiffres.";
            return false;
        }
    }
    if(val[0]!=="0" || (val[1]!=="6" && val[1]!=="7")){
        document.getElementById("erreur_phone").textContent="Le numéro doit commencer par 06 ou 07.";
        return false;
    }
    document.getElementById("erreur_phone").textContent="";
    return true;
}

function valider_birthday(){
    var val=document.getElementById("birthday").value;
    if(val.length===0){
        document.getElementById("erreur_birthday").textContent="Entrez votre date d'anniversaire.";
        return false;
    }
    var naissance=new Date(val);
    var auj=new Date();
    var age=auj.getFullYear()-naissance.getFullYear();
    var m=auj.getMonth()-naissance.getMonth();
    if(m<0 || (m===0 && auj.getDate()<naissance.getDate())){
        age=age-1;
    }
    if(age<16){
        document.getElementById("erreur_birthday").textContent="Vous devez avoir au moins 16 ans.";
        return false;
    }
    document.getElementById("erreur_birthday").textContent="";
    return true;
}
