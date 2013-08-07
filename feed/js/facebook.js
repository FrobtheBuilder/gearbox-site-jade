       
	   window.fbAsyncInit = function() {
		   		// Input your APP ID here.
                FB.init({appId: 'YOUR APP ID', status: true, cookie: true, xfbml: true});
				
                /* All the events registered */
                FB.Event.subscribe('auth.login', function(response) {
                    // do something with response
					window.location.reload();
                });
                FB.Event.subscribe('auth.logout', function(response) {
                    // do something with response	
					window.location.reload();				
                });
 
                FB.getLoginStatus(function(response) {
                    if (response.session) {
                        // logged in and connected user, someone you know
                    }
                });
            };
            (function() {
                var e = document.createElement('script');
                e.type = 'text/javascript';
                e.src = document.location.protocol +
                    '//connect.facebook.net/en_US/all.js';
                e.async = true;
                document.getElementById('fb-root').appendChild(e);
            }());
 
 			function updateHTML(elmId, value) {
        		document.getElementById(elmId).innerHTML = value;
      		}
						
		   function viewAllComment(id) {
				updateHTML("viewAllComment"+id+"",'<div class="facebookSubCommentContainer"><img src="images/loading.gif" style="margin-left:150px;" /></div>');
				$("#viewAllComment"+id+"").load("loadin.php?a=viewAllComment", { 'array[]': ["" + id + ""] } );
		   }
		   
		   function like(id) {
			   	updateHTML("like"+id,"Unlike");
			    $("#Query").load("loadin.php?a=like", { 'array[]': ["" + id + ""] } );
		   }
		   
		   function embedVideo (videoSource,postId) {
			   // Creates the embed code to put into the feed.
			   embedvideo = '<object width="425" height="349"><param name="movie" value="'+videoSource+'"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="'+videoSource+'" type="application/x-shockwave-flash" width="425" height="349" allowscriptaccess="always" allowfullscreen="true"></embed></object>';
				
				document.getElementById('video'+postId).innerHTML = embedvideo;
				
				// hides video name,caption,description.
				document.getElementById('video'+postId+'name').innerHTML = "";
				document.getElementById('video'+postId+'caption').innerHTML = "";
				document.getElementById('video'+postId+'description').innerHTML = "";
		   }
		   
		   
		   function comment(message,id,commentCount) {
				 document.getElementById("inputComment"+id).value = "Write a comment...";
				
			     updateHTML("viewAllComment"+id+"",'<div class="facebookSubCommentContainer"><img src="images/loading.gif" style="margin-left:150px;" /></div>');
			    $("#viewAllComment"+id).load("loadin.php?a=comment", { 'array[]': ["" + message + "",""+id+"",""+ commentCount +""] } );
		   }
		   
		   function photo (id,photoLink) {
			   
			    $("#photo"+id).load("loadin.php?a=photoChange", { 'array[]': [""+photoLink+""] } );
		   }
		   
		   function showComment (id) {
			   
			    document.getElementById('InputComment_'+id).style.display = "inline-block" ;
		   }
		   