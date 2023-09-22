<?
//
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");
//
session_start();
//print_r($_SESSION);
if ($_SESSION["isLogin"] != 1) {
	echo "Please login...";
	header("refresh:3; url=login.php");
	exit(0);
}
$clientId = $_SESSION["userId"];
$loginId = $_SESSION["loginId"];
$pinState = 0;
$pinLogin = 0;
	echo '<script type="text/javascript">setTimeout(function() { window.location.href = "https://mobile.bcasekuritas.co.id/tlogout.php"; }, 15 * 60000);</script>';
if ($_SESSION["pinState"] != 1) {
	echo "<script>alert('Please Input PIN first.');</script>";
	header("refresh:1; url=inPin.php");
	exit(0);
} else {
	$pinState = $_SESSION["pinState"];
	$pinLogin = $_SESSION["pin"];
}
?>
<html>
<head>
	<title>Change Pin</title> 	
	<meta name="viewport" content="width=device-width, initial-scale=1"> 	
	<? include "inc-common.php" ?>	
	<script type="text/javascript">
	
	$(document).ready(function() {
		var userId = "<?=$clientId;?>";
		var pinId = "<?=$pinLogin;?>";
		jQuery('body').on('pagehide','div',function(event,ui){
			var page = jQuery(event.target);
			if(page.attr('data-cache') == 'never'){
				page.remove();
			};
		});
		setInput();
	});

	function setInput() {
		$("#chgBtn").click(function(){
			var userId = "<?=$loginId;?>";
			var clientId = "<?=$clientId;?>";
			var oldPin = document.getElementById('oldPin').value;
			var newPin = document.getElementById('newPin').value;
			var conPin = document.getElementById('conPin').value;
			var pattern = /^(?=.*)(?=.*\d)[\d]{8,}$/;

			if(oldPin == ''){
				alert('Pin Lama Tidak Boleh Kosong');
				return;
			}
			else if(newPin == ''){
				alert('Pin Baru Tidak Boleh Kosong');
				return;
			}
            else if(newPin != conPin) {
				alert('Confirm Pin Tidak Sama');
				return;
            }
            else if(!pattern.test(newPin)) {
                alert("Pastikan Pin baru memiliki minimum 8 digit dan hanya angka.");
                return;
			} 
			else{
				//alert(userId + clientId + oldPin + newPin + conPins);
				tr000216(userId, clientId, oldPin, newPin, conPin);
			}
		});
	}

	function tr000216(userId, clientId, oldPin, newPin, conPin){
		$.ajax({
			type : "POST",
			url : "json/trproc.php",
			dataType : "json",
			data : "tr=000216&userId=" + userId + "&clientId=" + clientId + "&pinCurrent=" + oldPin + "&pinNew=" + newPin + "&pinConfirm=" + conPin,
			success: function(jsRet){
				if (jsRet.out[0].status == 0){
					alert('Error : ' + jsRet.out[0].msg);
				} else {
					alert('Change Pin Success');
					window.location.replace("index.php");
				}
			},
			error: function(data, status, err) {
				console.log("error forward : "+data);
				//alert('error communition with server.');
			}
		});
	}
	

</script>
</head> 
<body>
	<form method="POST">
			<div class="container">
				<div class="wrapper">
					<!-- Sidebar  -->
					<?php include "sidebar.php"?>
					<!-- Page Content  -->
					<div id="content">
						<?php include "page-header.php"?>
						<div class="card card-default">
						<div class="card-body" style="padding-bottom: 60px;">
							<form>
								<div class="form-group">
									<label for="LoginID">Login ID</label>
									<input type="text" class="form-control" id="loginId" name="loginId" value="<?=$loginId?>" readonly>
								</div>
								<div class="form-group">
									<label for="ClientID">Client ID</label>
									<input type="text" class="form-control" id="clientId" name="clientId" value="<?=$clientId?>" readonly>
								</div>
								<div class="form-group">
										<label for="oldPin">Old Pin</label>
										<input type="password" class="form-control" id="oldPin" name="oldPin" placeholder="Input Your Old Pin">
									</div>
								<div class="form-group">
									<label for="newPin">New Pin</label>
									<input type="password" class="form-control" id="newPin" name="newPin" placeholder="Input Your New Pin">
								</div>
								<div class="form-group">
										<label for="InputPin">Confirm Pin</label>
										<input type="password" class="form-control" id="conPin" name="conPin" placeholder="Re-input Your New Pin">
									</div>
								<button type="submit" class="btn btn-info" id="chgBtn">Update</button>
								<a href="#" class="btn btn-light" onclick="goIndex();return false;">Cancel</a>
							</form>
						</div>
					</div>
					<footer class="fixed-bottom">
						<div class="card text-white bg-info">
							<div class="card-header" style="text-align: center;max-height: 50px;">
								Footer
							</div>
						</div>
					</footer>
				</div>
			</div>			
			<div class="overlay"></div>
		</div>
	</form>
</body>
</html>
