<?php

//starts session for login
session_start();

function Connect(){
    $servername = "localhost";
    $username = "root";
    $password = "";
    $db = 'serious_games';
    //connection to the database  
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$db", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e){
        echo "Connection failed: " . $e->getMessage();
    }
    
    return $conn;
}

function LoginSend(){

    $conn = Connect();
    
    if(!isset($_SESSION['leerid'])){
        if(isset($_POST['gebruikersnaam']) && isset($_POST['wachtwoord'])){
            $user = $_POST['gebruikersnaam'];
            $pass = sha1($_POST['wachtwoord']);
            
            $select = "SELECT * FROM leeruser WHERE username='$user' AND password='$pass'";
            $stmt1 = $conn ->query($select);
            $count = $stmt1 ->fetchAll();
            $counter = count($count);

            if($counter = 1){
                foreach($count as $leerid){
                    $_SESSION['leerid'] = $leerid['leerid'];
                }
                $_SESSION['logincheck']['leerid'] = $leerid['leerid'];
                $_SESSION['logincheck']['user'] = $_POST['gebruikersnaam'];
                $_SESSION['logincheck']['pass'] = $_POST['wachtwoord'];
                echo 'U bent ingelogd!<br>';
                echo 'U wordt geredirect naar uw pagina in een aantal seconden!';
                header('Refresh: 5; url=index.php');
            }
            else{
                header('Location: login.php?message=incorrect');
            }
        }
    }
    else{
        echo 'U bent al ingelogd!<br>';
        echo 'U wordt geredirect naar uw pagina in een aantal seconden!';
        header('Refresh: 5; url=index.php');
    }
}

function RegisterSenddbone(){

    $conn = Connect();

    //used for db
    $vnaam = $_POST['vletter'];
    $anaam = $_POST['anaam'];
    $user = $_POST['gebruikersnaam'];
    $pass = sha1($_POST['wachtwoord']);
    $email = $_POST['email'];

    if(isset($_POST['toevoegen'])){
        if(!empty($vnaam) && !empty($anaam)){
            $select = "SELECT * FROM leeruser WHERE username='$user' AND email='$email'";
            $stmt = $conn ->query($select);
            $count = $stmt ->fetchAll();
            $counter = count($count);
            echo $counter;
            if($counter < 1){
                $query = "INSERT INTO leeruser (vletter,anaam,username,password,email) VALUES ('$vnaam','$anaam','$user','$pass','$email');";
                $result = $conn ->query($query);
                $query2 = "INSERT INTO userlevel (experience) VALUES ('0');";
                $result2 = $conn ->query($query2);
                header('Location: index.php');
            }
            else{
                header('Location: register.php?message=duplicateentry');
            }
        }
    }

}

function getLevel(){
    $conn = Connect();
    $leerid = $_SESSION['logincheck']['leerid'];

    $result = $conn->query("SELECT * FROM userlevel WHERE leerid = '$leerid'");
    return $result -> fetchAll (PDO::FETCH_ASSOC);
}

function levelReq(&$exp){
    $conn = Connect();

    $result = $conn->query("SELECT * FROM level_req as l WHERE require_to_next > '$exp'");
    return $result -> fetchAll (PDO::FETCH_ASSOC);
}

function displayLevel(&$leveluser){
    $user = $_SESSION['logincheck']['user'];
    
    echo '<div class="leveluser">';
    echo "Welkom terug, user " . $user . "<br>";
    foreach($leveluser as $u){
        $exp = $u['experience'];
        $level = levelReq($exp);
        echo "Jij bent nu op de rank: '<b>" . $level['0']['rank'] . "</b>'<br>";
        if(isset($level['1']['rank'])){
            $nextreq = $level['0']['require_to_next'] - $exp;
            echo "Nog <b>" . $nextreq . " exp</b> tot de volgende rank<br>";
        }
        else{
            echo "Wow, jij bent een echte Asian<br>";
        }
    }
    echo '</div>';
}

function correctAns(){
    $conn = Connect();
    $leerid = $_SESSION['logincheck']['leerid'];

    $query = "UPDATE userlevel set experience = experience + 20 WHERE leerid = '$leerid'";
    $conn->query($query);
}

function wrongAns(){
    $conn = Connect();
    $leerid = $_SESSION['logincheck']['leerid'];

    $query = "UPDATE userlevel set experience = experience - 100 WHERE leerid = '$leerid'";
    $conn->query($query);
}

?>