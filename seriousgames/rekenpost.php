<!DOCTYPE html>
<head>
<?php
    include('php/functions.php');
?>
</head>
<body>       
    <div> 
        <?php
            if(!isset($_SESSION['leerid'])){
                header('Location: login.php');
            }
            else{
                if($_POST['answer']){
                    if($_POST['answer'] == $_POST['userans']){
                        correctAns();
                        header('Location: rekenen.php?message=correct');
                    }
                    else{
                        wrongAns();
                        header('Location: rekenen.php?message=incorrect');
                    }
                }
            }
        ?>
    </div>
</body>
</html>