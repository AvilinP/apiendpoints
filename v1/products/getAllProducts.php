<?php
    include("../../includes/db.php");
    include("../../objects/Product.php");


    $token = "";
    if(isset($_GET['token'])) {

        $token = $_GET['token'];

    } else {

        $error = new stdClass();
        $error->message = "You have not specified a token!";
        $error->code = "0010";
        print_r(json_encode($error));
        die();

    }

    $product = new Product($pdo);


    if($product->isTokenValid($token)) {

       $post = $product->getAllProducts();
       print_r($post);
       die();

    } else {
        
        $error = new stdClass();
        $error->message = "Your token is not valid!";
        $error->code = "0011";
        print_r(json_encode($error));
        die();
    }
   

?>