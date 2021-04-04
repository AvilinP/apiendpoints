<?php

class Cart {

    private $database_connection; 

    private $id;
    private $user_id;
    private $product_id;
    private $date;


    function __construct($db) {
        $this->database_connection = $db;
    }


    // Add product to cart if you have a valid token
    function addToCart($prod_id_IN, $token) {

        // Checks if the product id exists in db products
        if(!empty($prod_id_IN)) {

            $sql = "SELECT id FROM products WHERE id = :prod_id_IN";
            $statement = $this->database_connection->prepare($sql);
            $statement->bindParam(":prod_id_IN", $prod_id_IN);
            $statement->execute();

            if($statement->rowCount() == 0) {
                $error = new stdClass();
                $error->message = "The product does not exist in database!";
                $error->code = "2001";
                print_r(json_encode($error));
                die();
            }
        
        }

        $sql = "INSERT INTO cart(product_id, token) VALUES (:prod_id_IN, :token_IN)";
        $statement = $this->database_connection->prepare($sql);
        $statement->bindParam(":prod_id_IN", $prod_id_IN);
        $statement->bindParam(":token_IN", $token);

        $statement->execute();

    }


    // Deletes product from cart if you have a valid token
    function deleteFromCart($prod_id_IN, $token) {

        if(empty($_GET['id'])) {

            $error = new stdClass();
            $error->message = "Id is not specified";
            $error->code = "2005";
            print_r(json_encode($error));
            die();
        }


        if(!empty($_GET['id'])) {

            $sql = "SELECT id FROM cart WHERE id = :prod_id_IN";
            $statement = $this->database_connection->prepare($sql);
            $statement->bindParam(":prod_id_IN", $prod_id_IN);

            $statement->execute();

            if($statement->rowCount() == 0) {

                $error = new stdClass();
                $error->message = "The id does not exist in the database!";
                $error->code = "2006";
                print_r(json_encode($error));
                die();

            }

            $sql ="DELETE FROM cart WHERE id=:prod_id_IN";
            $statement = $this->database_connection->prepare($sql);
            $statement->bindParam(":prod_id_IN", $prod_id_IN);
            $statement->execute();

        
        } 

    }

    // Checks if the token is valid
    function isTokenValid($token) {

        $sql = "SELECT token, last_used FROM sessions WHERE token=:token_IN AND last_used > :active_time_IN LIMIT 1";
        $statement = $this->database_connection->prepare($sql);
        $statement->bindParam(":token_IN", $token);
        $active_time = time() - (60*60);                                 // token is active for one hour (60s x 60s)
        $statement->bindParam(":active_time_IN", $active_time);

        $statement->execute();
        $return = $statement->fetch();

        if(isset($return['token'])) {

            // Updates the valid time for token. Updates user automagically during their active time on website, adds another 60 minutes to the time. 
            $this->UpdateToken($return['token']);
            
            return true;

        } else {
            
            return false;

        }

    }



    // Connected to isTokenValid - updates the time for token if user is active for longer
    function UpdateToken($token) {

        $sql = "UPDATE sessions SET last_used=:last_used_IN WHERE token=:token_IN";
        $statement = $this->database_connection->prepare($sql);
        $time = time();
        $statement->bindParam(":last_used_IN", $time);
        $statement->bindParam(":token_IN", $token);
        $statement->execute();
        
    }



}

?>