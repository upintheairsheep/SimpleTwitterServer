<?php
include  "../../../Config/Maintenance.php";
include  "../../../Config/DatabaseConnection.php";
include  "../../../Library/Middleware.php";
include  "../../../Library/UserListFunctions.php";

$GetUserData = checkAuth();

$tweetsDB = array();
$postersID = array();
$tweetsResponse = array();

$RetrieveTweets = $DBReq->prepare("SELECT * FROM `tweets` ORDER BY `tweets`.`Timestamp` ASC LIMIT 20;");
$RetrieveTweets->execute();
$DBResult = $RetrieveTweets->get_result();

while($tweetData = mysqli_fetch_assoc($DBResult)){
	$tweetsDB[] = $tweetData;
	$postersID[] = $tweetData["PosterUserID"];
}

$postersUserData = getUsersByID(implode(" ,", $postersID));

foreach($tweetsDB as $tweet){
	$tweetsResponse[] =  array (
    'coordinates' => NULL,
    'favorited' => false,
    'created_at' => date('D M j H:i:s O Y', $tweet["Timestamp"]),
    'truncated' => false,
    'entities' => 
    array (
     // 'urls' => 
     // array (
    //    0 => 
     //   array (
     //     'expanded_url' => NULL,
      //    'url' => 'http://www.flickr.com/photos/cindyli/4799054041/',
      //    'indices' => 
      //    array (
      //      0 => 75,
      //      1 => 123,
      //    ),
      //  ),
      ),
      'hashtags' => 
      array (
      ),
      'user_mentions' => 
      array (
        //0 => 
       // array (
       //   'name' => 'Stephanie',
      //    'id' => 15473839,
       //   'indices' => 
       //   array (
       //     0 => 27,
       //     1 => 39,
       //   ),
       //   'screen_name' => 'craftybeans',
       // ),
      //),
    ),
    'text' => $tweet["Text"],
    'annotations' => NULL,
    'contributors' => NULL,
    'id' => $tweet["ID"],
    'geo' => NULL,
    'in_reply_to_user_id' => NULL,
    'place' => NULL,
    'in_reply_to_screen_name' => NULL,
    'user' => 
    array (
      'name' => $postersUserData[$tweet["PosterUserID"]]["Username"],
      'profile_sidebar_border_color' => 'AD0066',
      'profile_background_tile' => false,
      'profile_sidebar_fill_color' => 'AD0066',
      'created_at' => 'Wed Jan 1 00:00:01 +0000 1970',
      'profile_image_url' => '',
      'location' => '',
      'profile_link_color' => 'FF8500',
      'follow_request_sent' => false,
      'url' => '',
      'favourites_count' => 0,
      'contributors_enabled' => false,
      'utc_offset' => -28800,
      'id' => $tweet["PosterUserID"],
      'profile_use_background_image' => true,
      'profile_text_color' => '000000',
      'protected' => false,
      'followers_count' => 0,
      'lang' => 'en',
      'notifications' => false,
      'time_zone' => 'Pacific Time (US & Canada)',
      'verified' => $postersUserData["IsVerified"],
      'profile_background_color' => 'cfe8f6',
      'geo_enabled' => true,
      'description' => '',
      'friends_count' => 0,
      'statuses_count' => 0,
      'profile_background_image_url' => '',
      'following' => false,
      'screen_name' => $postersUserData[$tweet["PosterUserID"]]["FullName"],
    ),
    'source' => 'ios app',
    'in_reply_to_status_id' => NULL,
  );
}

die(json_encode($tweetsResponse));