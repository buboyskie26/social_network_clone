<?php 

    require('includes/header.php');

    $message_obj = new Message($con, $userLoggedIn);
    
    if(isset($_GET['u'])){
        $user_to = $_GET['u'];

    }else{

        $user_to = $message_obj->getMostRecentUser();
        // If you logged in user doesnt have any chat
        if($user_to == false){
            $user_to = "new";
        }
    }
    // 
    if($user_to != "new"){
        $user_to_obj = new User($con, $user_to);
    }
    
    if(isset($_POST['post_message'])){
        if(isset($_POST['message_body'])){

            $body = mysqli_real_escape_string($con, $_POST['message_body']);
            $date = date("Y-m-d H:i:s");
            $message_obj->sendMessage($user_to, $body, $date);
            // header("Location: messages.php");
        }
    }

?>
    
    <div class="user_details column">
        <a href="<?php echo $userLoggedIn; ?>">
            <img src="<?php echo $user['profile_pic'] ?>" alt="">	
        </a>

        <div class="user_details_left_right">
            <a href="<?php echo $userLoggedIn ?>" >
                <?php 
                    
                echo $user['first_name'] . ' '.$user['last_name']; 
                ?>
            </a>

            <br>
            <?php echo "Posts:" . $user['num_posts'] ."<br>";
                    echo "Likes:" . $user['num_likes'] ."<br>";
                ?>
        </div>
    </div>

    <div class="main_column column" id="main_column">
        <?php 
            if($user_to != "new"){

                $getMessages = $message_obj->getMessages($user_to);

                echo "<h4> You and
                    <a href='$user_to'>"
                        .$user_to_obj->getFirstNameAndLastName().
                    "</a>
                    </h4><br>";

                echo "
                    <div class='loaded_messages' id='scroll_messages'>
                        $getMessages
                    </div>
                ";
            }else{
                echo "<h4>New Message</h4>";
            }
        ?>
        
        <div class="message_post">
            <form action="" method="POST">
                <?php 
                    if($user_to == "new"){
                        echo "Select and friend you would like to message <br><br>";
                        ?>
                            To: <input type='text' onkeyup='getUsers(this.value, "<?php echo $userLoggedIn; ?>")' 
                            name='q' placeholder='Name' autocomplete='off' id='seach_text_input'>
                        <?php

                        echo "<div class='results'></div>";
                    }else{
                        echo "<textarea name='message_body' id='message_textarea' placeholder='Write a message...'></textarea>";
                        echo "<input type='submit' name='post_message' id='message_submit' value='Send' />";
                    }
                ?>
            </form>
        </div>
        <script>
            var div = document.getElementById('scroll_messages');
            div.scrollTop = div.scrollHeight;
        </script>
    </div>
                    
    <div class="user_details column" id="conversations">
        <h3 class="text-center">Conversation</h3>
        
        <div class="loaded_conversations">
            <?php echo $message_obj->getConvos() ?>
        </div>
        <br>
        <a href="messages.php?u=new" class="btn btn-primary btn-sm">New Message</a>

    </div>

    

