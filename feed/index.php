<?php 
	session_start(); 
	require "functions.php";
?><head>   
     <meta http-equiv="Content-Type" content="charset=UTF-8" />
   
     
	<div id="fb-root"></div> 
     <script type="text/javascript" src="js/facebook.js"></script>
     <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
     <link rel="stylesheet" href="css/facebook.css" type="text/css" />
    </head>
     
     <fb:login-button autologoutlink="true" perms="friends_status,user_status,status_update,read_stream,publish_stream"></fb:login-button>
    
 <?php 
 	if(!empty($uid)){
		  $array = $facebook->api('/me/home?access_token='.$session['access_token'].'&limit=20');
		  feed($array); 
	   } 
 ?>
 
 
 
 