<?php 
	include("includes/header.php");
?>

    <div class="main_column column">
        <h4>Friend Requests</h4>

        <?php 
        
            $query = mysqli_query($con, "SELECT * FROM friend_requests 
                WHERE user_to='$userLoggedIn' AND user_from != '$userLoggedIn'");
            
            if(mysqli_num_rows($query)){

                while($row = mysqli_fetch_array($query)){

                    $user_from = $row['user_from'];
        
                    $user_from_obj = new User($con, $user_from);
                    echo $user_from_obj->getFirstNameAndLastName() . " sent you a friend request";

                    $user_from_friend_array = $user_from_obj->getFriendArray();

                    if(isset($_POST['accept_request' . $user_from])){

                        $add_friend_query = mysqli_query($con, 
                            "UPDATE users SET friend_array=CONCAT(friend_array,'$user_from,') 
                                WHERE username='$userLoggedIn'");

                        $add_friend_query = mysqli_query($con,
                            "UPDATE users SET friend_array=CONCAT(friend_array,'$userLoggedIn,')
                                WHERE username='$user_from'");

                        $clean_friend_Request = mysqli_query($con, "DELETE FROM friend_requests
                            WHERE user_to='$userLoggedIn' AND user_from='$user_from'");

                        echo "You are now friends with $user_from!";
				        header("Location: requests.php");

                    }
                    if(isset($_POST['ignore_request' . $user_from])){
                        $delete_friend_request = mysqli_query($con, "DELETE friend_request WHERE user_to='$userLoggedIn' AND user_from='$user_from'");
                        echo "Request ignored!";
				        header("Location: requests.php");
                    }
                    ?>
                        <form action="requests.php" method="post">
                            <input type="submit" name="accept_request<?php echo $user_from ?>"
                                value="Accept" id="accept_button">
                            <input type="submit" name="<?php echo "ignore_request". $user_from ?>"
                                value="Ignore" id="ignore_button">
                        </form>
                    <?php 
                }
            }

        ?>

        <!-- <form action="request.php" method="post">
            <input type="submit" name="<?php echo "accept_request". $user_from ?>" value="Accept" id="accept_button">
            <input type="submit" name="<?php echo "ignore_request". $user_from ?>" value="Ignore" id="ignore_button">
        </form> -->

    </div>

    <!-- Header Wrapper End Section -->
</div>
