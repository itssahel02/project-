<!DOCTYPE html>
<head>
<?php
    include('php/functions.php');
?>
</head>
<body>       
    <div> 
        <h2>Serious games - Hoofdrekenen</h2>
        <?php
            $ops = array('-', '+', '*', '/');
            shuffle($ops);
            $op = $ops[0];

            echo "<form method='post' action='rekenpost.php'>";
            if($op == '-'){
                $num1 = rand(1,100);
                $num2 = rand(1,$num1);
                echo $num1 . " " . $op . " " . $num2 . " = <input type='number' name='userans' id='userans' steps='1' required/>";
                $answer = $num1 - $num2;
            }
            if($op == '+'){
                $num1 = rand(1,100);
                $num2 = rand(1,100);
                echo $num1 . " " . $op . " " . $num2 . " = <input type='number' name='userans' id='userans' steps='1' required/>";
                $answer = $num1 + $num2;
            }
            elseif($op == '*'){
                $num1 = rand(1,10);
                $num2 = rand(1,10);
                $opsi = 'x';
                echo $num1 . " " . $opsi . " " . $num2 . " = <input type='number' name='userans' id='userans' steps='1' required/>";
                $answer = $num1 * $num2;
            }
            elseif($op == '/'){
                $num1 = rand(1,10);
                $num2 = rand(1,10);
                $num3 = $num1 * $num2;
                $num3;

                $numran = array($num1, $num2);
                shuffle($numran);

                $opsi = ':';
                $numr = $numran[0];
                echo $num3 . " " . $opsi . " " . $numr . " = <input type='number' name='userans' id='userans' steps='1' required/>";
                $answer = $num3 / $numr;
            }
            echo "<input type='hidden' name='answer' value='$answer'><br><br>";
            echo "<input type='submit' name='submit' value='Beantwoord'>";
            echo "</form>";
            if(isset($_GET['message']) && $_GET['message'] == 'incorrect'){
                echo "<br>";
                echo 'stooopid';
                echo "<br>";
            }
            elseif(isset($_GET['message']) && $_GET['message'] == 'correct'){
                echo "<br>";
                echo 'Wow you average';
                echo "<br>";
            }
            echo "<br>";
            echo "<a href='dash.php' class='avgbutton'>Fuc go back</a>";
        ?>
    </div>
    <div>
    <br>
    </div>
</body>
</html>