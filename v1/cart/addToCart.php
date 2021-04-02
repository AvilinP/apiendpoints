<?php
    include("../../includes/db.php");
    include("../../objects/Cart.php");

    $token = "";
    if(isset($_GET['token'])) {

        $token = $_GET['token'];

    } else {

        echo "FAIL - no token written!";
        die();
    }

    $cart = new Cart($pdo); 

    // kollar om det finns en valid token innan produkten läggs i cart
    if($cart->isTokenValid($token)) {

        if(!isset($_GET['product_id'])) {

            echo "Please specify a product id";
        
        } else {
                     
            $cart->addToCart($_GET['product_id'], $_GET['token']); 
            echo "Product added to cart";
          
        }

    } else {

        echo "No valid token";

    }



    ?>