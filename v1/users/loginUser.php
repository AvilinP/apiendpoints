<?php
    include("../../includes/db.php");
    include("../../objects/User.php");


    $username = $_GET['username'];
    $salt = "95uygajk/&&%%1415043343agaeehlrieieiengvn##";
    $password = md5($_GET['password'].$salt);

    $user = new User($pdo);

    // return endast om fälten är ifyllda korrekt 
    $return = new stdClass();
    $return->token = $user->loginUser($username, $password);
    print_r(json_encode($return));

    

?> 