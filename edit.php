<?php
ob_start(); 

include("./database.php");
include("./header.php");
if (session_id() == "") {
    session_start();
   }

$group_id = $_SESSION["group_id"];
$table_name = "grp_" . $group_id . "_data";
$user_id = $_SESSION["user_id"];
$row_id = $_GET["id"];

$sql = "SELECT * FROM $table_name WHERE transaction_id = '$row_id'";
/** @var \mysqli $conn */
$result = mysqli_query($conn, $sql);

if(!$result){
    die('Error: ' . mysqli_error($conn));
}
$selected_row = mysqli_fetch_assoc($result);

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

<body id="body-edit">
    <div id="btn-back" class="white"><p><a href="./dashboard.php"> ⬅ <?php
echo $_SESSION["language"] == "espanol" ? "volver" : "back";?> </a></p></div>
<h1 id="edit-page-title" class="white"><?php
echo $_SESSION["language"] == "espanol" ? "Editar entrada" : "Edit"; ?></h1>
    <section>
        <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
            <input type="hidden" name="form_id" value="form_edit">
            <div>
                <span id="dolar-sign" class="white">$</span>
                <input type="number" name="amount" step="any" id="edit-input-amount" value="<?php echo $selected_row["amount"]; ?>" class="edit-input"  id="edit-amount">
            </div>
            <input type="text" name="description" placeholder="descripción" id="edit-input-description" value="<?php echo $selected_row["description"] ?>" class="edit-input">
            <input type="submit" name="submit" value="<?php
echo $_SESSION["language"] == "espanol" ?"modificar" : "update"; ?>" class="submit-btn" onclick='return confirm("<?php echo $_SESSION["language"] == "espanol" ? "¿Querés actualizar esta entrada?" : "Do you want to update this entry?"; ?>");'>
        </form>
    </section>

    <div id="gray-mask-for-body"></div>
    <script src="./script.js"></script>
</body>

</html>

<?php
if($_SERVER["REQUEST_METHOD"] == "POST"){
    //sanitize inputs
    $new_amount = filter_input(INPUT_POST, "amount", FILTER_SANITIZE_NUMBER_FLOAT);
    $new_description = filter_input(INPUT_POST, "description", FILTER_SANITIZE_SPECIAL_CHARS);

    //validate
    $new_amount = filter_input(INPUT_POST, "amount", FILTER_VALIDATE_FLOAT);

    $sql_update = "UPDATE $table_name 
    SET amount = ?, description = ?, updated_at = NOW() WHERE transaction_id = ?";

    //prepared statement
    $stmt = $conn->prepare($sql_update);
    if(!$stmt){
        die('Error: ' . mysqli_error($conn));
    }
    $stmt->bind_param("dss", $new_amount, $new_description, $row_id);
    $stmt->execute();

    mysqli_close($conn);
    header("Location: dashboard.php");
    ob_end_flush();
}


?>