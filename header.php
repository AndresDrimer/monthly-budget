<?php
session_start();



if(!isset($_SESSION['language'])) {
    $_SESSION['language'] = 'espanol';
 }
 
 if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_lang'])){
    if ($_POST['submit_lang'] === 'ENGLISH') {
        $_SESSION['language'] = 'espanol';
    } else {
        $_SESSION['language'] = 'english';
    }
 }
?>

<header>

    <img src="./public/icons/Logo1.png" alt="logo" id="header-logo">

    <section>
        <?php
        if (isset($_SESSION["username"])) {
            echo "<button id='btn-hamburguer'><img src=./public/icons/hamburguer-menu.png alt=hamburguer-menu id=hamburguer-menu></button>";

            echo "<nav id='nav-medium-devices-and-above'>";

            echo "<p id='hello-user-in-modal'>:) " . ($_SESSION["language"] == "espanol" ? "Hola" : "Hi") . " " . ucfirst($_SESSION["username"]) . " ! </p>";

          
          if (!empty($_SESSION["group_id"])) {
            echo (strpos($_SERVER["REQUEST_URI"], "dashboard.php") !== false ? "<a href=stats.php>📈 Stats</a>" : "<a href=dashboard.php>⚡ Home</a>") . "<br>";
          }
          if (!empty($_SESSION["group_id"])) {
          echo (strpos($_SERVER["REQUEST_URI"], "settings.php") !== false ? "<a href=stats.php>📈 Stats</a>" : "<a href=settings.php>⚡ Settings</a>");
        }

          echo"<form action='{$_SERVER["PHP_SELF"]}' method='post'>
            <input type='submit' name='logout' value='❌ Logout' id='logout'>
          </form></nav>";
        } else {
            echo " <div>
            <nav>
                  <ul id='ul-nav-login-register'>
                       <li><a href=login.php>LOGIN</a></li>
                       <li>〰</li>

                      <li><a href=register.php>REGISTER</a></li>
                  </ul>
                  </nav>
                  <form action='{$_SERVER["PHP_SELF"]}' method='post'>
                  <input type='hidden' name='form_id' value='form_lang_selector'>
                  <input type='submit' id='submit_lang' name='submit_lang' value='" . strtoupper($_SESSION['language']) . "'>
                </form></div>";
        }
        ?>
    </section>

    <section id="header-modal" class="invisible">
        <button id="header-modal-close-btn">&#65336</button>
        <img src="./public/icons/Logo1.png" alt="logo-modal" id="logo-modal">
        <?php
        echo "<nav>
        <p id='hello-user-in-modal'>:) " . ($_SESSION["language"] == "espanol" ? "Hola" : "Hi") . " " . $_SESSION["username"] . " ! </p>"
                   . (strpos($_SERVER["REQUEST_URI"], "dashboard.php") !== false ? "<a href=stats.php>📈 Stats</a>" : "<a href=dashboard.php>⚡ Home</a>") . "<br>" .
                   (strpos($_SERVER["REQUEST_URI"], "settings.php") !== false ? "<a href=stats.php>📈 Stats</a>" : "<a href=settings.php>⚡ Settings</a>") .
                  "<form action='{$_SERVER["PHP_SELF"]}' method='post'>
                      <input type='submit' name='logout' value='❌ Logout' id='logout'>
                  </form></nav>";
        ?>

   
    </section>

</header>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['logout'])) {
    session_start();
    session_destroy();
    header('Location: login.php');
    exit;
}

?>