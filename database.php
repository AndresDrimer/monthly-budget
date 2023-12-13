<?php


require_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$db_server = $_ENV["DB_SERVER"];
$db_user = $_ENV["DB_USER"];
$db_pass = $_ENV["DB_PASS"];
$db_name = $_ENV["DB_NAME"];
$conn = "";
try {
    $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);
} catch (mysqli_sql_exception) {
    echo "Could not connect! <br>";
}


