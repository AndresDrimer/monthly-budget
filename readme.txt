MORE FEATURES:

- Actualizar ingreso de transac para permitir cambiar la fecha de NOW() a otra (aca ver como actualiza valor dolarblue)

- Quitar la marca de agua de la imagen de fondo.

- Quzias hacer selector de idioma ingles / español ?


-Registrar DOMINIO y delegar

Pagina: 
SETTINGS: tenes para cambiar user y pass de usuario y del grupo, podes enviar el cambiod e grupo por whatsapp si queres. 
Selector idioma=?
Mandar info por whatsapp?

//////////

Ahora guarda valor de dolarblue_venta cuando guarda toda operacion....

me queda pensar que hacer cuandop se cambia una fecha, eso queda a resolver (tiene una fechaActualizacion disponible la API).

BORRAR DATOS y ACTUALIZAR !


- Se sugiere otro modo de realizar acciones con la base de datos, usando consultas preparadas, de modo que : 
$sql =  "SELECT * FROM users WHERE username = '$username'";
 $result = mysqli_query($conn, $sql);

terminaria siendo: 
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

Fui cambiando varias queries segun esto, pero la de login no anduvo y la deje como estaba, investigarla un poco...

CAMBIE OK: edit.php, INSERT income y expense (de dashboard.php) y register.php
Podria probar con create.php, que tambien tiene un password hash, a ver si se soluciona el tema....
