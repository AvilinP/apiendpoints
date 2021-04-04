<?php


class User {

    private $database_connection;

    private $id;
    private $date;
    private $username;
    private $email;
    private $password;


    function __construct($db) {
        $this->database_connection = $db;
    }

    // Register user 
    function regUser($username_IN, $email_IN, $password_IN) {

        if(!empty($username_IN) && !empty($email_IN) && !empty($password_IN)) {

            // checks for already existing username and email in db
            $sql = "SELECT id FROM users WHERE username = :username_IN or email = :email_IN";
            $statement = $this->database_connection->prepare($sql);
            $statement->bindParam(":username_IN", $username_IN);
            $statement->bindParam(":email_IN", $email_IN);

            if(!$statement->execute() ) {

                $error = new stdClass();
                $error->message = "Could not execute your request!";
                $error->code = "1001";
                print_r(json_encode($error));
                die();

            }

                // counts rows in db and checks if it's more than zero            
                $count_rows = $statement->rowCount();
                if($count_rows > 0) { 

                    $error = new stdClass();
                    $error->message = "The user is already registered.";
                    $error->code = "1002";
                    print_r(json_encode($error)); 
                    die();
        
                }

                    // Adds new user to db with encrypted password
                    $sql = "INSERT INTO users (username, email, password) VALUES(:username_IN, :email_IN, :password_IN)";
                    $statement = $this->database_connection->prepare($sql);
                    $statement->bindParam(":username_IN", $username_IN);
                    $statement->bindParam(":email_IN", $email_IN);
                    $salt = "95uygajk/&&%%1415043343agaeehlrieieiengvn##";
                    $password_IN = md5($password_IN.$salt);
                    $statement->bindParam(":password_IN", $password_IN);

                        if(!$statement->execute() ) {

                            $error = new stdClass(); 
                            $error->message = "Could not create user, something must be wrong!";  
                            $error->code = "1003"; 
                            print_r(json_encode($error));
                            die();
                        }


        } else {
            $error = new stdClass(); 
            $error->message = "You need to fill out all values, such as username, email and password."; 
            $error->code = "1004"; 
            print_r(json_encode($error));
            die();

        }

        $message = new stdClass();
        $message->message = "You successfully registered as an user of this API!";
        print_r(json_encode($message));


    }


    // Login user to API
    function loginUser($username_IN, $password_IN) {

        if(!empty($username_IN) || !empty($password_IN) ) {

            $sql = "SELECT id, username, email, password FROM users WHERE username = :username_IN AND password = :password_IN"; 
            $statement = $this->database_connection->prepare($sql);
            $statement->bindParam(":username_IN", $username_IN);
            $statement->bindParam(":password_IN", $password_IN);

            $statement->execute();

            // if the user types the right username and password and the data exists in one row in the db
            if($statement->rowCount() == 1) {

                $message = new stdClass();
                $message->message = "You successfully loggedin!";
                print_r(json_encode($message));
                echo "<br><br>";
        
                $row = $statement->fetch();
                return $this->createToken($row['id'], $row['username']); // creates a token
             
                
            } else {
                
                $error = new stdClass(); 
                $error->message = "Username or password is either empty or wrong.";  
                $error->code = "1005"; 
                print_r(json_encode($error));
                die();
            }

        } 

    }


    // Create and checks token 
    function createToken($id, $username) {

        // Checks if token (in checkToken) is false, if not it continutes below
        $checked_token =  $this->checkToken($id);                        // fetches an already existing token, instead of creating a new every time

        // returns an active token, that's not false
        if($checked_token != false) {
            return $checked_token;
        }


        // creates a new token if the code above does not find an active token 
        $token = md5(time() . $id . $username);                          // md5 creates an unique token with time stamp, id and username 

        
        $sql  ="INSERT INTO sessions (user_id, token, last_used) VALUES(:user_id_IN, :token_IN, :last_used_IN)";
        $statement = $this->database_connection->prepare($sql);
        $statement->bindParam(":user_id_IN", $id);
        $statement->bindParam(":token_IN", $token);
        $time = time();
        $statement->bindParam(":last_used_IN", $time);

        $statement->execute();

        return $token;

    }

    function checkToken($id) {

        $sql = "SELECT token, last_used FROM sessions WHERE user_id=:user_id_IN AND last_used > :active_time_IN LIMIT 1";
        $statement = $this->database_connection->prepare($sql);
        $statement->bindParam(":user_id_IN", $id);
        $active_time = time() - (60*60);                                 // token is active for one hour (60s x 60s)
        $statement->bindParam(":active_time_IN", $active_time);

        $statement->execute();
        $return = $statement->fetch();

        if(isset($return['token'])) {
            
            return $return['token'];

        } else {
            
            return false;

        }

    }

}


?>