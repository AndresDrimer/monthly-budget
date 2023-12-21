<?php
include("database.php");
include("header.php");

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

<body><?php echo "<h1 class='white less-width' style='padding:8px 0;'>" . ($_SESSION["language"] == "espanol" ? "REGISTRAR NUEVO USUARIO" : "REGISTER NEW USER") . "</h1>";
?>

    <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
        <?php echo "<label>" . ($_SESSION["language"] == "espanol" ? "Nombre de usuario:" : "Username:") . "</label>";?></label>
        <input type="text" name="username"><br>
        <label>Email:</label>
        <input type="email" name="email"><br>
        <label>Password:</label>
        <input type="password" name="password"><br>
        <input type="submit" name="submit" value="<?php echo $_SESSION["language"] == "espanol" ? "enviar" : "submit"; ?>" class="btns">
    </form>
</body>

</html>

<?php


if ($_SERVER["REQUEST_METHOD"] == "POST") {
   //check for empty inputs
   if (empty($_POST["username"])){
       echo"<div class='error-msg-cont'><p>❌ " . ($_SESSION["language"] == "espanol" ? "El username está incompleto" : "The username is incomplete") . ".<br><span class='underline'><a href='./register.php'>" . ($_SESSION["language"] == "espanol" ? "Intentá de nuevo" : "Try again") . "</a></span></p></div>";
   }
   elseif(empty($_POST["email"])){
    echo"<div class='error-msg-cont'><p>❌ " . ($_SESSION["language"] == "espanol" ? "El email está incompleto" : "The email is incomplete") . ".<br><span class='underline'><a href='./register.php'>" . ($_SESSION["language"] == "espanol" ? "Intentá de nuevo" : "Try again") . "</a></span></p></div>";
   }
   elseif(empty($_POST["password"])){
    echo "<div class='error-msg-cont'><p>❌ " . ($_SESSION["language"] == "espanol" ? "El password está incompleto" : "The password is incomplete") . ".<br><span class='underline'><a href='./register.php'>" . ($_SESSION["language"] == "espanol" ? "Intentá de nuevo" : "Try again") . "</a></span></p></div>";

   } 
   else {
   //sanitize variables
       $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
       $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
       $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
       $hash_password = password_hash($password, PASSWORD_DEFAULT);

       //validate variables
       if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<div class='error-msg-cont'><p>❌ " . ($_SESSION["language"] == "espanol" ? "El email no es válido" : "The email is not valid") . ".<br><span class='underline'><a href='./register.php'>" . ($_SESSION["language"] == "espanol" ? "Intentá de nuevo" : "Try again") . "</a></span></p></div>";

           exit();
        }

       //check if email is unique - PREPARED STATMENT
       /** @var \mysqli $conn */
       $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
       $stmt->bind_param("s", $email);
       $stmt->execute();
       $result = $stmt->get_result();
       $prev_email_user = $result->fetch_assoc();

       if($prev_email_user){
        echo "<div class='error-msg-cont'><p>❌ " . ($_SESSION["language"] == "espanol" ? "El email ya está registrado" : "The email is already registered") . ".<br><span class='underline'><a href='./register.php'>" . ($_SESSION["language"] == "espanol" ? "Intentá de nuevo" : "Try again") . "</a></span></p></div>";

           mysqli_close($conn);   
           exit();
       }

       //insert new user
       $stmt = $conn->prepare("INSERT INTO users (username,email, password) VALUES (?, ?, ?)");
       $stmt->bind_param("sss", $username, $email, $hash_password);
       $stmt->execute();

       header("Location: login.php");
   }
}

mysqli_close($conn);
?>