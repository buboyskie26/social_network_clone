<?php 

	include("includes/header.php");
	// require 'includes/classes/User.php';
 	// require 'includes/classes/Post.php';

	$message_obj = new Message($con, $userLoggedIn);

	if(isset($_GET['profile_username'])){
		// .htaccess located
		$username = $_GET['profile_username'];
		$user_details_query = mysqli_query($con, "SELECT * FROM users 
			WHERE username='$username'");
			
		$user_array = mysqli_fetch_array($user_details_query);

		$num_friends = substr_count($user_array['friend_array'], ",") - 1;

		$user_to_obj = new User($con, $username);

	}

	if(isset($_POST['remove_friend'])){
		
		$user_obj = new User($con, $userLoggedIn);
		$user_obj->removeFriend($username);
	}

	if(isset($_POST['respond_request'])){
		header("Location: requests.php");
	}

	if(isset($_POST['add_friend'])){
		$user = new User($con, $userLoggedIn);
		$user->sendRequest($username);
	}

	if(isset($_POST['post_message2'])){
        if(isset($_POST['message_body'])){
            $body = mysqli_real_escape_string($con, $_POST['message_body']);
            $date = date("Y-m-d H:i:s");
            $message_obj->sendMessage($username, $body, $date);
            // header("Location: messages.php");
        }

		$link = '#profileTabs a[href="#messages_div"]';
		
		echo "<script>
				$(function() {
					$('".$link."').tab('show');
				});
			</script>";
    }

 ?>
	<style type="text/css">
	 	.wrapper {
	 		margin-left: 0px;
			padding-left: 0px;
	 	}
	</style>

		<div class="profile_left">
			<img src="<?php echo $user_array['profile_pic'] ?>" width="50" alt="">

			<div class="profile_info">
				<p><?php echo "Post: " . $user_array['num_posts'] ?></p>
				<p><?php echo "Likes: " . $user_array['num_likes'] ?></p>
				<p><?php echo "Friends: " . "$num_friends" ?></p>
			</div>

			 <!-- Current Page userane URL -->
			<form action="<?php echo $username ?>" method="POST">
				<?php
					$profile_user_obj = new User($con, $username);

					if($profile_user_obj->isClosed()) {
						header("Location: user_closed.php");
					}

					$logged_in_user_obj = new User($con, $userLoggedIn); 

					if($userLoggedIn != $username) {

						if($logged_in_user_obj->isFriend($username)) {
							echo '<input type="submit" name="remove_friend" class="danger" value="Remove Friend"><br>';
						}
						else if ($logged_in_user_obj->didReceiveRequest($username)) {
							echo '<input type="submit" name="respond_request" class="warning" value="Respond to Request"><br>';
						}
						else if ($logged_in_user_obj->didSendRequest($username)) {
							echo '<input type="submit" name="" class="default" value="Request Sent"><br>';
						}
						else 
							echo '<input type="submit" name="add_friend" class="success" value="Add Friend"><br>';
					}
				?>
	  	  	</form>

			<input type="submit" class="deep_blue" value="Post Something"
				 data-toggle="modal" data-target="#post_form">
			
			<?php 
				$num_mutual_friends = $logged_in_user_obj->getMutualFriends($username);
				$str = "";
				
				if($userLoggedIn != $username){
					// echo '<div class="profile_info_bottom">';
					// echo $logged_in_user_obj->getMutualFriends($username) . ' Mutual Friends';
					// echo '</div>';
					$str = "
						<div class='profile_info_bottom'>
							$num_mutual_friends Mutual Friends
						</div>
					";
					echo $str;
				}	
			?>
		</div>

		<div class="main_column column">
			<ul class="nav nav-tabs" id="profileTabs">
				<li class="active">
					<a href="#newsfeed_div" role="tab" data-toggle="tab">
						Newsfeed
					</a>
				</li>
				<li>
					<a href="#messages_div" role="tab" data-toggle="tab">
						Messages
					</a>
				</li>
			</ul>
			 
			<div class="tab-content">
				<div class="tab-pane fade in active" id="newsfeed_div">
					<div class="posts_area">
						<?php 
							$posts = new Post($con, $userLoggedIn);
							$posts->loadProfilePosts($username);
						?>
					</div>
				</div>
				<div class="tab-pane fade" id="messages_div">
					<div class="posts_area">
						<?php 

							$getMessages = $message_obj->getMessages($username);

							echo "<h4> You and
								<a href='$username'>"
									.$user_to_obj->getFirstNameAndLastName().
								"</a>
								</h4><br>";

							echo "
								<div class='loaded_messages' id='scroll_messages'>
									$getMessages
								</div>
							";
						?>
						
						<div class="message_post">
							<form action="" method="POST">
								<?php 
									echo "<textarea name='message_body' id='message_textarea' placeholder='Write a message...'></textarea>";
									echo "<input type='submit' name='post_message' id='message_submit' value='Send' />";
								?>
							</form>
						</div>

						<script>
							var div = document.getElementById('scroll_messages');
							div.scrollTop = div.scrollHeight;
						</script>
					</div>
				</div>
			</div>
		</div>
				
		<!-- Modal -->
		<div class="modal fade" id="post_form" tabindex="-1" role="dialog" aria-labelledby="postModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
						<h4 class="modal-title" id="postModalLabel">Post something</h4>
					</div>
					<div class="modal-body">
						<p>This will appear on the newsfeed for your friends to see. </p>

						<!-- class 'profile_post' is important for AJAX CALL -->
						<form class="profile_post" action="profile.php" method="POST">
							<div class="form-group">
								<textarea class="form-control" name="post_body"></textarea>
								<input type="hidden" name="user_from" value="<?php echo $userLoggedIn; ?>">
								<input type="hidden" name="user_to" value="<?php echo $username; ?>">
							</div>
						</form>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" name="post_button" id="submit_profile_post">Post</button>
					</div>
				</div>
			</div>
		</div>

	<!-- Header Wrapper Closing Div -->
	</div>
</body>
</html>
 