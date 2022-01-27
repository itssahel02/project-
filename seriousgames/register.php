<!DOCTYPE html>
<head>
<?php
    include('php/functions.php');
?>
</head>
<body>   
    <div>
            <h1>Registreren</h1> 
            <form method="post" action="registersend.php"> 
                <table width="500" border="0" cellspacing="1" cellpadding="2"> 
                <tr> 
                    <td width="200">Voorletter(s)</td> 
                    <td><input name="vletter" type="text" id="vletter" placeholder="A. B." maxlength="45" required> *</td> 
                </tr> 
                <tr> 
                    <td>Tussenvoegsel en achternaam</td> 
                    <td><input name="anaam" type="text" id="anaam" placeholder="van Wateren" maxlength="45" required> *</td> 
                </tr>
                <tr> 
                    <td>Gebruikersnaam</td> 
                    <td><input name="gebruikersnaam" type="text" id="gebruikersnaam" placeholder="Gebruikersnaam" required> *</td> 
                </tr> 
                <tr> 
                    <td>Wachtwoord</td> 
                    <td><input name="wachtwoord" type="password" id="wachtwoord" placeholder="Wachtwoord" required> *</td> 
                </tr> 
                <tr> 
                    <td>Email</td> 
                    <td><input name="email" type="email" id="email" placeholder="youremail@gmail.com" required> *</td> 
                </tr> 
                <tr> 
                    <tr> 
                        <td></td><td><input name="agree" type="checkbox" id="agree" required> Ik ga akkoord met de <a href="http://bit.ly/b85fa2s96" target="_blank">voorwaarden</a> *</td>
                    </tr>
                    <tr>
                        <td></td><td><input name="toevoegen" type="submit" id="toevoegen" value="Registreren"></td> 
                    </tr>
                </tr>
                <tr>
                    <td></td><td>Heeft u al een account? <a href='login.php'>Log In</a></td>
                </tr>
                </table>
            </form>
            <br>
            <?php
            if(isset($_GET['message']) && $_GET['message'] == 'duplicateentry'){
                echo 'De gebruikersnaam/email is al in gebruik. Probeer een andere gebruikersnaam/email.';
            }
            ?>
        </div>
</body>
</html>