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


    // samma som i User.php men kollar token istället för id
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
    
        // Finns även i User.php, men endast aktiverad funktion ovan
        function UpdateToken($token) {
    
            $sql = "UPDATE sessions SET last_used=:last_used_IN WHERE token=:token_IN";
            $statement = $this->database_connection->prepare($sql);
            $time = time();
            $statement->bindParam(":last_used_IN", $time);
            $statement->bindParam(":token_IN", $token);
            $statement->execute();
            
        }


    function addToCart($prod_id_IN, $token_IN) {

        

    }




}

    ?>