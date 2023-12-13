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

<body>
    <h1 class="white">REGISTRAR NUEVO USUARIO</h1>
    <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
        <label>Username:</label>
        <input type="text" name="username"><br>
        <label>Email:</label>
        <input type="email" name="email"><br>
        <label>Password:</label>
        <input type="password" name="password"><br>
        <input type="submit" name="submit" value="submit" class="btns">
    </form>
</body>

</html>

<?php


if ($_SERVER["REQUEST_METHOD"] == "POST") {
   //check for empty inputs
   if (empty($_POST["username"])){
       echo"<div class='error-msg-cont'><p>❌ El username está incompleto.<br><span class='underline'><a href='./register.php'>Intentá de nuevo</a></span></p></div>";
   }
   elseif(empty($_POST["email"])){
    echo"<div class='error-msg-cont'><p>❌ El email está incompleto.<br><span class='underline'><a href='./register.php'>Intentá de nuevo</a></span></p></div>";
   }
   elseif(empty($_POST["password"])){
    echo"<div class='error-msg-cont'><p>❌ El password está incompleto.<br><span class='underline'><a href='./register.php'>Intentá de nuevo</a></span></p></div>";
   } 
   else {
   //sanitize variables
       $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
       $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
       $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
       $hash_password = password_hash($password, PASSWORD_DEFAULT);

       //validate variables
       if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo"<div class='error-msg-cont'><p>❌ El email no es válido.<br><span class='underline'><a href='./register.php'>Intentá de nuevo</a></span></p></div>";
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
        echo"<div class='error-msg-cont'><p>❌ El email ya está registrado.<br><span class='underline'><a href='./register.php'>Intentá de nuevo</a></span></p></div>";
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