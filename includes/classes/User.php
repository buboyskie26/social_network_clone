<?php 

class User{

    private $user;
    private $con;

    public function __construct($con, $username){

        $this->con = $con;
        $user_details_query = mysqli_query($con, "SELECT * FROM users 
            WHERE username='$username'");

        if(mysqli_num_rows($user_details_query) > 0){
            $this->user = mysqli_fetch_array($user_details_query);
        }
    }

   public function getNumberOfFriendRequests() {
		$username = isset($this->user['username']) ? $this->user['username'] : "";

		$query = mysqli_query($this->con, "SELECT * FROM friend_requests 
            WHERE user_to='$username'");

		return mysqli_num_rows($query);
	}

    public function getNumPosts(){
        $username = isset($this->user['username']) ? $this->user['username'] : "";

        $query = mysqli_query($this->con, "SELECT num_posts FROM users 
            WHERE username='$username'");

        $row = mysqli_fetch_array($query);

        return $row['num_posts'];
    }
    public function getFirstNameAndLastName(){
        
        $username = isset($this->user['username']) ? $this->user['username'] : "";

        $query = mysqli_query($this->con, "SELECT first_name, last_name,username from users WHERE username='$username'");
        
        if(mysqli_num_rows($query) > 0){
            
            $row = mysqli_fetch_array($query);
            return $row['first_name'] .' '. $row['last_name'];
        }
        return "";

    }
    public function getUsername(){
        
        $username = isset($this->user['username']) ? $this->user['username'] : "";

        $query = mysqli_query($this->con, "SELECT first_name, last_name,username from users WHERE username='$username'");
        if(mysqli_num_rows($query) > 0){
            $row = mysqli_fetch_array($query);
            return $row['username'];

        }else{
            return "";
        }

        // return $row['first_name'] .' '. $row['last_name'];
         
    }

     public function getUsernameFella(){
        
        $username = isset($this->user['username']) ? $this->user['username'] : "";
        $query = mysqli_query($this->con, "SELECT first_name, last_name,username from users WHERE username='$username'");
        if(mysqli_num_rows($query) > 0){
            $row = mysqli_fetch_array($query);

        // return $row['first_name'] .' '. $row['last_name'];
            return $row['username'];
        }else{
            return "";
        }
        
         
    }

    public function getProfilePic(){
        $username = isset($this->user['username']) ? $this->user['username'] : "";

        $query = mysqli_query($this->con, "SELECT profile_pic from users WHERE username='$username'");
        if(mysqli_num_rows($query) > 0){
            $row = mysqli_fetch_array($query);

            return $row['profile_pic'];
        }
    
    }

    public function getFriendArray(){
        $username = isset($this->user['username']) ? $this->user['username'] : "";

        $query = mysqli_query($this->con, "SELECT friend_array from users 
            WHERE username='$username'");
        $row = mysqli_fetch_array($query);

        return $row['friend_array'];
    }

    

    public function isClosed() {
        
		$username = isset($this->user['username']) ? $this->user['username'] : "";

		$query = mysqli_query($this->con, "SELECT user_closed FROM users 
            WHERE username='$username'");
            
        if(mysqli_num_rows($query) > 0){
            $row = mysqli_fetch_array($query);

            if($row['user_closed'] == 'yes')
                return true;
            else 
                return false;
        }
	
	}

    public function isFriend($username_to_check) : bool{
        // print_r("HOHOY" . "<br>");
        // print_r($username_to_check);

        $usernameComma = ",".$username_to_check.",";
		// $userLoggedInUsername = $this->getUsernameFella();
        $userLoggedInUsername = isset($this->user['username']) ? $this->user['username'] : "";

        if((strstr($this->user['friend_array'], $usernameComma) 
            || $username_to_check == $userLoggedInUsername)) {
			return true;
		}
		else {
			return false;
		}

    }

    public function didReceiveRequest($user_from) {
        $user_to = isset($this->user['username']) ? $this->user['username'] : "";

		$check_request_query = mysqli_query($this->con, "SELECT * FROM friend_requests WHERE user_to='$user_to' AND user_from='$user_from'");
		if(mysqli_num_rows($check_request_query) > 0) {
			return true;
		}
		else {
			return false;
		}
	}

    public function didSendRequest($user_to) {
     
        $user_from = isset($this->user['username']) ? $this->user['username'] : "";

        $check_request_query = mysqli_query($this->con, "SELECT * FROM friend_requests WHERE user_to='$user_to' AND user_from='$user_from'");
        if(mysqli_num_rows($check_request_query) > 0) {
            return true;
        }
        else {
            return false;
        }
	}

    public function removeFriend($user_to_remove) {
        $loggedInuserName = isset($this->user['username']) ? $this->user['username'] : "";

		$query = mysqli_query($this->con, "SELECT friend_array FROM users WHERE username='$user_to_remove'");
		$row = mysqli_fetch_array($query);
		$friend_array_username = $row['friend_array'];

        $loggedInUserFriendArray = $this->user['friend_array'];
        
        // [',justineadrian_simplicio,justinesirios15,']
        // [',justineadrian_simplicio,']
        // Logged In user Friend Array removal.
		$new_friend_array = str_replace($user_to_remove . ",", "", $loggedInUserFriendArray);
		$remove_friend = mysqli_query($this->con, "UPDATE users SET friend_array='$new_friend_array' 
            WHERE username='$loggedInuserName'");
        
        // Your Friend Array Removes you from his/her Friend Array.
		$new_friend_array = str_replace($loggedInuserName . ",", "", $friend_array_username);
		$remove_friend = mysqli_query($this->con, "UPDATE users SET friend_array='$new_friend_array' 
            WHERE username='$user_to_remove'");
        
	}

    public function sendRequest($user_to) {
        $user_from = isset($this->user['username']) ? $this->user['username'] : "";

		$query = mysqli_query($this->con, "INSERT INTO friend_requests VALUES('', '$user_to', '$user_from')");
	}

    public function getMutualFriends($user_to_check){
        
		$mutualFriends = 0;
        
		$user_array = isset($this->user['friend_array']) ? $this->user['friend_array'] : "";
		$user_array_explode = explode(",", $user_array);

		$query = mysqli_query($this->con, "SELECT friend_array FROM users 
            WHERE username='$user_to_check'");

        if(mysqli_num_rows($query) > 0){

            $row = mysqli_fetch_array($query);
            $user_to_check_array = $row['friend_array'];
            $user_to_check_array_explode = explode(",", $user_to_check_array);

            foreach($user_array_explode as $i) {

                foreach($user_to_check_array_explode as $j) {

                    if($i == $j && $i !== "") {
                        $mutualFriends++;
                    }
                }
            }
            return $mutualFriends;
        }

        return $mutualFriends;
	}
}
    
?>