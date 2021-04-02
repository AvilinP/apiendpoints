<?php
    include("../../includes/db.php");
    include("../../objects/Cart.php");
   
    $cart = new Cart($pdo);
    $cart->deleteFromCart($_GET['id']);

    ?>