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


    function regUser($username_IN, $email_IN, $password_IN) {

        if(!empty($username_IN) && !empty($email_IN) && !empty($password_IN)) {

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

                $count_rows = $statement->rowCount();
                if($count_rows > 0) { 

                    $error = new stdClass();
                    $error->message = "The user is already registered.";
                    $error->code = "1002";
                    print_r(json_encode($error)); 
                    die();
        
                }

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



    function loginUser($username_IN, $password_IN) {

        if(!empty($username_IN) || !empty($password_IN) ) {

            $sql = "SELECT id, username, email, password FROM users WHERE username = :username_IN AND password = :password_IN"; 
            $statement = $this->database_connection->prepare($sql);
            $statement->bindParam(":username_IN", $username_IN);
            $statement->bindParam(":password_IN", $password_IN);

            $statement->execute();

            // om användaren skriver rätt username och password och finns på en rad i db
            if($statement->rowCount() == 1) {

                echo  " You successfully loggedin! <br><br>";
        
                $row = $statement->fetch();
                return $this->createToken($row['id'], $row['username']); // skapar en ny token
             
                
            } else {
                
                $error = new stdClass(); 
                $error->message = "Username or password is either empty or wrong.";  
                $error->code = "1005"; 
                print_r(json_encode($error));
                die();
            }

        } 

    }


    // skapa och checka token/session // ADDERA _IN
    function createToken($id, $username) {

        // kollar om token (i checkToken) är false, om inte kör den vidare. 
        $checked_token =  $this->checkToken($id);                        // hämtar en redan aktiv token, istället för att skapa ny hela tiden

        // returnerar en aktiv token, som inte är "false"
        if($checked_token != false) {
            return $checked_token;
        }


        // skapar en ny token ifall koden ovan inte hittade en aktiv token

        $token = md5(time() . $id . $username);                          // md5 ger en unik token med tid, id och username

        
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
        $active_time = time() - (60*60);                                 // sätter den aktiva tiden på en timme (60s x 60s)
        $statement->bindParam(":active_time_IN", $active_time);

        $statement->execute();
        $return = $statement->fetch();

        if(isset($return['token'])) {
            
            return $return['token'];

        } else {
            
            return false;

        }

    }


    // finns även i Product.php, ev ta bort denna, ej "aktiverad" funktion 
    function UpdateToken($token) {

        $sql = "UPDATE sessions SET last_used=:last_used_IN WHERE token=:token_IN";
        $statement = $this->database_connection->prepare($sql);
        $time = time();
        $statement->bindParam(":last_used_IN", $time);
        $statement->bindParam(":token_IN", $token);
        $statement->execute();

    }


 















// sista klammern i class User
}
























?>