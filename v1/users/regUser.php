<?php
    include("../../includes/db.php");
    include("../../objects/User.php");

    $user = new User($pdo);
    $user->regUser("Lisa", "lisa@hej.se", "lisa");


?> 