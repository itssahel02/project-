<!DOCTYPE html>
<head>
<?php
    include('php/functions.php');
?>
</head>
<body>       
    <div> 
        <h2>Serious games dashboard</h2>
        <?php
            if(!isset($_SESSION['leerid'])){
                header('Location: login.php');
            }
            $leveluser = getLevel();
            displayLevel($leveluser);
        ?>
    </div>
    <div>
        <br>
        <a href="rekenen.php" class="avgbutton">Rekenen</a>
        <br> 
        <br> 
        <a href="logout.php" class="avgbutton">Log uit</a>
    </div>
</body>
</html>