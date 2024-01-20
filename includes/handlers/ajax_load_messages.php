<?php

    require '../../config/config.php';
    require '../classes/User.php';
    require '../classes/Message.php';

    $limit = 6; //Number of messages to load

    $message = new Message($con, $_REQUEST['userLoggedIn']);

    // response in the ajax call
    echo $message->getConvosDropdown($_REQUEST, $limit);

?>