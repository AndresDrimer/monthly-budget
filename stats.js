var accordions = document.querySelectorAll(".accordion");

accordions.forEach(function(accordion) {
 accordion.querySelector(".label").addEventListener("click", function() {
 var content = this.nextElementSibling;
 var arrow = this.querySelector(".arrow");
 if (content.style.height) {
   content.style.height = null;
   arrow.classList.remove("rotate");
 } else {
   content.style.height = content.scrollHeight + "px";
   arrow.classList.add("rotate");
 } 
 });
});




