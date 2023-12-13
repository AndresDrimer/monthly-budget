<?php
session_start();
include("header.php");
include("database.php");

if(!isset($_SESSION["user_id"])){
    header("Location: login.php");
}
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
    <h1 class="white less-width margin-y" >INGRESÁ A UN GRUPO</h1>


    <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
        <label>Nombre del grupo: </label>
        <input type="text" name="group_name"> <br>
        <label>Password: </label>
        <input type="text" name="group_password"><br>
        <input type="submit" name="entrar" value="entrar" class="btns">
    </form>


</body>

</html>

<?php

if ($_SERVER["REQUEST_METHOD"]  == "POST") {
    //check for empty fields
    if (empty($_POST["group_name"]) || empty($_POST["group_password"])) {
        echo "Tenes que crear un nombre y un password para poder crear un nuevo grupo";
    } else {
        //asign sanitized variables
        $group_name = filter_input(INPUT_POST, "group_name", FILTER_SANITIZE_SPECIAL_CHARS);
        $group_password = filter_input(INPUT_POST, "group_password", FILTER_SANITIZE_SPECIAL_CHARS);

        $sql = "SELECT * FROM groups WHERE group_name = '$group_name' ";
      
        /** @var \mysqli $conn */
        $result = mysqli_query($conn, $sql);

        //check password 
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);

            if (!isset($_SESSION["user_id"])) {
                echo "necesitas estar logueado para ingresar a este grupo";
            }
            $group_id  = $row["group_id"];
            if (password_verify($group_password, $row["group_password"])) {

                //add this group_id and user_is to cross-reference-group
                $cross_reference  = "INSERT INTO 	user_group_connection (user_id, group_id) VALUES ('" . $_SESSION['user_id'] . "', '$group_id')";

                $insert_group_in_user = mysqli_query($conn, $cross_reference);

                $_SESSION["group_id"] = $group_id;

                mysqli_close($conn);
                header("Location: dashboard.php");
            } else {
                echo "El PASSWORD es INCORRECTO, intenta de nuevo";
            }
        }
    }
}
?>