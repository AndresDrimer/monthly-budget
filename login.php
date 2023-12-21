<?php

include("database.php");
include("header.php");
if (session_id() == "") {
    session_start();
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
    <h3 class="white less-width" style="padding-top:16px">
    <?php 
echo $_SESSION["language"] == "espanol" ? 
("Si no estás registrado aún podés hacerlo " . 
'<a href=\'./register.php\' class=\'underline\'>' . "aquí" . 
'</a>') : ("If you are not registered yet, you can do it " . '<a href=\'./register.php\' class=\'underline\'>' . "here" . '</a>') ;
?>

       
    <h1 class="white">LOGIN</h1>
    <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
        <label><?php echo $_SESSION["language"] == "espanol" ? "Usuario" : "User" ?></label>
        <input type="text" name="username"><br>
        <label>Password:</label>
        <input type="password" name="password"><br>
        <input type="submit" name="login" value="<?php echo $_SESSION["language"] == "espanol" ? "entrar" : "login"; ?>" class="btns">
    </form>
</body>

</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //check for empty fields
    if (empty($_POST["username"]) || empty($_POST["password"])) {
        echo "<div class='error-msg-cont'><p>" . ($_SESSION["language"] == "espanol" ? "Tenes que completar los dos campos para loguearte" : "You have to fill in both fields to log in") . "</p></div>";

    } else {
        //make login

        //sanitize
        $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

        //check user existence in DB wuth prepared statment
        $sql_login = "SELECT * FROM users WHERE username = ?";
        /** @var \mysqli $conn */
        $stmt = $conn->prepare($sql_login);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        //check password hash 
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row["password"])) {
                //save user data on session
                $_SESSION["username"] = $username;
                $_SESSION["user_id"] = $row["user_id"];
                $_SESSION["email"] = $row["email"];

                //regenerate session id
                session_regenerate_id();

                //redirect user to select group
                header("Location: index.php");
            } else {
                echo "<div class='error-msg-cont'><p>" . ($_SESSION["language"] == "espanol" ? "❌ El password es incorrecto.<br><span class='underline'><a href='./login.php'>Intentá de nuevo</a></span>" : "❌ The password is incorrect.<br><span class='underline'><a href='./login.php'>Try again</a></span>") . "</p></div>";

            }
        } else {
            echo "<div class='error-msg-cont'><p>" . ($_SESSION["language"] == "espanol" ? "❌ El usuario no está registrado.<br><span class='underline'><a href='./register.php'>Recordá primero REGISTRARTE</a></span>" : "❌ The user is not registered.<br><span class='underline'><a href='./register.php'>Remember to REGISTER FIRST</a></span>") . "</p></div>";

        }
    }
}

mysqli_close($conn);
?>