<?php 

    class FriendRequest{
    //
    private $user_obj;
    private $con;

    public function __construct($con, $username){
        $this->con = $con;
        $this->user_obj = new User($con, $username);
    }

    public function showMyFriendRequest(){

        $userLoggedIn = $this->user_obj->getUsername();

        $result="";


        $query = mysqli_query($this->con, "SELECT * FROM friend_requests 
                WHERE user_to='$userLoggedIn' AND user_from != '$userLoggedIn'");

        
        if(mysqli_num_rows($query)){

                while($row = mysqli_fetch_array($query)){

                    $user_from = $row['user_from'];
        
                    $user_from_obj = new User($this->con, $user_from);
                    $userSentRequest =  $user_from_obj->getFirstNameAndLastName() . " sent you a friend request";

                    $user_from_friend_array = $user_from_obj->getFriendArray();

                    if(isset($_POST['accept_request' . $user_from])){
                        ?>
                            <script>
                                alert('qwe')
                            </script>
                        <?php
                    }else{
                        echo "qwe";
                    }
                }
        }else{
            $result = "
                <h4>No Friend request yet.</h4>
            ";
        }

        $result = "
            <div class='main_column column'>
                <h4>Friend Requests</h4>
                $userSentRequest

                <form action='' method='POST'>
                    <input type='submit' name='accept_request$user_from>'
                        value='Accept' id='accept_button'>
                    <input type='submit' name='ignore_request$user_from>'
                        value='Ignore' id='ignore_button'>
                </form>
            </div>
        ";

        return $result;
    }
}

?>

 


