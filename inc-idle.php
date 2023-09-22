<?php 
	session_start();

	//if (!empty($_SESSION["pin"]) || $_SESSION["pinState"] != 1){
	//$cur_uri = $_SERVER['REQUEST_URI']; 
	//error_log("cur_uri = " . $cur_uri);
	if (!empty($_SESSION["pin"]) || $_SESSION["pinState"] != 0) { 
?> 
	<script type="text/javascript" src="js/jquery.idle.min.js"></script>
	<script type="text/javascript">
	idle({
    	onIdle: function(){
			var pathname = window.location.pathname;
			$.ajax({
				url: "resetPIN.php",
				success: function(result,status,xhr){
					console.log("path = " + pathname);

					if (pathname == "/buyOrderExt.php" || 
						pathname == "/sellOrderExt.php" ||
						pathname == "/orderList.php" ||
						pathname == "/tradeList.php" ||
						pathname == "/portfolio.php" ||
						pathname == "/chgPassword.php" ||
						pathname == "/chgPin.php" ||
						pathname == "/form-withdraw.php" 
						) {
						alert("Silahkan Input PIN Untuk Melakukan Transaksi");
						window.location.href = "inPin.php";
				
					} else {

					}
				}
			});
			
			//alert("Silahkan Input PIN Untuk Melakukan Transaksi Beli/Jual");
			//window.location.href = "inPin.php";
      	},
      	idle: 5 * 60000, //60000 -> 60Sec/1Min
      	//idle: 1 * 10000, //60000 -> 10Sec buat Test
      	keepTracking: true
	}).start();	
	</script>
<?php 
} 
?>

