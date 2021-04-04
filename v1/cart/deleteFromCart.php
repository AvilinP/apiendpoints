<?php
    include("../../includes/db.php");
    include("../../objects/Cart.php");

    $token = "";

    if(isset($_GET['token'])) {

        $token = $_GET['token'];

    } else {

        $error = new stdClass();
        $error->message = "There is no token written!";
        $error->code = "2007";
        print_r(json_encode($error));
        die();
    }

    $cart = new Cart($pdo); 

    // Checks if there's a valid token before the product is deleted from the cart
    if($cart->isTokenValid($token)) {

        if(!isset($_GET['id'])) {

            $error = new stdClass();
            $error->message = "Please specify an id";
            $error->code = "2008";
            print_r(json_encode($error));
            die();
        
        } else {
                     
            $cart = new Cart($pdo);
            $cart->deleteFromCart($_GET['id'], $token);
            $message = new stdClass();
            $message->message = "Successfully deleted product in cart!";
            print_r(json_encode($message));
            die();
          
        }

    } else {

        $error = new stdClass();
        $error->message = "No valid token";
        $error->code = "2009";
        print_r(json_encode($error));
        die();

    }


?>