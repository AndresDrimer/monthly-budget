

//DASHBOARD
//makes button bring up modal for adding transaction
let button_add_transaction = document.getElementById("btn-add-transaction");
let modal_add_transaction = document.getElementById("modal-add-transaction"); 
let close_modal = document.getElementById("close-modal");

button_add_transaction.addEventListener("click", function(){
    modal_add_transaction.classList.remove("invisible"); 
    modal_add_transaction.classList.add("visible"); 
   
});
close_modal.addEventListener("click", function(){
    modal_add_transaction.classList.add("invisible"); 
});


//controls toggler button from modal ADD ENTRY
let switch_checkbox = document.getElementById("switch-checkbox");
let label_gasto = document.getElementById("label-gasto");
let label_ingreso = document.getElementById("label-ingreso");
let form_article_expenses = document.getElementById("form-article-expenses");
let form_article_incomes = document.getElementById("form-article-incomes");
let modal = document.getElementById("modal-add-transaction")

switch_checkbox.addEventListener("click", function(){
    label_gasto.classList.toggle("selected");
    label_ingreso.classList.toggle("selected");
    form_article_expenses.classList.toggle("invisible");
    form_article_incomes.classList.toggle("invisible");
    modal.classList.toggle("make-green-back");
});
