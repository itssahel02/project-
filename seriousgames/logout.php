<?php

include 'php/functions.php';

// Deze functie verwijdert de loginsessie zelf.
if(isset($_SESSION['leerid'])){
    unset($_SESSION['leerid']);
    echo 'U wordt nu uitgelogd, even een moment!';
}

//Redirect naar de inlog pagina, na 5 seconden
header('Refresh: 4; url=index.php');

?>