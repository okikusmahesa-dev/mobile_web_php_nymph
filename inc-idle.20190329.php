<?php 
	session_start();

	//if (!empty($_SESSION["pin"]) || $_SESSION["pinState"] != 1){
	if (!empty($_SESSION["pin"]) || $_SESSION["pinState"] != 0) { 
?> 
	<script type="text/javascript" src="js/jquery.idle.min.js"></script>
	<script type="text/javascript">
	idle({
    	onIdle: function(){
        	alert('Test ' + window.location);
			window.location.href = "inPin.php";
      	},
      	idle: 10000, //20000 -> 20Sec
      	keepTracking: true
	}).start();	
	</script>
<?php 
} 
?>

