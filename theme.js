(function(){
    var cookies=document.cookie.split(";");
    for(var i=0; i<cookies.length; i++){
        var parties=cookies[i].split("=");
        if(parties[0]==="theme" && parties[1]==="sombre"){
            document.documentElement.classList.add("mode_sombre");
            break;
        }
    }
})();
document.addEventListener("DOMContentLoaded", function(){
    var btn=document.getElementById("theme_toggle");
    if(btn===null){
        return;
    }
    if(document.documentElement.classList.contains("mode_sombre")){
        btn.textContent="☀";
    }else{
        btn.textContent="🌙";
    }
    btn.addEventListener("click", function(){
        if(document.documentElement.classList.contains("mode_sombre")){
            document.documentElement.classList.remove("mode_sombre");
            document.cookie="theme=clair";
            btn.textContent="🌙";
        }else{
            document.documentElement.classList.add("mode_sombre");
            document.cookie="theme=sombre";
            btn.textContent="☀";
        }
    });
});
