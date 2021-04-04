<?php
    include("../../includes/db.php");
    include("../../objects/Product.php");


 if (empty($_GET['id'])) {
    $error = new stdClass();
    $error->message = "No id specified!";
    $error->code = "0007";
    print_r(json_encode($error));
    die();
}

if (empty($_GET['title'])) {
    $error = new stdClass();
    $error->message = "No title specified!";
    $error->code = "0008";
    print_r(json_encode($error));
    die();
}

if (empty($_GET['description'])) {
    $error = new stdClass();
    $error->message = "No description specified!";
    $error->code = "0009";
    print_r(json_encode($error));
    die();
}

   
    $post = new Product($pdo);
    $post->updateProduct($_GET['id'], $_GET['title'], $_GET['description']);

    $message = new stdClass();
    $message->message = "Successfully updated product in database!";
    print_r(json_encode($message));

    ?>