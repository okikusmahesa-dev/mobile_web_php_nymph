<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");

session_start();
//print_r($_SESSION);
if ($_SESSION["isLogin"] != 1) {
	echo "Please login...";
	header("refresh:3; url=login.php");
	exit(0);
}
$userId = $_SESSION["userId"];
$clientId = $_SESSION["loginId"];
?>
<html>
<head>
	<title>Input Pin</title> 
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<? include "inc-common.php" ?>
	<style type="text/css">
	<!--
	//-->
	</style>
	<script type="text/javascript">
		//
		$(function() {
			$('form').submit(function() {
				$id = $('#id').val();
				$pin = $('#pin').val();
				///*
				if ( idFill() && pinFill() ) {
					tr000200($pin);
				}
				//*/
				function idFill() {
					if($('#id').val().length == 0) {
						alert('Client ID cannot be empty');
						return false;
					} else {
						return true;
					}
				}
				
				function pinFill() {
					if($('#pin').val().length == 0) {
						alert('Pin cannot be empty');
						return false;
					} else {
						return true;
					}
				}
				
				return false;
			})
		});
	
		function tr000200(pin) {
			var id = "<?=$userId?>";
			var clientId = "<?=$clientId?>";
			$.ajax({
				type: "POST",
				url: "pin-do.php",
				dataType : "json",
				data: "tr=000200&id=" + id + "&pin=" + pin + "&clientId=" + clientId,
				success: function(jsRet){
					if (jsRet.status != 1) {
						alert(jsRet.mesg);
					} else {
						console.log(jsRet);
						//
						statusFld = jsRet.out[0].status;
						mesgFld = jsRet.out[0].mesg;
						//
						if (statusFld == "0") {
							alert(mesgFld + ": " + statusFld);
						}
						else {
							window.open("index.php", "_self");
						}
						//
					}
				},
				error: function(data, status, err) {
					console.log("error forward : "+data);
					//alert('error communition with server.');
				}
			});
		}
		//
	</script>
</head>
<body>
<div data-role="page" id="inPin">
	<div data-role="header" data-theme="b">
		<h1>Please Input Pin</h1>
	</div>
<form method="post">

	<div data-role="main" class="ui-content">
		<div data-role="fieldcontain">
			<label for="id">Client ID</label>
			<input type="text" name="id" id="id" data-mini="true" value="<?=$userId?>" readonly="readonly">
		</div>
		<div data-role="fieldcontain">
			<label for="pin">Input Pin</label>
			<input type="password" name="pin" id="pin" data-mini="true" placeholder="Input Pin">
		</div>
		<button type="submit" data-inline="true" data-theme="b" data-mini="true"> Ok </button>
		<a href="index.php" data-inline="true" data-role="button" data-theme="b" data-mini="true">Cancel</a>
	</div>

</form>
	<div data-role="footer" data-theme="b">
		<h1>BCA Sekuritas Mobile Trading</h1>
	</div>
</div>
</body>
</html>
