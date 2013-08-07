<?php

require "functions.php";

$action = $_GET['a'];
	
if ($action == "comment") { 

// gather variables from $_POST
$message = $_POST['array']['0'];
$postId = $_POST['array']['1'];
$commentCount = $_POST['array']['2'];


try {
      $statusUpdate = $facebook->api($postId.'/comments', 'post', array('message'=>$message, 'cb' => ''));
} catch (FacebookApiException $e) {
      
}

	// Setup offset variable
	$showCommentCount = 5;
	// logic
	$commentCount += 1;
	if($commentCount > 5) {
		$offset = $commentCount - $showCommentCount;
	}
	
	$comment = $facebook->api($postId.'/comments?offset='.$offset.'&limit='.$showCommentCount.'');
	
	$comment = $comment['data'];
		
		
 if ($commentCount > $showCommentCount) { ?>
	 <div class="facebookExtraComment">
     	<img src="http://static.ak.fbcdn.net/rsrc.php/v1/yg/r/V8Yrm0eKZpi.gif" width="16px" height="16px"/>
        	<a href="javascript:void(0);" onclick="viewAllComment('<?php echo $postId; ?>');">
            	View all <?php echo $commentCount; ?> comments
            </a>
     </div>
    <?php } 
	
	//Comment functions.
	commentLogic($comment); 


} elseif ($action == "viewAllComment") { 

	//pulles comment id
	$postId = $_POST['array']['0'];
	
	//grabs from facebook the nessary array
	$comment = $facebook->api('/'.$postId.'?access_token='.$session['access_token'].'');

	//main variables	
	$comment = $comment['comments']['data'];
	
	//Comment Logic
	commentLogic($comment); 

} elseif ($action == "like") {

	//grabs variables from javascript post 
	$postId = $_POST['array']['0'];
	
	try {
		  $statusUpdate = $facebook->api($postId.'/likes', 'post', array('cb' => ''));
	} catch (FacebookApiException $e) {
		  d($e);
	}

} else {

	echo "Unknown error has occurred";	
	
}

?>