<?php

include("./database.php");
include("./header.php");
if (session_id() == "") {
    session_start();
   }

$user_id = $_SESSION["user_id"];
$group_id = $_SESSION["group_id"];

$sql = "SELECT * FROM groups WHERE group_id = ?";
/** @var \mysqli $conn */

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $group_id);
$stmt->execute();
$result = $stmt->get_result();
$actual_group = $result->fetch_assoc();

 


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

<body id="settings-body">
    <section id="settings-title">
        <h1 class="white"> 🔨 Settings 🔨</h1>
    </section>

    <div class="switch-container">
        <label class="switch-label selected" id="label-usuario"><?php echo $_SESSION["language"] == "espanol" ? "usuario" : "user"; ?></label>
        <label class="switch">
            <input type="checkbox" id="switch-checkbox-settings">
            <span class="slider round"></span>
        </label>
        <label class="switch-label" id="label-grupo"><?php echo $_SESSION["language"] == "espanol" ? "grupo" : "group"; ?></label>
    </div>

    <article id="form-user-article" class="visible">
        <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
            <input type="hidden" name="form_id" value="form_update_user">
            <label for="username"><?php echo $_SESSION["language"] == "espanol" ? "Nombre de usuario" : "Username"; ?></label>
            <input type="text" id="username" name="new_user_name" value="<?php echo $_SESSION['username']; ?>">

            <input type="submit" value="<?php echo $_SESSION["language"] == "espanol" ? "Modificar" : "Update"; ?>" class="btns" onclick='return confirm("<?php echo $_SESSION["language"] == "espanol" ? '¿Querés modificar tu nombre de usuario? Necesitarás recordar este cambio más adelante a la hora de loguearte' : 'Do you want to modify your username? You will need to remember this change when you log in later'; ?>")'>
        </form>
    </article>

    <article id="form-group-article" class="invisible">
        <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
            <input type="hidden" name="form_id" value="form_update_group">
            <label for="username">Nombre del Grupo:</label>
            <input type="text" id="groupname" name="new_group_name" value="<?php echo $actual_group["group_name"]; ?>">

            <input type="submit" value="Modificar" class="btns" onclick='return confirm("¿Seguro querés actualizar el nombre de este grupo? Se modificará para todos sus usuarios")'>
        </form>
    </article>
<hr id="settings-separator">
    <article>
    <div class="less-width"><h4 class="white"><?php echo $_SESSION["language"] == "espanol" ? "Do you want to switch to English? Press button below" : "Queres cambiar a Español? Presiona el botón"; ?></h4></div>
   <?php
   echo '<form action="' . $_SERVER["PHP_SELF"] . '" method="post">
    <input type="hidden" name="form_id" value="form_lang_selector">
    <input type="submit" class="btns" id="submit_lang_settings" name="submit_lang" value="' . strtoupper($_SESSION['language']) . '">
  </form>';
   ?>
</article>
    <?php include("./footer.php"); ?>

    <div id="gray-mask-for-body"></div>
    <script src="./script.js"></script>
    <script src="./settings.js"></script>
</body>

</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && ($_POST["form_id"] == "form_update_user")) {
    $new_user_name = $_POST["new_user_name"];
    $new_user_name = filter_input(INPUT_POST, "new_user_name", FILTER_SANITIZE_SPECIAL_CHARS);

    $change_username = "UPDATE users SET username = ? WHERE user_id = ?";
    $stmt = $conn->prepare($change_username);
    $stmt->bind_param("si", $new_user_name, $user_id);
    $stmt->execute();


    if (!$stmt->store_result()) {
        die('Error: ' . mysqli_error($conn));
    } else {
        $_SESSION["username"] = $new_user_name;
        mysqli_close($conn);
        header("Location: dashboard.php");
        ob_end_flush();

        exit();
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && ($_POST["form_id"] == "form_update_group")) {
    $new_group_name = $_POST["new_user_name"];
    $new_group_name = filter_input(INPUT_POST, "new_group_name", FILTER_SANITIZE_SPECIAL_CHARS);


    $change_groupname = "UPDATE groups SET group_name = ? WHERE group_id = ?";
    $stmt = $conn->prepare($change_groupname);
    $stmt->bind_param("ss", $new_group_name, $group_id);
    $stmt->execute();


    if (!$stmt->store_result()) {
        die('Error: ' . mysqli_error($conn));
    } else {
        
        mysqli_close($conn);
        header("Location: dashboard.php");
        ob_end_flush();

        exit();
    }
}
?>

<?php

 if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_lang'])){
  if($_POST['form_id'] == 'form_lang_selector'){
    if ($_POST['submit_lang'] === 'ENGLISH') {
        $_SESSION['language'] = 'espanol';
    } else {
        $_SESSION['language'] = 'english';
    }
 }}
?>