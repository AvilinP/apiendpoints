<?php
    include("../../includes/db.php");
    include("../../objects/Cart.php");

    $token = "";

    if(isset($_GET['token'])) {

        $token = $_GET['token'];

    } else {

        $error = new stdClass();
        $error->message = "There is no token written!";
        $error->code = "2002";
        print_r(json_encode($error));
        die();
    }

    $cart = new Cart($pdo); 

    // Checks if there's a valid token before the product is added to the cart
    if($cart->isTokenValid($token)) {

        if(!isset($_GET['product_id'])) {

            $error = new stdClass();
            $error->message = "Please specify a product id";
            $error->code = "2003";
            print_r(json_encode($error));
            die();
        
        } else {
                     
            $cart->addToCart($_GET['product_id'], $_GET['token']); 
            $message = new stdClass();
            $message->message = "Product added to cart";
            print_r(json_encode($message));
            die();
          
        }

    } else {

        $error = new stdClass();
        $error->message = "No valid token";
        $error->code = "2004";
        print_r(json_encode($error));
        die();

    }

?>