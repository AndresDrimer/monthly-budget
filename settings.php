<?php
session_start();
include("database.php");
include("header.php");


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
    holis Settings
 
    <div id="gray-mask-for-body"></div>
    <script src="script.js"></script>
</body>
</html>