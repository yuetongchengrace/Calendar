<?php
	header("Content-Type: application/json"); 
	$json_str = file_get_contents('php://input');
	//This will store the data into an associative array
	$json_obj = json_decode($json_str, true);

	//Variables can be accessed as such:
	$username = htmlentities($json_obj['username']);
	$password = $json_obj['password'];
	//This is equivalent to what you previously did with $_POST['username'] and $_POST['password']

	// Check to see if the username and password are valid.  (You learned how to do this in Module 3.)
	require 'database.php';
	$stmt = $mysqli->prepare("select * from users where username=?");
	if(!$stmt){
		echo json_encode(array(
			"success" => false,
			"message" => "Incorrect Username or Password"
		));
		exit;
	}
	$stmt->bind_param('s', $username);
	$stmt->execute();
	$result= $stmt->get_result();
	$row = $result->fetch_assoc();
	$hashed_password = $row['password'];
	$stmt->close();
	//verify password
	if(password_verify($password, $hashed_password)){
		ini_set("session.cookie_httponly", 1);
		session_start();
		$_SESSION['username'] = $username;
		$_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32)); 

		echo json_encode(array(
			"success" => true,
			"token" => $_SESSION['token']
		));
		exit;
	}
	else{
		echo json_encode(array(
			"success" => false,
			"message" => "Incorrect Username or Password"
		));
		exit;
	}
?>