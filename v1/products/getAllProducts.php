<?php
    include("../../includes/db.php");
    include("../../objects/Product.php");

    $token = "";
    if(isset($_GET['token'])) {

        $token = $_GET['token'];

    } else {

        echo "FAIL!";
        die();
    }

    $product = new Product($pdo);


    if($product->isTokenValid($token)) {

       $post = $product->getAllProducts();
       print_r(json_encode($post));
       die();

    } else {
        
        echo "ERROR!";
    }
   


    

    ?>