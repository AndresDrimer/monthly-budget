<?php
if (session_id() == "") {
    session_start();
   }
?>

<footer>
<section>
<img src="./public/icons/Logo1.png" alt="logo" id="footer-logo">
<?php
echo "<p>" . ($_SESSION["language"] == "espanol" ? "Monthly Budget es una página diseñada para ayudar a controlar el flujo de fondos doméstico o de pequeños proyectos. Permite la creación de espacios personales o compartidos de manera privada. La intención es que resulte facil de usar, que brinde un servicio util y que sea facilmente escalable. Está diseñada en PHP e incluye HTML, CSS y Javascript. La base de datos es MySQL." : "Monthly Budget is a page designed to help control domestic cash flow or small projects. It allows the creation of personal or shared spaces privately. The intention is that it is easy to use, provides a useful service, and is easily scalable. It is designed in PHP and includes HTML, CSS, and Javascript. The database is MySQL.") . "</p>";
?>


</section>
<div id="copy">© <a href="mailto:andresdrimer@hotmail.com">Andrés Drimer</a> - <?php echo date('Y') ?></div>
</footer>