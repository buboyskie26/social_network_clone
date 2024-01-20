<?php 

    require '../../config/config.php';
    require '../classes/User.php';


    $query = $_POST['query'];
    $userLoggedIn = $_POST['userLoggedIn'];

    $names = explode(" ", $query);
    
    // REFERENCE: https://www.w3schools.com/sql/sql_like.asp

    if(strpos($query, "_") !== false){
        $search_query_db = mysqli_query($con, "SELECT * FROM users 
            WHERE username LIKE '$query%'
            AND user_closed='no' LIMIT 8");

    // justine sirios
    }else if(count($names) == 2) {
	    $search_query_db = mysqli_query($con, "SELECT * FROM users
            WHERE (first_name LIKE '%$names[0]%' AND last_name LIKE '%$names[1]%')
            AND user_closed='no' LIMIT 8");
    }else {
	    $search_query_db = mysqli_query($con, "SELECT * FROM users
            WHERE (first_name LIKE '%$names[0]%' OR last_name LIKE '%$names[0]%')
            AND user_closed='no' LIMIT 8");
    }

    if($query !== ""){

        while($row = mysqli_fetch_array($search_query_db)){

            $user = new User($con, $userLoggedIn);

            if($row['username'] !=  $userLoggedIn){
                $mutualFriends = $user->getMutualFriends($row['username']) . " friend in common";
            }else{
                $mutualFriends = "";
            }

            if($user->isFriend($row['username'])){
                echo "
                    <div class='resultDisplay'>
                        <a href='messages.php?u=" . $row['username'] . "' style='color: #000'>
                            <div class='liveSearchProfilePic'>
                                <img src='". $row['profile_pic'] . "'>
                            </div>

                            <div class='liveSearchText'>
                                ".$row['first_name'] . " " . $row['last_name']. "
                                <p style='margin: 0;'>". $row['username'] . "</p>
                                <p id='grey'>".$mutualFriends . "</p>
                            </div>
                        </a>
                    </div>";

                }
        }
    }
?>