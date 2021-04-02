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


    function addToCart($prod_id_IN, $token) {

        //kolla om produkt-id finns i tabellen products (får felmeddelande)
        if(!empty($prod_id_IN)) {

            $sql = "SELECT id FROM products WHERE id = :prod_id_IN";
            $statement = $this->database_connection->prepare($sql);
            $statement->bindParam(":prod_id_IN", $prod_id_IN);
            $statement->execute();

            if($statement->rowCount() == 0) {
                echo "the product does not exist in database";
            }
        
        }

        $sql = "INSERT INTO cart(product_id, token) VALUES (:prod_id_IN, :token_IN)";
        $statement = $this->database_connection->prepare($sql);
        $statement->bindParam(":prod_id_IN", $prod_id_IN);
        $statement->bindParam(":token_IN", $token);

        $statement->execute();

    }



    // Finns även i User.php (kollar id) och Product.php (kollar token)
    function isTokenValid($token) {

        $sql = "SELECT token, last_used FROM sessions WHERE token=:token_IN AND last_used > :active_time_IN LIMIT 1";
        $statement = $this->database_connection->prepare($sql);
        $statement->bindParam(":token_IN", $token);
        $active_time = time() - (60*60);                                 // sätter den aktiva tiden på en timme (60s x 60s)
        $statement->bindParam(":active_time_IN", $active_time);

        $statement->execute();
        $return = $statement->fetch();

        if(isset($return['token'])) {

            // Uppdaterar tiden om token är giltig. Uppdatera user automatiskt under tiden hen är aktiv. Sedan sätts 60 min token. 
            $this->UpdateToken($return['token']);
            
            return true;

        } else {
            
            return false;

        }




    }

    // Finns även i User.php och Product.php
    function UpdateToken($token) {

        $sql = "UPDATE sessions SET last_used=:last_used_IN WHERE token=:token_IN";
        $statement = $this->database_connection->prepare($sql);
        $time = time();
        $statement->bindParam(":last_used_IN", $time);
        $statement->bindParam(":token_IN", $token);
        $statement->execute();
        
    }


    function deleteFromCart($prod_id_IN) {

        if(empty($_GET['id'])) {

            echo "fail!";
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

            $message = new stdClass();
            $message->message = "Successfully deleted product in cart!";
            print_r(json_encode($message));
        

        } 




    }





}

    ?>