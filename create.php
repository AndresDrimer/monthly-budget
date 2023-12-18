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
    <h1 class="white">CREAR UN NUEVO GRUPO</h1>


    <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post" id="form_group">
    <input type="hidden" name="form_id" value="form_group_creation">
        <label>Nombre de tu nuevo grupo: </label>
        <input type="text" name="group_name"> <br>
        <label>Password: </label>
        <input type="text" name="group_password" placeholder="inventa un password"><br>
        <input type="submit" name="crear" value="crear  🚀" class="btns">
    </form>

    <br>
    <p class="white less-width">⚡ Recordá guardar el nombre del GRUPO y su PASSWORD para poder acceder más adelante y compartirlo con quien quieras! ⚡ </p>

   
    

</body>

</html>


<?php
if ($_SERVER["REQUEST_METHOD"]  == "POST" && ($_POST["form_id"] == "form_group_creation") ) {
    //check for empty fields
    if (empty($_POST["group_name"]) || empty($_POST["group_password"])) {
        echo "Tenes que crear un nombre y un password para poder crear un nuevo grupo";
    } else {
        //asign sanitized variables
        $group_name = filter_input(INPUT_POST, "group_name", FILTER_SANITIZE_SPECIAL_CHARS);
        $group_password = filter_input(INPUT_POST, "group_password", FILTER_SANITIZE_SPECIAL_CHARS);
        $hash_group_password  = password_hash($group_password, PASSWORD_DEFAULT);
        //generate random group name
        function generateRandomString()
        {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
            $randomString = '';
            for ($i = 0; $i < 8; $i++) {
                $index = rand(0, strlen($characters) - 1);
                $randomString .= $characters[$index];
            }
            return $randomString;
        }

        //check if random string as group_id is already on DB, in that case generate another one.
        do {
            $group_id = generateRandomString();
            $sql_check_prev = "SELECT * FROM groups WHERE group_id = '$group_id'";
            /** @var \mysqli $conn */
            $check_existance = mysqli_query($conn, $sql_check_prev);
        } while (mysqli_num_rows($check_existance) > 0);

        //add new group row
        $sql = "INSERT INTO groups (group_name, group_password, group_id)
    VALUES ('$group_name' , '$hash_group_password', '$group_id');";
        $table = mysqli_query($conn, $sql);


        //create a new table for a new group
        $table_name = "grp_" . $group_id . "_data";
       
        $sql2 = "CREATE TABLE $table_name (
        transaction_id INT PRIMARY KEY AUTO_INCREMENT,
        transac_type ENUM('income', 'expense'),
        amount DECIMAL(9,2),
        dolarblue DECIMAL(9,2),
        description VARCHAR(255),
        created_at DATETIME DEFAULT NOW(),
        updated_at DATETIME DEFAULT NULL,
        inserted_by INT, 
        FOREIGN KEY(inserted_by) REFERENCES users(user_id)                      
    );";

        $table_create = mysqli_query($conn, $sql2);
      
        //add table name to user groups
        $sql3 = "SELECT * from users WHERE user_id = '" . $_SESSION['user_id'] . "' ";
        $session_user = mysqli_query($conn, $sql3);
        $current_user = mysqli_fetch_assoc($session_user);

        if (mysqli_num_rows($session_user) == 0) {
            echo "usuario no logueado!!";
        } 
        else { 
            
            $cross_reference  = "INSERT INTO 	user_group_connection (user_id, group_id) VALUES ('" . $_SESSION['user_id'] . "', '$group_id')";

            $insert_group_in_user = mysqli_query($conn, $cross_reference);
          
            $_SESSION["group_id"] = $group_id;
            mysqli_close($conn);
            header("Location: dashboard.php");
        }
    }
}

?>