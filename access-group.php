<?php

include("./header.php");
include("./database.php");
if (session_id() == "") {
    session_start();
}
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
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

<body><?php
        echo "<h1 class='white less-width margin-y'>" . ($_SESSION["language"] == "espanol" ? "INGRESÁ A UN GRUPO" : "JOIN A GROUP") . "</h1>"; ?>



    <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
        <label><?php echo $_SESSION["language"] == "espanol" ? "Nombre del grupo: " : "Group´s name"; ?></label>
        <input type="text" name="group_name"> <br>
        <label>Password: </label>
        <input type="text" name="group_password"><br>
        <input type="submit" name="entrar" value="<?php echo $_SESSION["language"] == "espanol" ? "entrar" : "enter"; ?>" class="btns">
    </form>


</body>

</html>

<?php

if ($_SERVER["REQUEST_METHOD"]  == "POST") {
    //check for empty fields
    if (empty($_POST["group_name"]) || empty($_POST["group_password"])) {
        echo "<div class='error-msg-cont'><p class='less-width'>" .  ($_SESSION["language"] == "espanol" ? "Tenes que indicar el nombre y el password para acceder a un grupo" : "You have to write group´s name and password to acces a group") . "</p></div>";
    } else {
        //asign sanitized variables
        $group_name = filter_input(INPUT_POST, "group_name", FILTER_SANITIZE_SPECIAL_CHARS);
        $group_password = filter_input(INPUT_POST, "group_password", FILTER_SANITIZE_SPECIAL_CHARS);

        $sql = "SELECT * FROM groups WHERE group_name = ? ";
        /** @var \mysqli $conn */
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $group_name);
        $stmt->execute();
        $result = $stmt->get_result();
        
        //check if group name exists
        if ($result->num_rows == 0) {
            echo "<div class='error-msg-cont'><p class='less-width'>" . ($_SESSION["language"] == "espanol" ? "Ese grupo no está registrado. Intentá nuevamente o <a href='./create.php' class='underline'>creá tu propio grupo aquí</a>" : "The group does not exist. Try again or <a href='./create.php' class='underline'>create your own group here</a>") . "</p></div>";
         
        } else {


            //check password 
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                if (!isset($_SESSION["user_id"])) {
                    echo "<div class='error-msg-cont'><p>" . "necesitas estar logueado para ingresar a este grupo" . ($_SESSION["language"] == "espanol" ? "" : "You need to be logged in to enter this group") . "</p></div>";
                }
                $group_id  = $row["group_id"];
                if (password_verify($group_password, $row["group_password"])) {

                    //add this group_id and user_is to cross-reference-group
                    $cross_reference  = "INSERT INTO 	user_group_connection (user_id, group_id) VALUES (?,?)";

                    $stmt = $conn->prepare($cross_reference);
                    $stmt->bind_param("ss", $_SESSION['user_id'], $group_id);
                    $stmt->execute();
                    

                    $_SESSION["group_id"] = $group_id;

                    mysqli_close($conn);
                    header("Location: dashboard.php");
                } else {
                    echo "<div class='error-msg-cont'><p>" . ($_SESSION["language"] == "espanol" ? "El PASSWORD es INCORRECTO, intenta de nuevo" : "The PASSWORD is INCORRECT, try again") . "</p></div>";
                }
            }
        }
    }
}
?>