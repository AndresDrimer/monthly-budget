<?php
session_start();
include("database.php");
include("header.php");

$group_id = $_SESSION["group_id"];
$sql = "SELECT * FROM groups WHERE group_id = '$group_id'";
//added this line on every page only to prevent conn being marked as an error
/** @var \mysqli $conn */
$result = mysqli_query($conn, $sql);
$actual_group = mysqli_fetch_assoc($result);

$user_id = $_SESSION["user_id"];
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
    holis
    <article id="date-changer">
            <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post" id="date-form">
                <input type="hidden" name="form_id" value="form_date_select">
                <select name="month" id="month">
                    <?php
                    $months = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
                    for ($i = 0; $i < 12; $i++) {
                        $value = str_pad($i + 1, 2, '0', STR_PAD_LEFT);
                        $selected = date('m') == $value ? ' selected="selected"' : '';
                        echo "<option value='$value'$selected>{$months[$i]}</option>";
                    }
                    ?>
                </select>


                <select name="year" id="year">

                    <?php
                    for ($i = date('Y'); $i >= 2000; $i--) {
                        echo "<option value='$i'";
                        if (date('Y') == $i) echo ' selected="selected"';
                        echo ">$i</option>";
                    }
                    ?>
                </select>

                <input type="submit" name="submit" value="Enviar">
            </form>

        </article>
    <div id="gray-mask-for-body"></div>
    <script src="script.js"></script>
</body>
</html>