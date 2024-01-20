
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>Document</title>
</head>
<body>

    <style type="text/css">
        * {
            font-family: Arial, Helvetica, Sans-serif;
        }
        body {
            background-color: #fff;
        }
        form {
            position: absolute;
            top: 0;
        }

	</style>

     <?php 
        require 'config/config.php';
        require 'includes/classes/User.php';
        require 'includes/classes/Post.php';
        require 'includes/classes/Notification.php';

        if(isset($_SESSION['username'])){
            $userLoggedIn = $_SESSION['username'];
            $user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$userLoggedIn'");
            
            $user = mysqli_fetch_array($user_details_query);
        }else{
            header('Location: register.php');
        }

        if(isset($_GET['post_id'])){
            $post_id = $_GET['post_id'];

            // echo  $_GET['post_id'];
        } 

        // getting likes and added_by from POSTS
        $get_likes = mysqli_query($con, "SELECT likes, added_by FROM posts 
            WHERE id='$post_id'");
        $like_row = mysqli_fetch_array($get_likes);

        $total_posts_likes = $like_row['likes'];
        $post_user = $like_row['added_by'];
        
        // getting users username via posts -> added_by
        $user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$post_user'");
        $user_row = mysqli_fetch_array($user_details_query);

        $total_user_likes = $user_row['num_likes'];

        // Like Button
        if(isset($_POST['like_button'])){
            
            $total_posts_likes++;
            $total_user_likes++;

            $update_posts_likes = mysqli_query($con, "UPDATE posts SET likes='$total_posts_likes' WHERE id='$post_id'");
            $update_user_num_likes = mysqli_query($con, "UPDATE users SET num_likes='$total_user_likes' WHERE username='$post_user'");

            $inser_user_likes = mysqli_query($con,"INSERT INTO likes VALUES('',
                    '$userLoggedIn', '$post_id')");

            //Insert Notification
            if($post_user != $userLoggedIn) {
                $notification = new Notification($con, $userLoggedIn);
                $notification->insertNotification($post_id, $post_user, "like");
            }
        }

        // UnLike Button
        if(isset($_POST['unlike_button'])){

            if($total_posts_likes > 0){
                $total_posts_likes--;
            }

            $query = mysqli_query($con, "UPDATE posts SET likes='$total_posts_likes' WHERE id='$post_id'");
            $user_likes = mysqli_query($con, "UPDATE users SET num_likes='$total_user_likes' WHERE username='$post_user'");

            $inser_user_likes = mysqli_query($con,"DELETE FROM likes 
                WHERE username='$userLoggedIn' AND post_id='$post_id'");
        }
        // It will show if you unlike or link the post depending on the check_num_rows.
        $check_query = mysqli_query($con, "SELECT * FROM likes WHERE username='$userLoggedIn' AND post_id='$post_id'");
        $check_num_rows = mysqli_num_rows($check_query);

        if($check_num_rows > 0){
            echo '<form action="like.php?post_id='.$post_id.'" method="POST">
                    <input type="submit" class="comment_like" name="unlike_button" value="Unlike">
                    <div class="like_value">
                        '.$total_posts_likes.'
                    </div>
                </form>';
        }else{
            echo '<form action="like.php?post_id='.$post_id.'" method="POST">
                    <input type="submit" class="comment_like" name="like_button" value="Like">
                    <div class="like_value">
                        '.$total_posts_likes.'
                    </div>
                </form>';
        }
    ?>
</body>
</html>
