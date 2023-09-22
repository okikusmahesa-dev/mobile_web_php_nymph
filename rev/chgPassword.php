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
	<title>Change Password</title> 
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
			var oldPwd = document.getElementById('oldPwd').value;
			var newPwd = document.getElementById('newPwd').value;
			var conPwd = document.getElementById('conPwd').value;
			var pattern = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;

			if(oldPwd == ''){
				alert('Password Lama Tidak Boleh Kosong');
				return;
			}
			else if(newPwd == ''){
				alert('Password Baru Tidak Boleh Kosong');
				return;
			}
            else if(newPwd != conPwd) {
				alert('Confirm Password Tidak Sama');
				return;
            }
            else if(!pattern.test(newPwd)) {
                alert("Pastikan password baru memiliki minimum 8 karakter dan setidaknya 1 buah angka dan huruf.");
                return;
			} 
			else{
				//alert(userId + clientId + oldPass + newPass + conPasss);
				tr000217(userId, clientId, oldPwd, newPwd, conPwd);
				//window.location.replace("index.php");
                //alert("change password");
			}
		});

	}

	function tr000217(userId, clientId, oldPass, newPass, conPass){
		$.ajax({
			type : "POST",
			url : "json/trproc.php",
			dataType : "json",
			data : "tr=000217&userId=" + userId + "&clientId=" + clientId + "&passCurrent=" + oldPass + "&passNew=" + newPass + "&passConfirm=" + conPass,
			success: function(jsRet){
				if (jsRet.out[0].status == 0){
					alert('Error : ' + jsRet.out[0].mesg);
				} else {
					//alert(jsRet.status+', userId = '+userId+', pass = '+password);
					alert('Change Password Success');
					window.location.replace("index.php");
				}
			},
			error: function(data, status, err) {
				console.log("error forward : "+data);
				//alert('error communition with server.');
			}
		});
	}
	
	//start chgPassword
	// function tr830000(userId, password) {
	// 	$.ajax({
	// 		type: "POST",
	// 		url: "json/trproc.php",
	// 		dataType : "json",
	// 		data: "tr=830000&userId=" + userId + "&password=" + password,
	// 		success: function(jsRet){
	// 			if (jsRet.status != 1) {
	// 				alert('There is an error with our server');
	// 			} else {
	// 				//alert(jsRet.status+', userId = '+userId+', pass = '+password);
	// 				alert('Your Password has been changed.');
	// 				window.location.replace("index.php");
	// 			}
	// 		},
	// 		error: function(data, status, err) {
	// 			console.log("error forward : "+data);
	// 			//alert('error communition with server.');
	// 		}
	// 	});
	// }
	//end chgPassword



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
									<label for="oldPassword">Old Password</label>
									<input type="password" class="form-control" id="oldPwd" name="oldPwd" placeholder="Input Your Old Password">
								</div>
							<div class="form-group">
								<label for="newPassword">New Password</label>
								<input type="password" class="form-control" id="newPwd" name="newPwd" placeholder="Input Your New Password">
							</div>
							<div class="form-group">
									<label for="InputPIN">Confirm Password</label>
									<input type="password" class="form-control" id="conPwd" name="conPwd" placeholder="Re-input Your New Password">
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
