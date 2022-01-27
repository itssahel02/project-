<!DOCTYPE html>
<head>
<?php
    include('php/functions.php');
?>
</head>
<body>       
    <div> 
        <h2>Serious games login</h2>
        <?php
            if(isset($_SESSION['leerid'])){
                header('Location: dash.php');
            }
        ?>
        <div class='textloginregister'>
            <form method="post" action="loginsend.php">
                <p>
                    <label for="gebruikersnaam">Gebruikersnaam:</label>
                    <input type="text" name="gebruikersnaam" id="gebruikersnaam" />
                </p>
                <p>
                    <label for="pass">Wachtwoord:</label>
                    <input type="password" name="wachtwoord" id="wachtwoord" />
                </p>
                <p>
                    <input name="login" type="submit" value="Inloggen" />
                </p>
                <p>
                    Nog geen account? <a href='register.php'>Registreer hier</a>
                </p>
            </form>
            <?php
                if(isset($_GET['message']) && $_GET['message'] == 'incorrect'){
                    echo 'De gebruikersnaam/wachtwoord klopt niet, voer een andere gebruikersnaam/wachtwoord in.';
                }
                elseif(isset($_GET['message']) && $_GET['message'] == 'invalid'){
                    echo 'De gebruiker bestaat niet.';
                }
            ?>
        </div>
    </div>
</body>
</html>