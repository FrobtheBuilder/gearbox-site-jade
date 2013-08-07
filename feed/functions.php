<?php
require "facebook.php";
$plugin = array();
/*  FourgeFeed is open source and allowed to be used in anyway
    needed but there is only one rule. That if you create an awesome
    application that you send a link of it to fourgefeeds admin or
    danielwilczak@hotmail.com.
*/

/* These are all the standerd functions that fourgefeed uses to 
   display the five diffrent types of feed posts. Along with the 
   comment logic and controls to interact with the post.
*/





// To add a plugin just add to the plugin variable by..

// Example -    $plugin += array('Filename.php' => 'Active/Deactive');


// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
  'appId'  => 'YOUR APP ID',
  'secret' => 'YOUR APP SECRET',
  'cookie' => true,
));






/// FourgeFeed logic if you would like to read and maybe even learn from the system.


foreach($plugin as $key => $value) {
	if ($value == 'active') {
		include("plugins/".$key);
	}
}

$session = $facebook->getSession();
// checks if the general information session is stored for facebook.
if(isset($_SESSION['session'])) {} else { $_SESSION['session'] =  $session;}

if(empty($session)) unset($_SESSION['session']);

// login or logout url will be needed depending on current user state.
if ($me) {
  $logoutUrl = $facebook->getLogoutUrl();
} else {
  $loginUrl = $facebook->getLoginUrl();
}
	

// GLOBAL VARIABLES
$uid = $_SESSION['session']['uid'];
$playlistInfo = array();

   
	function feed ($array) { ?>
    
    <div id="holder"> 
    <img src="images/loading.gif" style="display:none" />
    <?php
	
	$key = $array['data'];
	
	foreach ($key as $value)
	  { 
	
	$postId = $value['id']; 
	$name   = $value['from']['name'];
	$id     = $value['from']['id'];

	$to = $value['to'];
	if (!empty($to))  {
		$to['name']   = $value['to']['data']['0']['name'];
		$to['id'] = $value['to']['data']['0']['id'];
	}
	if ($to == $name) {
		$to = "";
	}

	$commentUrl = $value['actions']['0']['link'];
	
	// like variables
	$like      = $value['likes'];
	$likeCount = $like['count'];
	$likeName  = $like['data']['0']['name'];
	
	// action variables
	$commentLink = $value['actions'][0]['link'];
	$likeLink    = $value['actions'][1]['link'];
	
	// comment variables
	$currentCommentCount = count($value['comments']['data']);
	$comment      = $value['comments']['data'];
	$commentCount = $value['comments']['count'];
	if ($commentCount == "") $commentCount = 0;
	
	// time variables modified to create an array of sorting times.
	$timeCreated = timeAgo(strtotime($value['created_time']),time());
	
	// type of post in the feed.
	$type = $value['type'];
	if ($type == "status") {
		// Standerd Status variables.
		$statusMessage    = $value['message'];
	
	} elseif ($type == "photo") {
		//Picture variables
		$photoMessage = $value['message'];
		$photoPicture = $value['picture']; 
		$photoLink    = $value['link'];
		$photoName    = $value['name'];
		$photoIcon    = $value['icon'];
		$photoCaption = $value['caption'];
		
	} elseif ($type == "video") {
		//Video variables
		$videoMessage     = $value['message'];
		$videoPicture     = $value['picture'];
		$videoLink        = $value['link'];
		$videoSource      = $value['source'];
		$videoName        = $value['name'];
		$videoCaption     = $value['caption'];
		$videoDescription = $value['description'];
		$videoIcon        = $value['icon'];
	
	} elseif ($type == "link") {
		//Link variables
		$linkMessage     = $value['message'];
		$linkPicture     = $value['picture'];
		$linkLink        = $value['link'];
		$linkName        = $value['name'];
		$linkCaption     = $value['caption'];
		$linkDescription = $value['description'];
		$linkIcon        = $value['icon'];
		$linkApplication = $value['application'];
		
		if (!empty($linkApplication))  {
			$linkApplication  =  $linkApplication['name'];
			$linkApplication  =  $linkApplication['id'];
		}
		
	} elseif ($type == "swf") {
		//Swf variables
		$swfMessage     = $value['message'];
		$swfPicture     = $value['picture'];
		$swfLink        = $value['link'];
		$swfSource		= $value['source'];
		$swfName        = $value['name'];
		$swfCaption     = $value['caption'];
		$swfDescription = $value['description'];
		$swfIcon        = $value['icon'];
		$swfApplication = $value['application'];
		
		if (!empty($swfApplication))  {
			$swfApplication  =  $linkApplication['name'];
			$swfApplication  =  $linkApplication['id'];
		}
		
	}
	
	?>
            
<div class="facebookContain">
 <?php 
 	   /* The statusheader function is used to explain who is sending the
	      message and who is receiving it if it is being sent. */
	   statusHeader ($id,$name,$to);
      
       if ($type == "status") {
		 // if the post is a status update then use the status function.  
         facebookStatus($statusMessage);
			
	   } elseif ($type == "photo") {
		 // if the post is a photo then use the photo function.	
		 facebookPhoto($postId,$photoMessage,$photoLink,$photoPicture,$photoName);		
                
	   } elseif ($type == "video") {
		 // if the post is a video then use the video function.  
		 facebookVideo($postId,$videoMessage,$videoSource,$videoPicture,$videoName,$videoCaption,$videoDescription); 
      			 			
	   } elseif ($type == "link") { 
	   	 // if the post is a link then use the link function.
	   	 facebookLink($postId,$linkMessage,$linkLink,$linkPicture,$linkLink,$linkCaption,$linkDescription);	
      
	   } elseif ($type == "swf") {
		 // if the post is a swf then use the swf function.     
		 facebookSwf($postId,$swfMessage,$swfSource,$swfPicture,$swfLink,$swfName,$swfCaption,$swfDescription);                       
       } 
	   
	   // To comment and "like" a post we use the facebookControls function.
	   facebookControls($postId,$timeCreated,$commentCount,$likeCount);
  
      if ($commentCount > 0 || $likeCount > 0 )  { echo '<div class="facebookArrow"></div>';} 
      
	  
	  // if someone has liked the post then we output the "likes".
      likeCount($likeCount,$likeName);
      
	  // controls if the post has more comments then shown.
	  viewAllComments($postId,$commentCount,$currentCommentCount);

	if (!empty($comment)) { 
	// if the post has any comments then we output them with the commentlogic function.
		commentLogic($comment);
	} 
?>
    
</div>
    
    <?php 
  /* the inputComment function is used to allow the user input
	 his own comment and it will refresh once they have input there
	 own comment.*/
	 inputComment($postId,$commentCount,$likeCount); 
	?>
</div>
</div>	

<!--This div is used as a holder to allow jquery to load functions into.-->
<div style="display:none" id="Query"></div>

<?php }  /*end of feed function	*/} 


function print_a ($array) {
	// Diagnostic tool to view arrays.
	echo "<pre>";
	print_r ($array);
	echo "</pre>";
}

function timeAgo($time,$now) {
	// time modifier function to output in a specfic fashion
	$ago1 = $now - $time;
	if ($ago1 > 59) {
		$ago2 = $ago1 / 60;
		if ($ago2 > 59) {
			$ago3 = $ago2 / 60;
			if ($ago3 > 23) {
				$ago4 = $ago3 / 24;
				$ago4 = round($ago4);
				if ($ago4 == 1) {
					$ago = "$ago4 day ago";
				} elseif ($ago4 > 7) {
					$ago = date("F jS, Y",$time);
				} else {
					$ago = "$ago4 days ago";
				}
			} else {
				$ago3 = round($ago3);
				if ($ago3 == 1) {
					$ago = "about an hour ago";
				} else {
					$ago = "$ago3 hours ago";
				}
			}
		} else {
			$ago2 = round($ago2);
			if ($ago2 == 1) {
				$ago = "$ago2 minute ago";
			} else {
				$ago = "$ago2 minutes ago";
			}
		}
	} else {
		if ($ago1 == 1) {
			$ago = "$ago1 second ago";
		} else {
			$ago = "$ago1 seconds ago";
		}
	}
	
	return $ago;
}

function commentLogic($comment) {
	
		//Comment Logic
	if (!empty($comment)) {
	foreach ($comment as $com) {
		$commentId = $com['id'];
		$commentNameId = $com['from']['id'];
		$commentName = $com['from']['name'];
		$commentMessage = $com['message'];
		$commentLikeAmount = $com['likes'];
		$commentTimeCreated = timeAgo(strtotime($com['created_time']),time());
	?>
      <div class="facebookSubCommentContainer">
       <div class="facebookSubCommentPhoto">
        <a href="http://www.facebook.com/profile.php?id=<?php echo $commentNameId; ?>" target="_blank">
         <img src="https://graph.facebook.com/<?php echo $commentNameId; ?>/picture?type=square"/>
        </a>
       </div>
       
       <div class="facebookSubComment">
        <a href="http://www.facebook.com/profile.php?id=<?php echo $commentNameId; ?>" target="_blank"><?php echo $commentName; ?></a> 
		<?php echo $commentMessage;  ?>
       </div>
       
       <div class="facebookSubCommentControl">
       	<a href="#" style="color:#999999;"><?php echo $commentTimeCreated; ?></a> · <a href="javascript:void(0);" id="like<?php echo $commentId; ?>" onclick="like('<?php echo $commentId; ?>');">Like</a> 
		 <?php 	 
           if($commentLikeAmount > 1) { ?>
             · <a href="#"><div class="facebookTinyLike"></div>
             <?php echo $commentLikeAmount ?> People </a>
		<?php } elseif ($commentLikeAmount == 1) { ?>
			 · <a href="#"><div class="facebookTinyLike"></div>
             <?php echo $commentLikeAmount ?> Person </a>
		<?php } ?>
           
       </div>
       </div>
      <?php }}  /*end of comment logic*/ } ?>
      
      
      
<?php function statusHeader ($id,$name,$to) { ?>
	
	<div class="facebookPhoto">
     <a href="http://www.facebook.com/profile.php?id=<?php echo $id; ?>" target="_blank">
     	<img src="https://graph.facebook.com/<?php echo $id; ?>/picture?type=square"/>
     </a>
    </div>
     <div class="facebookMainContent">
      <div class="facebookName">
	   <?php if (!empty($to)) { ?>
         <a href="http://www.facebook.com/profile.php?id=<?php echo $id; ?>" target="_blank"><?php echo $name; ?></a>
         	<div class='facebookArrowRight'></div> 
         <a href="http://www.facebook.com/profile.php?id=<?php echo $to['id']; ?>" target="_blank"><?php echo $to['name'];?></a>
   
       <?php } else { ?>
         <a href="http://www.facebook.com/profile.php?id=<?php echo $id; ?>" target="_blank"><?php echo $name; ?></a> 
       <?php } ?>
      </div>
	
<?php } ?>

<?php if(!function_exists('facebookStatus')) { function facebookStatus ($statusMessage) { ?>
	
    <div class="facebookComment"><?php echo $statusMessage; ?></div>
		
<?php }} ?>


<?php function facebookPhoto ($postId,$photoMessage,$photoLink,$photoPicture,$photoName) { ?>
	
		<div class="facebookComment"><?php echo $photoMessage; ?></div>
        
         <div class="facebookPictureContainer">
          <a href="<?php echo $photoLink; ?>" target="_blank">
             <div class="facebookPicture" id="photo<?php echo $postId; ?>">
             	<img src="<?php echo $photoPicture; ?>"/>
             </div>
          </a>
          <div class="facebookPictureName"><a href="<?php echo $photoLink ?>" target="_blank"><?php echo $photoName ?></a></div>
         </div>
        
<?php } ?>


<?php function facebookVideo ($postId,$videoMessage,$videoSource,$videoPicture,$videoName,$videoCaption,$videoDescription) { ?>
	
    <div class="facebookComment"><?php echo $videoMessage; ?></div>
    
    <div class="facebookPictureContainer">
     <a onclick="embedVideo('<?php echo $videoSource; ?>','<?php echo $postId; ?>');">
      <div class="facebookPicture" id="video<?php echo $postId; ?>" style="background-image:url(<?php echo $videoPicture; ?>); min-width:120px; min-height:90px;">
      	<div class="facebookVideoThumb"></div>          
      </div>
     </a>
     
     <div class="facebookPictureName" id="video<?php echo $postId; ?>name" ><a href="<?php echo $videoLink; ?>" target="_blank"><?php echo $videoName; ?></a></div>
     <div class="facebookLinkCaption" id="video<?php echo $postId; ?>caption"><a href="http://<?php echo $videoCaption; ?>" target="_blank"><?php echo $videoCaption; ?></a></div>
     <div class="facebookLinkDescription" id="video<?php echo $postId;?>description"><?php echo $videoDescription; ?></div>
    </div>
		
<?php } ?>

<?php function facebookLink ($postId,$linkMessage,$linkLink,$linkPicture,$linkLink,$linkCaption,$linkDescription) { ?>
	
    <div class="facebookComment"><?php echo $linkMessage; ?></div>
                
    <div class="facebookPictureContainer">
     <a href="<?php echo $linkLink; ?>" target="_blank">
         <div class="facebookPicture" id="photo<?php echo $postId; ?>">
             <img src="<?php echo $linkPicture; ?>"/>
         </div>
     </a>
     <div class="facebookPictureName"><a href="<?php echo $linkLink; ?>" target="_blank"><?php echo $linkName; ?></a></div>
     <div class="facebookLinkCaption"><a href="http://<?php echo $linkCaption; ?>" target="_blank"><?php echo $linkCaption; ?></a></div>
     <div class="facebookLinkDescription"><?php echo $linkDescription; ?></div>
    </div>
		
<?php } ?>

<?php function facebookSwf ($postId,$swfMessage,$swfSource,$swfPicture,$swfLink,$swfName,$swfCaption,$swfDescription) { ?>
	       
   <div class="facebookComment"><?php echo $swfMessage; ?></div>
   
   <div class="facebookPictureContainer">
    <a onclick="embedVideo('<?php echo $swfSource; ?>','<?php echo $postId; ?>');">
     <div class="facebookPicture" id="video<?php echo $postId; ?>" style="background-image:url(<?php echo $swfPicture; ?>); min-width:120px; min-height:90px;">
     	<div class="facebookVideoThumb"></div>          
     </div>
    </a>
        
    <div class="facebookPictureName" id="video<?php echo $postId; ?>name" >
    	<a href="<?php echo $swfLink; ?>" target="_blank"><?php echo $swfName; ?></a>
    </div>
    
    <div class="facebookLinkCaption" id="video<?php echo $postId; ?>caption">
    	<a href="http://<?php echo $swfCaption; ?>" target="_blank"><?php echo $swfCaption; ?></a>
    </div>
    
    <div class="facebookLinkDescription" id="video<?php echo $postId;?>description"><?php echo $swfDescription; ?></div>
  </div>
		
<?php } ?>


<?php function facebookControls ($postId,$timeCreated,$commentCount,$likeCount) { ?>

<div class="facebookControl">
       <a href="#" style="color:#999999;"><?php echo $timeCreated ?></a>
        · <a href="javascript:void(0);" id="like<?php echo $postId; ?>" onclick="like('<?php echo $postId; ?>');">Like</a> · 
           <a href="javascript:void(0);" 
               onclick="<?php if ($commentCount == 0 && $likeCount == 0) { ?> 
                    showComment('<?php echo $postId; ?>'); 
                    <?php } else { ?>
                    document.comment_<?php echo $postId; ?>.inputComment<?php echo $postId; ?>.focus();
                    <?php } ?>">
           Comment</a>
      </div>

<?php } ?>


<?php function likeCount ($likeCount,$likeName) { 
 	if($likeCount >= 1) { ?>
      <div class="facebookLikes">
       <a href="#"><div class="facebookSmallLike"></div><?php 
		  if($likeCount > 1) {echo $likeCount." people";} elseif ($likeCount = 1) {echo $likeName;} ?>
       </a> like this 
      </div>
    <?php } ?>

<?php } ?>

<?php function viewAllComments ($postId,$commentCount,$currentCommentCount) { ?>

 	<div id="viewAllComment<?php echo $postId; ?>">		
          <?php if ($commentCount > $currentCommentCount) { ?>
           <div class="facebookExtraComment">
              <img src="http://static.ak.fbcdn.net/rsrc.php/v1/yg/r/V8Yrm0eKZpi.gif" width="16px" height="16px"/>
                  <a href="javascript:void(0);" onclick="viewAllComment('<?php echo $postId; ?>');">
                      View all <?php echo $commentCount; ?> comments
                  </a>
           </div>
          <?php } ?>

<?php } ?>

<?php function inputComment ($postId,$commentCount,$likeCount) { ?>
	
	<div id="InputComment_<?php echo $postId; ?>" <?php if ($commentCount == 0 && $likeCount == 0) echo 'style="display:none;"'; ?>>
      <?php if ($commentCount == 0 && $likeCount == 0) echo '<div class="facebookArrow" id="facebookArrow'.$postId.'"></div>'; ?>
       <div class="facebookInputComment">
        <div class="facebookInputCommentContainer">
 		 <form 
          name="comment_<?php echo $postId; ?>" 
          action="#" 
          onsubmit="comment(document.comment_<?php echo $postId; ?>.inputComment<?php echo $postId; ?>.value,'<?php echo $postId;?>',<?php echo $commentCount;?>);" 
         >
          <input 
          		 class="inputComment"
                 id="inputComment<?php echo $postId; ?>" 
                 value="Write a comment..." 
                 onblur="javascript:this.value = 'Write a comment...'" 
                 onfocus="javascript:this.value = ''" 
                 autocomplete="off" 
          />
        </form>
       </div>
      </div> 
     </div>
	
<?php } ?>	









