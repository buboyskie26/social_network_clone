<?php 

	include("includes/header.php");

    if($_GET['id']){
        $id = $_GET['id'];
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
    <div class="posts_area">
        <?php 
			$post = new Post($con, $userLoggedIn);
			$post->getSinglePost($id);
        ?>
    </div>
</div>


