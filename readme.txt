MORE FEATURES:

1 - Actualizar ingreso de transac para permitir cambiar la fecha de NOW() a otra (aca ver como actualiza valor dolarblue) [Ahora guarda valor de dolarblue_venta cuando guarda toda operacion.... me queda pensar que hacer cuandop se cambia una fecha, eso queda a resolver (tiene una fechaActualizacion disponible la API).]

2 - Quitar la marca de agua de la imagen de fondo.

3 - Quzias hacer selector de idioma ingles / español ? en settings o header? como encarar esto??

4 -Registrar DOMINIO y delegar

5- BORRAR DATOS y ACTUALIZAR con varios meses!

6- Ultimo ajuste de RESPONSIVE device sizes

7 - Chequear consultas preparads para todos los pedidos a la DB [- Se sugiere otro modo de realizar acciones con la base de datos, usando consultas preparadas, de modo que : 
$sql =  "SELECT * FROM users WHERE username = '$username'";
 $result = mysqli_query($conn, $sql);

terminaria siendo: 
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

Fui cambiando varias queries segun esto, pero la de login no anduvo y la deje como estaba, investigarla un poco...

CAMBIE OK: edit.php, INSERT income y expense (de dashboard.php) y register.php
Podria probar con create.php, que tambien tiene un password hash, a ver si se soluciona el tema....]





