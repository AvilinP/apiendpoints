<?php


class Product {

    private $database_connection; 

    private $id;
    private $date;
    private $product;
    private $title;
    private $description;
    

    function __construct($db) {
        $this->database_connection = $db;
    }


    // Adds product to db
    function addProduct($product_IN, $title_IN, $description_IN) {

        if(!empty($product_IN) && !empty($title_IN) && !empty($description_IN)) {

            // checks for already existing product in db based on title
            $sql = "SELECT id FROM products WHERE title = :title_IN";
            $statement = $this->database_connection->prepare($sql);
            $statement->bindParam(":title_IN", $title_IN); 


            if( !$statement->execute() ) {

                $error = new stdClass();
                $error->message = "Could not execute your request!";
                $error->code = "0001";
                print_r(json_encode($error));
                die();

            }

            // counts rows in db and checks if it's more than zero 
            $num_rows = $statement->rowCount();
            if($num_rows > 0) {

                $error = new stdClass();
                $error->message = "The product is already registered";
                $error->code = "0002";
                print_r(json_encode($error));
                die();

            }

            // Adds product to db
            $sql = "INSERT INTO products (product, title, description) VALUES(:product_IN, :title_IN, :description_IN)";
            $statement = $this->database_connection->prepare($sql);
            $statement->bindParam(":product_IN", $product_IN);
            $statement->bindParam(":title_IN", $title_IN);
            $statement->bindParam(":description_IN", $description_IN);

            if(!$statement->execute() ) {

                $error = new stdClass();
                $error->message = "Could not create product, something must be wrong.";
                $error->code = "0003";
                print_r(json_encode($error));
                die();

            }

            $this->product = $product_IN;
            $this->title = $title_IN;

            $message = new stdClass();
            $message->message = "Product '$this->product' with title '$this->title' is now added to the database.";
            print_r(json_encode($message));
            die();

                } else {
                
                    $error = new stdClass(); 
                    $error->message = "All arguments needs a value!"; // 
                    $error->code = "0004"; 
                    print_r(json_encode($error));
                    die();

                }
 
    }


    // Delete product in db
    function deleteProduct($prod_id_IN) {

        // Checks if id is declared 
        if(empty($_GET['id'])) {

            $error = new stdClass();
            $error->message = "No id specified!";
            $error->code = "0005";
            print_r(json_encode($error));
            die();

        } 


        // Checks if the id exists in db
        if(!empty($_GET['id'])) {

            $sql = "SELECT id FROM products WHERE id = :prod_id_IN";
            $statement = $this->database_connection->prepare($sql);
            $statement->bindParam(":prod_id_IN", $prod_id_IN);

            $statement->execute();

            if($statement->rowCount() == 0) {

                $error = new stdClass();
                $error->message = "The id does not exist in the database!";
                $error->code = "0006";
                print_r(json_encode($error));
                die();

            }

            $sql ="DELETE FROM products WHERE id=:prod_id_IN";
            $statement = $this->database_connection->prepare($sql);
            $statement->bindParam(":prod_id_IN", $prod_id_IN);
            $statement->execute();

            $message = new stdClass();
            $message->message = "Successfully deleted product in database!";
            print_r(json_encode($message));
        

        } 

    }

    // Update product's title and description
    function updateProduct($prod_id_IN, $title_IN = "", $description_IN = "") {

        if(!empty($title_IN)) {
            $this->updateTitle($prod_id_IN, $title_IN);
        }

        if(!empty($description_IN)) {
            $this->updateDescription($prod_id_IN, $description_IN);
        }

    }

    private function updateTitle($prod_id_IN, $title_IN) {

        $sql = "UPDATE products SET title = :title_IN WHERE id = :prod_id_IN";
        $statement = $this->database_connection->prepare($sql);
        $statement->bindParam(":title_IN", $title_IN);
        $statement->bindParam(":prod_id_IN", $prod_id_IN);
        $statement->execute();

    }

    private function updateDescription($prod_id_IN, $description_IN) {

        $sql = "UPDATE products SET description = :description_IN WHERE id = :prod_id_IN";
        $statement = $this->database_connection->prepare($sql);
        $statement->bindParam(":description_IN", $description_IN);
        $statement->bindParam(":prod_id_IN", $prod_id_IN);
        $statement->execute();

    } 



    // Shows all products in db and only works with active and valid token
    function getAllProducts() {

        $sql = "SELECT * FROM products";
        $statement = $this->database_connection->prepare($sql);
        $statement->execute();
 
        echo json_encode($statement->fetchAll());
       

    }


     // Checks if token is valid 
     function isTokenValid($token) {

        $sql = "SELECT token, last_used FROM sessions WHERE token=:token_IN AND last_used > :active_time_IN LIMIT 1";
        $statement = $this->database_connection->prepare($sql);
        $statement->bindParam(":token_IN", $token);
        $active_time = time() - (60*60);                                 //token is active for one hour (60s x 60s)
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