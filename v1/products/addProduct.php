<?php
    include("../../includes/db.php");
    include("../../objects/Product.php");

    $product = new Product($pdo);
    $product->addProduct( "Sneakers", "Jordan Air Max 1", "Established in 2005 by owners and friends Nobuhiro Mori and Keiji Ishizuka, Wacko Maria is known for their love for music, which made it now to the Authentic LX.");


?> 