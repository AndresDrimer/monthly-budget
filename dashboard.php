<?php
ob_start();
session_start();
include("database.php");
include("header.php");
include 'utils/get_dolarblue_value.php';

$group_id = $_SESSION["group_id"];
$sql = "SELECT * FROM groups WHERE group_id = '$group_id'";
//added this line on every page only to prevent conn being marked as an error
/** @var \mysqli $conn */
$result = mysqli_query($conn, $sql);
$actual_group = mysqli_fetch_assoc($result);

$user_id = $_SESSION["user_id"];

if(!isset($_SESSION["current_month"])){
    $_SESSION["current_month"] = date('m');
 }
 
 if(!isset($_SESSION["current_year"])){
    $_SESSION["current_year"] = date('Y');
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
 <section>
    <h1 id="group-name-header"> ⚡ <?php echo strtoupper($actual_group["group_name"]); ?> ⚡</h1>
</section>

   

    <section id="modal-add-transaction">

        <div id="close-modal-btn-cont">
            <button id="close-modal">&#x2718</button>
        </div>

        <div class="switch-container">
            <label class="switch-label selected" id="label-gasto">gasto</label>
            <label class="switch">
                <input type="checkbox" id="switch-checkbox">
                <span class="slider round"></span>
            </label>
            <label class="switch-label" id="label-ingreso">ingreso</label>
        </div>

        <article id="form-article-expenses" class="visible">
            <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
                <input type="hidden" name="form_id" value="form_expenses">
                <div>
                    <span id="dolar-sign">$</span>
                    <input type="number" name="amount" step="any" id="modal-input-amount">
                </div>
                <input type="text" name="description" placeholder="descripción" id="modal-input-description">
                <input type="submit" name="submit" value="cargar" class="submit-btn">
            </form>
        </article>


        <article id="form-article-incomes" class="invisible">

            <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
                <input type="hidden" name="form_id" value="form_incomes">
                <div>
                    <span id="dolar-sign">$</span>
                    <input type="number" name="amount" step="any" id="modal-input-amount">
                </div>
                <input type="text" name="description" placeholder="descripción" id="modal-input-description">
                <input type="submit" name="submit" value="cargar" class="submit-btn">
            </form>
        </article>
    </section>


    <section>     
        <article id="date-prev-next-container">
            <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
                <input type="hidden" name="form_id" value="form_date_selector">
                <input type="submit" name="prev_date_select" id="prev-date-select" value="<">

                <p>
                    <?php
                //month to string format
                $dateObj = DateTime::createFromFormat('!m', $_SESSION["current_month"]);
                $monthName = $dateObj->format('M');
                    
                    echo "<div id='actual-month-container'><p>" . $monthName . "/" . $_SESSION["current_year"] . "</p></div>";
                    ?>
                </p>

                <input type="submit" name="next_date_select" id="next-date-select" value=">">
            </form>
        </article>

      


 <article id="button-for-modal">
        <button id="btn-add-transaction"><span>+</span></button>
</article>

        <article id="table-show">
            <?php
            //////////////////////////////////////
            //PAINT RESULT FOR CURRENT MONTH AT START
            //////////////////////////////////////

            // SQL query for  default values
            
            $pass_date = "SELECT * FROM grp_" . $group_id . "_data WHERE MONTH(created_at) = " . $_SESSION["current_month"] . " AND YEAR(created_at) = " . $_SESSION["current_year"] . " ORDER BY created_at DESC";
            $result = mysqli_query($conn, $pass_date);

            //calculate month´s total
            $month_total = 0;
            while ($row = mysqli_fetch_assoc($result)) {
                if ($row['transac_type'] == 'income') {
                    $month_total += $row['amount'];
                } elseif ($row['transac_type'] == 'expense') {
                    $month_total -= $row['amount'];
                }
            }
            $total_formatted = number_format($month_total, 2, ',', '.');

            echo "<div id=total-container><p>Total: <span>$" . $total_formatted . "</span></p></div>";

            //print each row as a list item
            $result2 = mysqli_query($conn, $pass_date);
            echo "<ul class='data-ul'>";
            while ($row = mysqli_fetch_assoc($result2)) {


            // make buttons for edit and delete
            $buttons = "<form action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='post' id='form-inner-li-crud-container'>
            <input type='hidden' name='form_id' value='form_inner_li'>
            <input type='hidden' name='item_id' value='{$row['transaction_id']}'>
            <span class='crud-menu-buttons' id='edit-a'><a href='./edit.php?id={$row['transaction_id']}'>
            🖋</a></span>

            <input type='submit' name='delete_submit' class='crud-menu-buttons' value='❌'  onclick='return confirm(\"¿Estás seguro de que querés borrar este elemento?\");' > 
            </form> ";

                //create a class to paint different color for income or expenses
                if ($row['transac_type'] == 'expense') {
                    $class_color = 'li-container-expense';
                } else if ($row['transac_type'] == 'income') {
                    $class_color = 'li-container-income';
                }

                //format number 
                $formatted_number = number_format($row['amount'], 2, ',', '.');

                //format date to display dd-mm
                $formatted_date = substr($row['created_at'], 8, 2) . '-' . substr($row['created_at'], 5, 2);


                echo "<div class='data-li-container $class_color'>";
                echo "<li><div>" . $formatted_date . "</div><div>" . substr($row['created_at'], 11, 5) . "</div></li>";
                echo "<li><div class='li-price'>" . "$" . "{$formatted_number}</div><div class='li-description'>{$row['description']}</div></li>";
                echo "<li>{$buttons}</li>";
                echo "</div>";
            }
            echo "</ul>";

            ?>
        </article>
    </section>


    <div id="gray-mask-for-body"></div>
   
    <script src="script.js"></script>
    <script src="script-dashboard.js"></script>
   
</body>

</html>

<!-- add new transactions-->
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && ($_POST["form_id"] == "form_expenses" || $_POST["form_id"] == "form_incomes")) {
    //check for empty fields
    if (empty($_POST["amount"]) || empty($_POST["description"])) {
        echo "Tenes que completar monto y la descripción para cargar una nueva operación";
    } else {
        //sanitize 
        $amount = filter_input(INPUT_POST, "amount", FILTER_SANITIZE_NUMBER_FLOAT);
        $description = filter_input(INPUT_POST, "description", FILTER_SANITIZE_SPECIAL_CHARS);

        //validate
        $amount = filter_input(INPUT_POST, "amount", FILTER_VALIDATE_FLOAT);

        if ($amount === false) {
            echo "el monto ingresado es incorrecto, por favor ingreselo nuevamente";
        }

        $table_name = "grp_" . $group_id . "_data";

        //check not insert twice same row
        $check_query = "SELECT * FROM `$table_name` WHERE  amount = '$amount' AND description = '$description' AND inserted_by = '$user_id'";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) == 0) {

            if ($_POST["form_id"] == "form_expenses") {
                $transac_type = 'expense';
             } else if ($_POST["form_id"] == "form_incomes") {
                $transac_type = 'income';
             } else {
                die('Error: Invalid form_id');
             }
             
             $sql = "INSERT INTO `$table_name` (transac_type, amount, dolarblue, description, inserted_by) VALUES (?, ?, ?, ?, ?)";
             
             // Hacer una consulta preparada
             $stmt = $conn->prepare($sql);
             if (!$stmt){
                die('Error: ' . mysqli_error($conn));
             }
             $stmt->bind_param("sddss", $transac_type, $amount, $dolar_blue, $description, $user_id);
             $stmt->execute();
             
             mysqli_close($conn);
             header("Location: " . $_SERVER["PHP_SELF"]);
             ob_end_flush();
             exit();
        }
    }
}
?>

<!--actualizar mes -->
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && ($_POST["form_id"] == "form_date_selector")) {
    if (isset($_POST["prev_date_select"])){
        $date = DateTime::createFromFormat('Y-m', $_SESSION["current_year"] . '-' . $_SESSION["current_month"]);
        $date->modify('-1 month');
        $_SESSION["current_month"] = $date->format('m');
        $_SESSION["current_year"] = $date->format('Y');
 
        header("Location: " . $_SERVER["PHP_SELF"]);
        exit();
 
    } else if (isset($_POST["next_date_select"])) {
        $date = DateTime::createFromFormat('Y-m', $_SESSION["current_year"] . '-' . $_SESSION["current_month"]);
        $date->modify('+1 month');
        $_SESSION["current_month"] = $date->format('m');
        $_SESSION["current_year"] = $date->format('Y');
 
        header("Location: " . $_SERVER["PHP_SELF"]);
        exit();
    }
 }

?>

<!--borrar y editar una row -->
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && ($_POST["form_id"] == "form_inner_li")) {
    $item_id = $_POST["item_id"];
    $table_name = "grp_" . $group_id . "_data";
    if (isset($_POST["delete_submit"])) {


        $sql_del_item = "DELETE FROM $table_name WHERE transaction_id ='$item_id'";

        $deleted_item = mysqli_query($conn,  $sql_del_item);

        if (!$deleted_item) {
            die('Error: ' . mysqli_error($conn));
        }

        mysqli_close($conn);
        header("Location: " . $_SERVER["PHP_SELF"]);
        ob_end_flush();

        exit();
    }
}
?>
 <?php include './footer.php'; ?>