<?php  
	// require 'config/config.php';

	// require '../../config/config.php';

if(isset($_POST['login_button'])) {

	// "john(.doe)@exa//mple.com";
	// john.doe@example.com
	// Remove all illegal characters from email
	$email = filter_var($_POST['log_email'], FILTER_SANITIZE_EMAIL); //sanitize email

	$_SESSION['log_email'] = $email; //Store email into session variable 

	$password = md5($_POST['log_password']); //Get password

	$check_database_query = mysqli_query($con, "SELECT * FROM users 
		WHERE email='$email' AND password='$password' LIMIT 1");

	if(mysqli_num_rows($check_database_query) == 1) {
		
		$row = mysqli_fetch_array($check_database_query);
		
		$username = $row['username'];

		$user_closed_query = mysqli_query($con, "SELECT * FROM users WHERE email='$email' AND user_closed='yes'");
		if(mysqli_num_rows($user_closed_query) == 1) {
			$reopen_account = mysqli_query($con, "UPDATE users SET user_closed='no' WHERE email='$email'");
		}
		
		// Stock the session['username'] for the index.php page
		$_SESSION['username'] = $username;
		header("Location: index.php");
		exit();
	}
	else {
		array_push($error_array, "Email or password was incorrect<br>");
	}
}

?>