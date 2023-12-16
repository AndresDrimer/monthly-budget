
//controls toggler button from modal ADD ENTRY
let switch_checkbox_settings = document.getElementById("switch-checkbox-settings");
let label_usuario = document.getElementById("label-usuario");
let label_grupo = document.getElementById("label-grupo");
let form_user_article = document.getElementById("form-user-article");
let form_group_article = document.getElementById("form-group-article");


switch_checkbox_settings.addEventListener("click", function(){
    label_usuario.classList.toggle("selected");
    label_grupo.classList.toggle("selected");
    form_user_article.classList.toggle("invisible");
    form_group_article.classList.toggle("invisible");
   
});




