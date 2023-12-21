<?php

include("./header.php");
include("./database.php");
if (session_id() == "") {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./css/normalize.css">
    <link rel="stylesheet" type="text/css" href="./css/styles.css">

    <title>Monthly Budget</title>
</head>

<body>
    <h4 class="white" id="index-top-text">
        <?php
        if ($_SESSION["language"] == "espanol") {
            echo 'Monthly Budget es una página diseñada para ayudar a controlar el flujo de fondos doméstico o de pequeños proyectos. Permite la creación de espacios personales o compartidos de manera privada.';
        }
        if ($_SESSION["language"] == "english") {
            echo 'Monthly Budget is a page designed to help control the flow of domestic funds or small projects. It allows the creation of personal or shared spaces in a private way.';
        }
        ?>
    </h4>

    <h2 class="white"><?php
    if ($_SESSION["language"] == "espanol") {
        echo 'Empezamos?';}
        if ($_SESSION["language"] == "english") {
            echo 'Shall we start?';}    
    
    ?></h2>

    <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">

    <input type="submit" name="create-group" value="<?php echo $_SESSION["language"] == 'espanol' ? "😎 CREAR UN GRUPO" : "😎 CREATE A GROUP"; ?>" class="btns">

        <input type="submit" name="enter-group" value="<?php echo $_SESSION["language"] == "espanol" ? "⚡  ENTRAR A UN GRUPO" : "⚡  ENTER A GROUP" ?>"
         class="btns">


    </form>


</body>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["create-group"])) {
        header("Location:create.php");
    } else if (isset($_POST["enter-group"])) {
        header("Location:access-group.php");
    }
}


?>