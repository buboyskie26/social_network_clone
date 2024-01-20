<?php 
	include("../../config/config.php");
    require '../classes/FriendRequest.php';
    require '../classes/User.php';
     

    $friend = new FriendRequest($con, $_REQUEST['userLoggedIn']);

    echo $friend->showMyFriendRequest();

?>


 