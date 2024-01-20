<?php
require '../../config/config.php';
require '../classes/User.php';
require '../classes/Message.php';
require '../classes/Notification.php';

$limit = 6; //Number of messages to load

$notif = new Notification($con, $_REQUEST['userLoggedIn']);

// response in the ajax call
echo $notif->getNotification($_REQUEST, $limit);

?>