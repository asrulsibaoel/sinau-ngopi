<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title> 

	<link rel="stylesheet" href="{{ asset('css/chat.css')}}">

	<script src="https://code.jquery.com/jquery-1.11.3.js"></script>
 
 	<script type="text/javascript">
 		var lastNextID=0;
 		var nextChat=function(){
 			$.ajax({
 					url:"{{url('/chats')}}",
 					type:"GET",
 					data:{
 						action:'get_content_chat',
 						users_id:{{$users_id}},
 						chats_rooms_id:{{$chats_rooms_id}},
 						last_id:lastNextID
 					},
 					success:function(result_data){
 						$("#main-chat").append(result_data);
 						if(result_data!=""){
							$("body").animate({scrollTop:10000000000},1000)
 						}else{
 							if(result_data=="" && $("#main-chat").html()==""){
 							
 								// $("#main-chat").html('<div class="direct-chat-text">Chat Not Found</div>');
 							}
 						}
 					},
 					failure:function(err){
 						console.log("failed:"+err);
 					}
 			});
 		}

 		$(document).ready(function(){
			nextChat();

 			setInterval(function(){
 				nextChat();
 			},1000)
 		})
 	</script>
</head>
<body>

	<div id="main-chat" class="direct-chat-messages"></div>
</body>
</html>