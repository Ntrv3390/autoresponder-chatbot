<?php
date_default_timezone_set('Asia/Kolkata');
include('database.inc.php');

?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta name="robots" content="noindex, nofollow">
      <title>Chatbot for Sciastra.com</title>
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	  <link href="style.css" rel="stylesheet">
      <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
      <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
   </head>
   <body>
<!---Navbar starts-->

			<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
			<a class="navbar-brand" href="#">ChatBot for Sciastra.com</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav mr-auto">
				<li class="nav-item active">
					<a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
				</li>
				</ul>
			</div>
			</nav>

<!---Navbar ends--> 
	<div class="text-center" style="margin-top:30px;">
		<b>ChatBot for Sciastra.com</b>
		<p>Features:</p>
		<p>-Stores the chat till 24 hours</p>
		<p>-All the answers are dynamic</p>
		<p>-Returns a message if answer does not exists in database</p>
		<p>-Can help students with general queries and faqs</p>
	</div>
      <div class="container">
         <div class="row justify-content-md-center mb-4">
            <div class="col-md-6">
               <!--start code-->
			   <div class="closing-button">
					<button onclick="hideChat()">
						<img width="50%" src="image/cross.png" alt="Icon">
					</button>
				</div>
               <div class="card">
                  <div class="card-body messages-box">
				  
					 <ul class="list-unstyled messages-list">
							<?php
							$session_id = $_SESSION["ID"];
							$res=mysqli_query($con,"select * from message where session_id='$session_id'");
							if(mysqli_num_rows($res)>0){
								$html='';
								while($row=mysqli_fetch_assoc($res)){
									$message=$row['message'];
									$added_on=$row['added_on'];
									$strtotime=strtotime($added_on);
									$time=date('h:i A',$strtotime);
									$type=$row['type'];
									if($type=='user'){
										$class="messages-me";
										$imgAvatar="user_avatar.png";
										$name="Me";
									}else{
										$class="messages-you";
										$imgAvatar="robot.png";
										$name="Chatbot";
									}
									$html.='<li class="'.$class.' clearfix"><span class="message-img"><img src="image/'.$imgAvatar.'" class="avatar-sm rounded-circle"></span><div class="message-body clearfix"><div class="message-header"><strong class="messages-title">'.$name.'</strong> <small class="time-messages text-muted"><span class="fas fa-time"></span> <span class="minutes">'.$time.'</span></small> </div><p class="messages-p">'.$message.'</p></div></li>';
								}
								echo $html;
							}else{
								
								$html='<li class="messages-you clearfix"><span class="message-img"><img width="20%" src="image/robot.png" class="avatar-sm rounded-circle"></span><div class="message-body clearfix"><div class="message-header"><strong class="messages-title">ChatBot</strong> <small class="time-messages text-muted"><span class="fas fa-time"></span> </small></br> </div><p class="messages-p">Hi there, what can I do for you?</p></div></li>';
								echo $html;
							}
							?>
                    
                     </ul>
                  </div>
                  <div class="card-header">
                    <div class="input-group">
					   <input id="input-me" type="text" name="messages" class="form-control input-sm" placeholder="Type your message here..." />
					   <span class="input-group-append">
					   <input type="button" class="btn btn-primary" value="Send" onclick="send_msg()">
					   </span>
					</div> 
                  </div>
               </div>
               <!--end code-->

			  


            </div>
         </div>
      </div>
	  <div class="floating-button">
					<button onclick="showChat()">
						<img src="image/robot.png" alt="Icon">
					</button>
				</div>




      <script type="text/javascript">
		jQuery('.col-md-6').hide();
		function showChat()
		{
			jQuery('.col-md-6').show();
			jQuery('.floating-button').hide();
			jQuery('.messages-box').scrollTop(jQuery('.messages-box')[0].scrollHeight);
		}
		function hideChat()
		{
			jQuery('.col-md-6').hide();
			jQuery('.floating-button').show();
		}
		 function getCurrentTime(){
			var now = new Date();
			var hh = now.getHours();
			var min = now.getMinutes();
			var ampm = (hh>=12)?'PM':'AM';
			hh = hh%12;
			hh = hh?hh:12;
			hh = hh<10?'0'+hh:hh;
			min = min<10?'0'+min:min;
			var time = hh+":"+min+" "+ampm;
			return time;
		 }
		 function send_msg(){
			jQuery('.start_chat').hide();
			var txt=jQuery('#input-me').val();
			var html='<li class="messages-me clearfix"><span class="message-img"><img src="image/user_avatar.png" class="avatar-sm rounded-circle"></span><div class="message-body clearfix"><div class="message-header"><strong class="messages-title">Me</strong> <small class="time-messages text-muted"><span class="fas fa-time"></span> <span class="minutes">'+getCurrentTime()+'</span></small> </div><p class="messages-p">'+txt+'</p></div></li>';
			jQuery('.messages-list').append(html);
			jQuery('#input-me').val('');
			if(txt){
				jQuery.ajax({
					url:'get_bot_message.php',
					type:'post',
					data:'txt='+txt,
					success:function(result){
						var html='<li class="messages-you clearfix"><span class="message-img"><img src="image/bot_avatar.png" class="avatar-sm rounded-circle"></span><div class="message-body clearfix"><div class="message-header"><strong class="messages-title">Chatbot</strong> <small class="time-messages text-muted"><span class="fas fa-time"></span> <span class="minutes">'+getCurrentTime()+'</span></small> </div><p class="messages-p">'+result+'</p></div></li>';
						jQuery('.messages-list').append(html);
						jQuery('.messages-box').scrollTop(jQuery('.messages-box')[0].scrollHeight);
					}
				});
			}
		 }
      </script>
   </body>
</html>