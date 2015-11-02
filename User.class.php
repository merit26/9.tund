<?php

class User{
	
	private $connection;
	
    //User.class.php
	//see fn käivitub kui tekitame uue instantsi nt. new User()
	function __construct($mysqli){
		
		$this->connection = $mysqli;
	 function logInUser($email, $hash){
        
       
        $stmt = $this->connection->prepare("SELECT id, email FROM user_sample WHERE email=? AND password=?");
        $stmt->bind_param("ss", $email, $hash);
        $stmt->bind_result($id_from_db, $email_from_db);
        $stmt->execute();
        if($stmt->fetch()){
            echo "Kasutaja logis sisse id=".$id_from_db;
            
            // sessioon, salvestatakse serveris
            $_SESSION['logged_in_user_id'] = $id_from_db;
            $_SESSION['logged_in_user_email'] = $email_from_db;
            
            //suuname kasutaja teisele lehel
            header("Location: data.php");
            
        }else{
            echo "Wrong credentials!";
        }
        $stmt->close();
                      
    }
    
    
    function createUser($create_email, $hash){
        // objekt kus tagastame errori (id, message) või successi (message)
		$response = new StdClass();
		$stmt = $this->connection->prepare("SELECT id FROM user_sample WHERE email = ?");
		$stmt->bind_param("s", $create_email);
		$stmt->bind_result($id);
		$stmt->execute();
		
		if($stmt->fetch()){
			// email on juba olemas
			$error->id=0;
			$error->message = "Email on juba kasutusel";
			
			$response->error = $error;
			// kui on juba kasutuses, siis edasi ei lähe
			return $response;
		}	
		// siia jõuame kui seda emaili ei leitud

				
			
	
		
        $stmt = $this->connection->prepare("INSERT INTO user_sample (email, password) VALUES (?,?)");
        $stmt->bind_param("ss", $create_email, $hash);
        if($stmt->execute()){
		//siia jõudes on selge, et sisestamine õnnestus	
			$success = new StdClass();
			$success->message = "kasutaja edukalt loodud";
			$response->success=$success;
			
		}else{
			// ei õnnestunud
			$error = new StdClass();
			$error->id=1;
			$error->message = "Email on juba kasutusel";
			
			$response->error=$error;
			/// siin on miski veel
		};
		
		
        $stmt->close();
        
              
    }	
		
	}

}?>