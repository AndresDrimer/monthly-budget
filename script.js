//HEADER
//open - close header modal
let btn_close = document.getElementById("header-modal-close-btn");
let btn_open = document.getElementById("btn-hamburguer");
let header_modal = document.getElementById("header-modal");
let gray_mask = document.getElementById("gray-mask-for-body");

btn_open.addEventListener("click", function(){
header_modal.classList.remove("invisible");
gray_mask.style.display = "block";
btn_close.addEventListener("click", function(){
    header_modal.classList.add("invisible");
    gray_mask.style.display = "none";
})
})


