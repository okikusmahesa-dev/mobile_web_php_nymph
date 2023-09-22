<?
//
header("Cache-Control: no-cache");
header("Pragma: no-cache");
//
$userId = $_SESSION["userId"];
$loginId = $_SESSION["loginId"];
?>
<html>
<head> 
	<title>Chat Forum</title> 
	<meta name="viewport" content="width=device-width, initial-scale=1"> 
	<? include "inc-common.php" ?>
	<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>  
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>  
    <style>  
      .message-bubble{  
	     padding: 10px 0px 10px 0px;  
	  }  
	  .message-bubble:nth-child(even) { background-color: #F5F5F5; }  
	  .message-bubble > * {  
	     padding-left: 10px;    
	  }  
	  .panel-body { padding: 0px; }  
	  .panel-heading { background-color: #3d6da7 !important; color: white !important; }  
	  </style>  



	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">  	
	<script src="https://www.gstatic.com/firebasejs/3.6.4/firebase.js"></script>
	<script type="text/javascript">
	var userId = "<?=$userId;?>";
	var now = new Date();
	today = now.format("yyyymmdd");
	
	var config = {
		apiKey: "AIzaSyCbPfxpwVR0NhU1R0MJqFHPA2RvbxYMzCA",
		authDomain: "tokuns-1e79c.firebaseapp.com",
		databaseURL: "https://tokuns-1e79c.firebaseio.com",
		storageBucket: "tokuns-1e79c.appspot.com",
		messagingSenderId: "200870335992"
	};
	firebase.initializeApp(config);  

	$(document).ready(function() {
		
		var userId = "<?=$userId;?>";
		var loginId = "<?= strtoupper($loginId);?>";
		var rootchatref = firebase.database().ref('/');  
        var chatref = firebase.database().ref('/Chat');  
        chatref.on('child_added', function(snapshot) {  
            var data = snapshot.val();  
            //alert(data);  
            $('#chat_data').append('<div class="row message-bubble"><p class="text-muted"><b><u>'+data.user+'</u></b></p><span>'+data.msg+'</span></div>');  
        });  
		$("#sendBtn").one("click",function(){
		});

	});
	
</script>
</head> 
<body>
	<div data-role="page" id="home" data-cache="never">
	<? include "page-header-chat.php" ?>
	<div class="buy-panel">
	<!-- <b>Buy Order</b> -->
	<table style="overflow-x:auto;">

	<tr>
		<td width="25%">
			<div class="container">
				<div class="row">
					<div class="container" id="chat_data" style="height: 65%; overflow-y:auto;">
					</div>
				</div>
			</div>
		
		</td>
	</tr>
	
	</table>
	</div>
	<? include "page-footer-chat.php" ?>
	</div>
</body>
</html>
