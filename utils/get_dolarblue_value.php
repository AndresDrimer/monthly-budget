<?php

$json = file_get_contents("https://dolarapi.com/v1/dolares/blue");
$data = json_decode($json, true);

$dolar_blue = $data['venta'];
$dolar_blue_date = $data['fechaActualizacion'];

//validate and sanitize
$dolar_blue = filter_var($dolar_blue, FILTER_VALIDATE_FLOAT);

// check format
if ($dolar_blue !== false) {
    $dolar_blue = number_format($dolar_blue, 2, '.', '');
  } else {
    // deal with error
    die('Error: El valor del dólar blue no es válido');
  }

?>