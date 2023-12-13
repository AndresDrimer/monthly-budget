<?php
session_start();
include("header.php");
include("database.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/normalize.css">
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    
    <title>Monthly Budget</title>
</head>

<body>
    <h4 class="white" id="index-top-text">Monthly Budget es una página diseñada para ayudar a controlar el flujo de fondos doméstico o de pequeños proyectos. Permite la creación de espacios personales o compartidos de manera privada.</h4>
  
    <h2 class="white">Empezamos?</h2>

    <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">

        <input type="submit" name="create-group" value="😎  CREAR UN GRUPO" class="btns">

        <input type="submit" name="enter-group" value="⚡  ENTRAR A UN GRUPO" class="btns">


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