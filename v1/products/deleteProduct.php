<?php
    include("../../includes/db.php");
    include("../../objects/Product.php");
   
    $product = new Product($pdo);
    $product->deleteProduct($_GET['id']);

    ?>