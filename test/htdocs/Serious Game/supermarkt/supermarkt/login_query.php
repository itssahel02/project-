<?php

var_dump($_POST);

if(isset($_POST['login'])){
    include "function.php";

    $username - $_POST['username'];
    $password - $_POST['password'];

    echo "username: $username ";
    echo "</br>";
    echo "password: $password ";
    echo "</br>";

}

?>