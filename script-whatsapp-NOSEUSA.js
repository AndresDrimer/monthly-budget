//NO LO ESTOY USANDO ESTO POR AHORA, DEJO ARCHIVO POR SI SE AGREGARA ESTE FEATURE A FUTURO.

    document.getElementById('form_group').addEventListener('submit', function(e) {
        e.preventDefault();
  
        var telNumber = document.querySelector('input[name="tel_number"]').value;
        var groupName = document.querySelector('input[name="group_name"]').value;
        var groupPassword = document.querySelector('input[name="group_password"]').value;
        var text = 'INFO DEL GRUPO - Link: https://monthly-budget - Nombre del grupo: ' + groupName + ' - Contraseña: ' + groupPassword;
  
        if (telNumber) {
            var text = 'INFO DEL GRUPO - Link: https://monthly-budget - Nombre del grupo: ' + groupName + ' - Contraseña: ' + groupPassword;
            window.open('https://wa.me/' + telNumber + '?text=' + encodeURIComponent(text), '_blank');
         }
    });




  
//<hr id="linea-punteada"> 
//<p class="white less-width">Pro-TIP: Podés mandarte a vos mismo los datos por Whatsapp y reenviarlos a quien quieras desde allí 😉. <br>Sólo completa tu número en la casilla de abajo, o dejala en blanco como está si preferís no usarla.</p>

//<form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post" id="form-whatsapp">
//<input type="hidden" name="form_id" value="form_whatsapp">
//<label>Tel: </label>
//<input type="number" name="tel_number" placeholder="1161234567"> 
//<a href="#" target="_blank" id="a_link">
 <button>Enviar mensaje de WhatsApp</button>
//</a>
//</form>
