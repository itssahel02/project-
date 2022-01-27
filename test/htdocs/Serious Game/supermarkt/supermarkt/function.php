<?php
session_start();
//auteur=Ricardo Autar

function connect(){
    $servername = "localhost"; 
    $username = "root";
    $password = "";
    $DB="sup";
    try{
         $conn = new PDO("mysql:host=$servername; dbname=$DB;", $username, $password,);
         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e){
        echo "connection failed: ". $e->getMessage();
    }
    return $conn;
}

function GetCatData($cat){
    $conn=  connect();

    if($cat == 'compleet'){
        $result=$conn->query("SELECT * FROM categorie");
    }
    elseif($cat == ''){
        $result = $conn->query("SELECT * FROM categorie");
    }
    else{
        $result = $conn->query("SELECT * FROM categorie WHERE categorie_nr = '$cat'");
    }
   return $datab = $result->fetchAll(PDO::FETCH_ASSOC);
}

function GetProdData($cat2){
    $conn=  connect();

    if(empty($cat2)){
        $result = $conn->query("SELECT * FROM producten");
    }
    elseif(!empty($cat2)){
        $result = $conn->query("SELECT * FROM producten WHERE categorie_nr = '$cat2'");
    }
   return $datab = $result->fetchAll(PDO::FETCH_ASSOC);
}

function GetCat(){
    $conn= connect();
    $datacat=$conn->query("SELECT DISTINCT categorie_naam, categorie_nr FROM categorie");
    $getcat= $datacat->fetchAll(PDO::FETCH_ASSOC);

    foreach($getcat as $row){
        echo '<div><a href="index.php?categorie_naam='. $row['categorie_nr']. '">' . $row['categorie_naam'] . '</a></div>';
    }
}

function assortiment($datab){

        echo "<div class ='flex-auto col-span-3'>";
            foreach($datab as $row){
            echo"<div class=' w-full max-w-sm mx-auto rounded-md shadow-md overflow-hidden bg-green-600 border-8 border-gray-900'>";
            echo"<div class=' mx-auto px-6'<br><br>";
            echo"<img class='h-20 w-20 object-cover rounded' src='$row[product_afbeelding]'><br>";
            echo "<b>$row[product_naam]</b> </img><br>";
            echo "<div class='text-gray-600'>$row[product_prijs] <br></div>";
            echo "<form action='voorraad.php?id=$row[product_nr]' method='post'><input type='hidden' name='productnr' value='$row[product_nr]'></input><input type='hidden' name='productnaam' value='$row[product_naam]'></input><input type='hidden' name='productprijs' value='$row[product_prijs]'></input><input type='number' value='1' name='aantal'><br><br><input type='submit' name='toevoegen' class='p-2 rounded-full bg-blue-600 text-white mx-5 -mb-4 hover:bg-blue-500 focus:outline-none focus:bg-blue-500' value='Toevoegen aan Winkelwagen'></form></div><br>";
            echo"</div>";
        }
        echo "</div>";
    }   
    function bestelProduct (){
        //Initialisatie
            if(isset($_POST['toevoegen'])){
                if(isset($_SESSION['winkelwagen'][$_POST['productnr']])){
                    $_SESSION['winkelwagen'][$_POST['productnr']]['item_aantal'] = $_SESSION['winkelwagen'][$_POST['productnr']]['item_aantal'] + $_POST['aantal'];
                }
                else{
                    $item_array = array(
                        'item_id'            =>    $_POST["productnr"],
                        'item_naam'            =>    $_POST["productnaam"],
                        'item_prijs'        =>    $_POST["productprijs"],
                        'item_aantal'        =>    $_POST["aantal"]
                    );
                    $_SESSION['winkelwagen'][$_POST['productnr']] = $item_array;
                }
                header('Refresh: 0, url=winkelwagen.php');
            }
            elseif(isset($_POST['aanpassen'])){
                if($_POST['aantal'] > 0){
                    $_SESSION['winkelwagen'][$_POST['productnr']]['item_aantal'] = $_POST['aantal'];
                }
                else{
                    unset($_SESSION['winkelwagen'][$_POST['productnr']]);
                }
                header('Refresh: 0, url=winkelwagen.php');
            }
    }
    function printtabel(){
        //$conn= connect();
        //$sql="SELECT * FROM product";
    //$result= $conn->query($sql);
        $data= GetData('');
       
    echo "<table border=1>";
    $totaalgeld = 0;
    //while ($row1= $result->fetch()){
    foreach($data as $row1){
        echo "<tr>";
        echo "<td>" .  $row1['naam'] . "</td>";
        echo "<td>" .  $row1['prijs'] . "</td>";
        echo "<td>" .  $row1['voorraad'] . "</td>";


        $totaal = 0;

        $totaal = $row1['prijs'] * $row1['voorraad'];
         echo "<td>€ $totaal</td>";
         echo "</tr>";
        $totaalgeld = $row1['prijs'] * $row1['voorraad'] + $totaalgeld;


    }

    echo "<tr><td>Totaal:<td><td><td>€ " . "$totaalgeld";
    echo "</table>";
    }

    function RegisterForm() {

        $conn = Connect();
    
        $username = $_POST["username"];
        $password = $_POST["password"];

    
        if(!empty($password)) {
            
            $hashword = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
            $result = $conn->query("SELECT MAX(klant_nr) as klant_id FROM klanten");
            $data = $result->fetchAll(PDO::FETCH_ASSOC);
            foreach($data as $row){
                $klantid = $row['klant_id'];
                $klantid++;
            }
    
            $data = [
                'klantid' => $klantid,
                'username' => $username,
                'password' => $hashword,
            ];
            $sql_one = "INSERT INTO klanten(klant_nr, klant_gebruikersnaam, klant_wachtwoord) VALUES (:klantid, :username, :password)";
            $statement= $conn->prepare($sql_one);
            $statement->execute($data);
    
        }
    }

function WinkelwagenIndex(){
    echo '<table style="text-align:center; border: solid black; width: 575px; background-color:white; font-size: 15px;" border="1">';
    echo '<tr>';
    echo '<th width="3%">Artikelnr.</th>';
    echo '<th width="40%">Product</th>';
    echo '<th width="5%">Aantal</th>';
    echo '<th width="13%">Prijs p/stuk</th>';
    echo '<th width="13%">Totaal</th>';
    echo '<th></th>';
    echo '</tr>';
    $bedrag = 0;
    if(isset($_SESSION['winkelwagen'])){
        foreach($_SESSION['winkelwagen'] as $val){
            echo '<form method="post" action="voorraad.php">';
            echo "<tr><td><input type='hidden' id='productnr' name='productnr' value='$val[item_id]'>$val[item_id]</input></td>";
            echo "<td>$val[item_naam]</td>";
            echo "<td><input type='number' id='aantal' name='aantal' value='$val[item_aantal]' min='0' max='999'></input></td>";
            echo "<td>$val[item_prijs]</td>";
            $tot = $val['item_prijs'] * $val['item_aantal'];
            echo "<td>€ " . number_format($tot, 2) . "</td><td><input type='submit' name='aanpassen' id='aanpassen' value='Aanpassen'></input></td></tr>";
            $bedrag = $bedrag + $tot;
            echo '</form>';
        }
    }
    echo '<tr colspan="4">';
    echo '<td colspan="4"></td><td style="border-top:3px solid black;">€ ' . number_format($bedrag , 2) . '</td>';
    echo '</table>';
}


function medewerkerwijzig($data){

    $conn = Connect();

    echo '<table class="min-w-max w-full table-auto >';

    echo '<tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">';
    echo '<th class="py-3 px-6 text-left">ID</th><th class="py-3 px-6 text-left">Gebruikersnaam</th><th class="py-3 px-6 text-left">Voornaam</th><th class="py-3 px-6 text-left">Achternaam</th><th class="py-3 px-6 text-left">Wachtwoord</th><th class="py-3 px-6 text-left">Rol</th><th class="py-3 px-6 text-left">Versturen</th><th class="py-3 px-6 text-left">Verwijderen</th>';

    echo '<tr class="">';
    echo '<form method="post" action="Verwerking.php?action=medewerker">';
    echo '<td><input type="hidden" name="med_id" value="add"></input></td>';
    echo '<td><input type="text" name="gebruikersnaam" placeholder="gebruikersnaam..." required></input></td>';
    echo '<td><input type="text" name="voornaam" placeholder="voornaam..." required></input></td>';
    echo '<td><input type="text" name="achternaam" placeholder="achternaam..." required></input></td>';
    echo '<td><input type="text" name="wachtwoord" placeholder="wachtwoord..." required></input></td>';
    echo '<td><input type="text" name="rollen" placeholder="rollen..." required></input></td>';
    echo '<td><input type="submit" name="versturen" value="Versturen"></input></td>';
    echo '<td><input type="submit" name="delete" value="Verwijderen"></input></td>';
    echo '</form>';
    echo '</tr>';

    foreach($data as $row){
        echo '<tr class="">';
        echo '<form method="post" action="Verwerking.php?action=medewerker">';
        echo '<td><input type="hidden" name="med_id" value="' . $row['medewerker_nr'] . '">' . $row['medewerker_nr'] . '</input></td>';
        echo '<td><input type="text" name="gebruikersnaam" value="' . $row['medewerker_gbr'] . '" required></input></td>';
        echo '<td><input type="text" name="voornaam" value="' . $row['medewerker_voornaam'] . '" required></input></td>';
        echo '<td><input type="text" name="achternaam" value="' . $row['medewerker_achternaam'] . '" required></input></td>';
        echo '<td><input type="text" name="wachtwoord" value="' . $row['medewerker_ww'] . '" required></input></td>';
        echo '<td><input type="text" name="rollen" value="' . $row['rollen'] . '" required></input></td>';
        echo '<td><input type="submit" name="versturen" value="Versturen"></input></td>';
        echo '<td><input type="submit" name="delete" value="Verwijderen"></input></td>';
        echo '</form>';
        echo '</tr>';
    }
}

function addmedewerker(){

    $conn = Connect();
    
    if(isset($_POST['delete']) && $_POST['delete'] == 'Verwijderen'){
    
        $a = $_POST["med_id"];
        $sql=("DELETE FROM medewerkers WHERE medewerker_nr = '$a'");
        $conn->query($sql);

    }elseif($_POST['med_id'] == 'add'){
        
        $sql = "SELECT MAX(medewerker_nr) as medwnum FROM medewerkers";
        $stmt = $conn->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $a = $result['medwnum']+1;
        $b = $_POST['voornaam'];
        $c = $_POST['achternaam'];
        $d = $_POST['gebruikersnaam'];
        $e = password_hash($_POST['wachtwoord'], PASSWORD_DEFAULT);
        $f = $_POST['rollen'];

        $data = [
            'id' => $a,
            'voornaam' => $b,
            'achternaam' => $c,
            'gebruikersnaam' => $d,
            'wachtwoord' => $e,
            'rollen' => $f
        ];
        $sql = "INSERT INTO medewerkers(medewerker_nr, medewerker_voornaam, medewerker_achternaam, medewerker_gbr, medewerker_ww, rollen) VALUES (:id, :voornaam, :achternaam, :gebruikersnaam, :wachtwoord, :rollen)";
        $statement = $conn->prepare($sql);
        $statement->execute($data);

    }elseif($_POST['med_id'] != 'add'){
        
        $a = $_POST['med_id'];
        $b = $_POST['voornaam'];
        $c = $_POST['achternaam'];
        $d = $_POST['gebruikersnaam'];
        $e = password_hash($_POST['wachtwoord'], PASSWORD_DEFAULT);
        $f = $_POST['rollen'];

        $data = [
            'id' => $a,
            'voornaam' => $b,
            'achternaam' => $c,
            'gebruikersnaam' => $d,
            'wachtwoord' => $e,
            'rollen' => $f
        ];
        $sql = "UPDATE medewerkers SET medewerker_voornaam = :voornaam, medewerker_achternaam = :achternaam, medewerker_gbr = :gebruikersnaam, medewerker_ww = :wachtwoord, rollen = :rollen WHERE 'medewerker_nr = :id'";
        $statement = $conn->prepare($sql);
        $statement->execute($data);

    }

}

function loginForm () {

    $conn = Connect();

    $user = $_POST['username'];
    $userPassword = $_POST['password']; //Wachtwoord uit form
    echo $user;
    $hash = $conn->query("SELECT klant_wachtwoord FROM klanten WHERE klant_gebruikersnaam='$user'");
    $passwordHash = $hash->fetchAll(PDO::FETCH_ASSOC); //Wachtwoord uit database
    var_dump($passwordHash);
    $hash = $passwordHash[0]["klant_wachtwoord"];


    if(password_verify($userPassword, $hash)) {
        echo 'Password is correct, logged in!';
        $_SESSION['login'] = array('username' => $user, 'password' => $userPassword);
        header('Location: index.php');
        exit;
    }else{
        echo 'Password is wrong, try again';
    }
}

function mwloginForm () {

    $conn = Connect();

    $user = $_POST['username'];
    $userPassword = $_POST['password']; //Wachtwoord uit form

    $hash = $conn->query("SELECT medewerker_ww, rollen FROM medewerkers WHERE medewerker_gbr='$user'");
    $passwordHash = $hash->fetchAll(PDO::FETCH_ASSOC); //Wachtwoord uit database
    $hash = $passwordHash[0]["medewerker_ww"];
    $nimda = $passwordHash[0]["rollen"];


    if(password_verify($userPassword, $hash)) {
        echo 'Password is correct, logged in!';
        $_SESSION['login'] = array('username' => $user, 'password' => $userPassword, 'admin' => $nimda);
        header('Location: admindhb.php');
        exit;
    }else{
        echo 'Password is wrong, try again';
    }
}

function productwijzig($data){

    $conn = Connect();
    $cat = $conn->query("SELECT * FROM categorie");

    echo '<table class="min-w-max w-full table-auto ">';

    echo '<tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">';
    echo '<th class="py-3 px-6 text-left">ID</th><th class="py-3 px-6 text-left">Product categorie</th><th class="py-3 px-6 text-left">Product Naam</th><th class="py-3 px-6 text-left">Product image</th><th class="py-3 px-6 text-left">Prijs</th><th class="py-3 px-6 text-left">Voorraad</th><th class="py-3 px-6 text-left">Versturen</th><th class="py-3 px-6 text-left">Verwijderen</th></tr>';

    echo '<tr class="">';
    echo '<form method="post" action="Verwerking.php?action=wijzigproduct">';
    echo '<input type="hidden" name="new" value="add">';
    echo '<td></td>';
    echo '<td><select id="categorie" name="cat_id">';
    echo '<option selected>--Please selecteer een categorie--</option>';
    foreach($cat as $option){
        echo '<option value="' . $option['categorie_nr'] . '">' . $option['categorie_naam'] . '</option>';
    } 
    echo '</select></td>';
    echo '<td><input type="text" name="product_naam" placeholder="Product Naam..."></td>';
    echo '<td><input type="file" name="product_img" placeholder="Product image..."></td>';
    echo '<td><input type="number" step="0.01" name="prijs" placeholder="prijs..."></td>';
    echo '<td><input type="number" step="0.01" name="voorraad" placeholder="voorraad..."></input></td>';
    echo '<td><input type="submit" value="Versturen"></td>';
    echo '</form>';
    echo '</tr>';


    foreach($data as $row){

        $conn = Connect();
        $cat = $conn->query("SELECT * FROM categorie");

        echo '<form method="post" action="Verwerking.php?action=wijzigproduct">';
        echo '<td><input type="hidden" name="product_id" value="' . $row['product_nr'] . '">' . $row['product_nr'] . '</input></td>';
        echo '<td><select id="categorie" name="cat_id">'; 
        foreach($cat as $option){
            if($row['categorie_nr'] == $option['categorie_nr']){
                echo '<option value="' . $option['categorie_nr'] . '" selected>' . $option['categorie_naam'] . '</option>';
            }else{
                echo '<option value="' . $option['categorie_nr'] . '">' . $option['categorie_naam'] . '</option>';
            }
        }
        echo '</select></td>';
        echo '<td><input type="text" name="product_naam" value="' . $row['product_naam'] . '"></input></td>';
        echo '<td><input type="file" name="product_img" value="' . $row['product_afbeelding'] . '"></input></td>';
        echo '<td><input type="number" step="0.01" name="prijs" value="' . $row['product_prijs'] . '"></input></td>';
        echo '<td><input type="number" step="0.01" name="voorraad" value="' . $row['voorraad'] . '"></input></td>';
        echo '<td><input type="submit" name="versturen" value="Versturen"></input></td>';
        echo '<td><input type="submit" name="delete" value="Verwijderen"></input></td>';
        echo '</form>';
        echo '</tr>';
    }
    echo '</table';
}

function addProduct(){
    
    $conn = Connect();
    
    if(isset($_POST['new']) && $_POST['new'] == 'add'){
    
        $sql = "SELECT MAX(product_nr) as prodnum FROM producten";
        $stmt = $conn->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $a = $result['prodnum']+1;
        $b = $_POST["cat_id"];
        $c = $_POST["product_naam"];
        $d = $_POST["product_img"];
        $e = $_POST["prijs"];
        $f = $_POST["voorraad"];

        $datab = [
            'product_nr' => $a,
            'product_cat' => $b,
            'product_naam' => $c,
            'product_img' => $d,
            'prijs' => $e,
            'voorraad' => $f
        ];
        $sql = "INSERT INTO producten(product_nr, categorie_nr, product_naam, product_afbeelding, product_prijs, voorraad) VALUES (:product_nr, :product_cat, :product_naam, :product_img, :prijs, :voorraad)";
        $statement = $conn->prepare($sql);
        $statement->execute($datab);
    
    }elseif(isset($_POST['delete']) && $_POST['delete'] == 'Verwijderen'){
        
        $a = $_POST["product_id"];
        $sql=("DELETE FROM producten WHERE product_nr = $a");
        $conn->query($sql);

    }elseif(isset($_POST['versturen']) && $_POST['versturen'] == 'Versturen'){
    
        $a = $_POST["product_id"];
        $b = $_POST["cat_id"];
        $c = $_POST["product_naam"];
        $d = $_POST["product_img"];
        $e = $_POST["prijs"];
        $f = $_POST["voorraad"];

        if(empty($_POST['product_img'])){
            $datac = [
                'product_nr' => $a,
                'product_cat' => $b,
                'product_naam' => $c,
                'prijs' => $e,
                'voorraad' => $f
            ];
            $sql = "UPDATE producten SET categorie_nr = :product_cat, product_naam = :product_naam, product_prijs = :prijs, voorraad = :voorraad WHERE product_nr = :product_nr";
            $statement = $conn->prepare($sql);
            $statement->execute($datac);
        }else{
            $datac = [
                'product_nr' => $a,
                'product_cat' => $b,
                'product_naam' => $c,
                'product_img' => $d,
                'prijs' => $e,
                'voorraad' => $f
            ];
            $sql = "UPDATE producten SET categorie_nr = :product_cat, product_naam = :product_naam, product_afbeelding = :product_img, prijs = :prijs, voorraad = :voorraad WHERE product_nr = :product_nr";
            $statement = $conn->prepare($sql);
            $statement->execute($datac);
        }
    }
}

function categoriewijzig($data){

    echo '<table class="min-w-max w-full table-auto">';

    echo '<tr class="tafeltijd-item">';
    echo '<td>ID</td><td>Categorie Naam</td><td>Versturen</td>';

    echo '<tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">';
    echo '<form method="post" action="Verwerking.php?action=toecategorie">';
    echo '<td></td><input type="hidden" name="new" value="add">';
    echo '<td><input type="text" name="cat_name" placeholder="Product Naam..."></td>';
    echo '<td><input type="submit" name="versturen" value="Versturen"></input></td>';
    echo '</form>';
    echo '</tr>';


    foreach($data as $row){
        echo '<tr>';
        echo '<form method="post" action="Verwerking.php?action=toecategorie">';
        echo '<td><input type="hidden" name="categorie_nr" value="' . $row['categorie_nr'] . '">' . $row['categorie_nr'] . '</input></td>';
        echo '<td><input type="text" name="categorie_naam" value="' . $row['categorie_naam'] . '"></input></td>';
        echo '<td><input type="submit" name="versturen" value="Versturen"></input></td>';
        echo '</form>';
        echo '</tr>';
    }

}

function toecategorie(){
    
    $conn = Connect();
    
    if(isset($_POST['new']) && $_POST['new'] == 'add'){
    
        $sql = "SELECT MAX(categorie_nr) as catnum FROM categorie";
        $stmt = $conn->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $a = $result['catnum']+1;
        $c = $_POST["cat_name"];


        $data = [
            'cat_nr' => $a,
            'cat_name' => $c,

        ];
        $sql = "INSERT INTO categorie(categorie_nr, categorie_naam) VALUES (:cat_nr, :cat_name)";
        $statement = $conn->prepare($sql);
        $statement->execute($data);

    }elseif(isset($_POST['versturen']) && $_POST['versturen'] == 'Versturen'){
    
        $a = $_POST['cat_id'];
        $c = $_POST["cat_name"];


        if(empty($_POST['product_afbeelding'])){
            $datab = [
                'cat_nr' => $a,
                'cat_name' => $c
            ];
            $sql = "UPDATE producten SET categorie_naam = :cat_name WHERE categorie_nr = :cat_nr";
            $statement = $conn->prepare($sql);
            $statement->execute($datab);

        }else{
            $datab = [
                'cat_nr' => $a,
                'cat_name' => $c,
                'cat_image' => $d
            ];
            $sql = "UPDATE producten SET categorie_naam = :cat_name WHERE categorie_nr = :cat_nr";
            $statement = $conn->prepare($sql);
            $statement->execute($datab);

        }
    }
}

function klantwijzig($data){

    $conn = Connect();

    echo '<table class="min-w-max w-full table-auto">';

    echo '<tr class="tafeltijd-item">';
    echo '<tr><td>ID</td><td>Voornaam</td><td>Achternaam</td><td>Gebruikersnaam</td><td>Wachtwoord</td><td>E-mail</td><td>Versturen</td><td>Verwijderen</td></tr>';

    foreach($data as $row){
        echo '<tr class="tafeltijd-item">';
        echo '<form method="post" action="Verwerking.php?action=klantwijzig">';
        echo '<td><input type="hidden" name="klant_nr" value="' . $row['klant_nr'] . '">' . $row['klant_nr'] . '</input></td>';
        echo '<td><input type="text" name="voornaam" value="' . $row['klant_voornaam'] . '" required></input></td>';
        echo '<td><input type="text" name="achternaam" value="' . $row['klant_achternaam'] . '" required></input></td>';
        echo '<td><input type="text" name="gebruikersnaam" value="' . $row['klant_gebruikersnaam'] . '"></input></td>';
        echo '<td><input type="text" name="wachtwoord" value="' . $row['klant_wachtwoord'] . '"></input></td>';
        echo '<td><input type="text" name="mail" value="' . $row['klant_email'] . '"></input></td>';
        echo '<td><input type="submit" name="versturen" value="Versturen"></input></td>';
        echo '<td><input type="submit" name="delete" value="Verwijderen"></input></td>';
        echo '</form>';
        echo '</tr>';
    }
}

function toeklant(){

    $conn = Connect();
    
    if(isset($_POST['delete']) && $_POST['delete'] == 'Verwijderen'){
    
        $a = $_POST["klant_nr"];
        $sql=("DELETE FROM klanten WHERE klant_nr = $a");
        $conn->query($sql);

    }else{

        $a = $_POST['klant_nr']+1;
        $b = $_POST['voornaam'];
        $c = $_POST['achternaam'];
        $d = $_POST['gebruikersnaam'];
        $e = $_POST['wachtwoord'];
        $f = $_POST['mail'];


        $data = [
            'id' => $a,
            'voornaam' => $b,
            'achternaam' => $c,
            'gebruikersnaam' => $d,
            'wachtwoord' => $e,
            'email' => $f
        ];
        $sql = "UPDATE klanten SET klant_voornaam = :voornaam, klant_achternaam = :achternaam, klant_gebruikersnaam = :gebruikersnaam, klant_wachtwoord = :wachtwoord, klant_email = :email WHERE klant_nr = :id";
        $statement = $conn->prepare($sql);
        $statement->execute($data);

    }

}

function bestelling(){

    $conn = Connect();
    $user = $_SESSION['login']['username'];
    $result = $conn->query("SELECT klant_nr FROM klanten WHERE klant_gebruikersnaam = '$user'");
    $ids = $result->fetchAll(PDO::FETCH_ASSOC);
    if($_POST['bezorgen'] == 'afhalen'){
        $bezorgen = 0;
    }
    elseif($_POST['bezorgen'] == 'bezorgen'){
        $bezorgen = 1;
    }

    $a = date('Y-m-d-H-i-s');
    $b = $ids[0]['klant_nr'];
    $c = $bezorgen;

    $datab = [
        'datum' => $a,
        'id' => $b,
        'beez' => $c
    ];
    $sql = "INSERT INTO orders(order_datum, klant_nr, bezorgen) VALUES (:datum, :id, :beez)";
    $statement = $conn->prepare($sql);
    $statement->execute($datab);

        $result = $conn->query("SELECT MAX(order_nr) as maxorderid FROM orders WHERE klant_nr='$b'");
        $orderid = $result->fetchAll(PDO::FETCH_ASSOC);

    echo '<br>';
    foreach($orderid as $orderids){
        foreach($_SESSION['winkelwagen'] as $mand){
            $orderquery = "INSERT INTO bestelling (order_nr, product_nr, aantal) VALUES ('$orderids[maxorderid]','$mand[item_id]','$mand[item_aantal]')";
            $conn->query($orderquery);
        }
    }
    unset($_SESSION['winkelwagen']);
    //header('Refresh: 5; url=index.php');
    echo '<br>Bedankt dat u onze supermarkt heeft gekozen!';
}

function order($data){

    $conn = Connect();

    echo '<table class="min-w-max w-full table-auto ">';

    echo '<tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">';
    echo '<th class="py-3 px-6 text-left">Order ID</th><th class="py-3 px-6 text-left">Klant ID</th><th class="py-3 px-6 text-left">Datum</th><th class="py-3 px-6 text-left">Verwijderen</th></tr>';
    
    foreach($data as $row){
        echo '<tr class="tafeltijd-item">';
        echo '<form method="post" action="Verwerking.php?action=order">';
        echo '<td><input type="hidden" name="order_nr" value="' . $row['order_nr'] . '">' . $row['order_nr'] . '</input></td>';
        echo '<td><input type="hidden" name="klant_nr" value="' . $row['klant_nr'] . '">' . $row['klant_nr'] . '</input></td>';
        echo '<td><input type="hidden" name="datum" value="' . $row['order_datum'] . '">' . $row['order_datum'] . '</input></td>';
        echo '<td><input type="submit" name="delete" value="Verwijderen"></input></td>';
        echo '</form>';
        echo '</tr>';
    }
}

    function verwijderorder(){

        $conn = Connect();
        
        if(isset($_POST['delete']) && $_POST['delete'] == 'Verwijderen'){
        
            $a = $_POST["order_nr"];
            $sql=("DELETE FROM orders WHERE order_nr = $a");
            $conn->query($sql);
    
        }else{

            $a = $_POST['order_nr'];
            $b = $_POST['klant_nr'];
            $c = $_POST['order_datum'];
            $d = $_POST['bezorgen'];
        
            $data = [
                'orderid' => $a,
                'klantid' => $b,
                'datum' => $c,
                'beez' => $d
            ];
            $sql = "UPDATE orders SET order_nr = :orderid, order_datum = :datum, bezorgen = :beez WHERE klant_nr = :klantid";
            $statement = $conn->prepare($sql);
            $statement->execute($data);
    
        }
    
    }