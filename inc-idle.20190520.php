<?php 
	session_start();

	//if (!empty($_SESSION["pin"]) || $_SESSION["pinState"] != 1){
	if (!empty($_SESSION["pin"]) || $_SESSION["pinState"] != 0) { 
?> 
	<script type="text/javascript" src="js/jquery.idle.min.js"></script>
	<script type="text/javascript">
	idle({
    	onIdle: function(){
			alert("Silahkan Input PIN Untuk Melakukan Transaksi Beli/Jual");
			window.location.href = "inPin.php";
      	},
      	//idle: 5 * 60000, //60000 -> 60Sec/1Min
      	idle: 1 * 10000, //60000 -> 60Sec/1Min
      	keepTracking: true
	}).start();	
	</script>
<?php 
} 
?>

