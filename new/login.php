<?php 
set_time_limit(0);
session_start();

if(isset($_SESSION['userId']) || isset($_SESSION['loginId'])){
    header("Location: index.php");
}

?>
<!DOCTYPE html>
<html lang="en" dir="ltr"><meta name="viewPort" content="width=device-width, initial-scale=1.0" />
<head>
	<meta charset="utf-8">
	<title>BEST Mobile</title> 
	<? include "inc-common.php" ?>
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css?v=1.1">
	<script type="text/javascript">
		///*
		$(document).ready(function() {
			jQuery('div').live('pagehide', function(event, ui){
				var page = jQuery(event.target);
				if(page.attr('data-cache') == 'never'){
					page.remove();
				};
			});
		});
		
		$(function() {
			$('form').submit(function() {
				$user = $('#userid').val();
				$pass = $('#passwd').val();
				$ip_adr   = $('#ip').val();
				///*
				if ( userFill() && passFill() ) {
					tr000102($user, $pass);
				}
				//*/
				function userFill() {
					if($('#userid').val().length == 0) {
						alert('Username cannot be empty');
						return false;
					} else {
						return true;
					}
				}
				
				function passFill() {
					if($('#passwd').val().length == 0) {
						alert('Password cannot be empty');
						return false;
					} else {
						return true;
					}
				}
				
				return false;
			})
		});
	
		function tr000102(userid, passwd) {
			var id = userid;
			var pw = passwd;
			var pw2 = "12345678901234567890";
			$user = $('#userid').val();
			$pass = $('#passwd').val();
			$ip_adr   = $('#ip').val();
				
			$.ajax({
				type: "POST",
				url: "login-do.php",
				dataType : "json",
				data: "tr=000102&loginId=" + id + "&loginPw=" + pw + "&pw2=" + pw2,
				success: function(jsRet){
					if (jsRet.status != 1) {
						alert(jsRet.mesg);
					} else {
						//
						userId = jsRet.out[0].userId;
						statusFld = jsRet.out[0].status;
						mesgFld = jsRet.out[0].mesg;
                        disclaim = jsRet.out[0].disclaim;
						console.log('cek 1 '+userId);
						console.log('cek 1 '+statusFld);
						console.log('cek 1 '+mesgFld);
						console.log('cek 1 '+disclaim);

						if (statusFld == "0") {
							alert(mesgFld + " : " + statusFld);
						}
						else {
                            if(disclaim == "N" || disclaim == null){
				 			    tr000105($user, $ip_adr);
                                window.open("disclaim.php?disclaim="+disclaim,"_self")
                            }
                            
                            if(disclaim == "Y"){ 
				 			    tr000105($user, $ip_adr);
                                window.open("index.php?uFlag=1","_self")
                            }
                        }
						//
					}
				},
				error: function(data, status, err) {
					console.log("error forward : "+data);
					alert('error communition with server.');
				}
			});
		}
		
		function tr000105(userid, ip_adr) {
			var id = userid;
			var ip = ip_adr;
			$.ajax({
				type: "POST",
				url: "login-hist-do.php",
				dataType : "json",
				data: "tr=000105&loginId=" + id + "&ip_adr=" + ip,
				success: function(jsRet){
					if (jsRet.status != 1) {
						//alert(jsRet.mesg);
					} else {
						//
						userId = jsRet.out[0].userId;
						statusFld = jsRet.out[0].status;
						mesgFld = jsRet.out[0].mesg;
						//
						if (statusFld == "0") {
							//alert(mesgFld + ": " + statusFld);
						}
						else {
							//window.open("index.php", "_self");
						}
						//
					}
				},
				error: function(data, status, err) {
					//console.log("error forward : "+data);
					//alert('error communition with server.2');
				}
			});
		}
	//*/


        window.history.forward();
		function noBack(){ window.history.forward(); }


	</script>
	


</head> 
<body onload="noBack()" onpageshow="if(event.persisted)noBack();" onunload="">
	<form method="post" action="index.php">
		<div class="card text-white bg-info">
			<div class="card-header" style="text-align: center">
				<div class="title" style="margin: -0.6em 30% -0.9em;">
					BEST Mobile <br>
					<span style="font-size: 12px;">Ver 1.0.0</span>
				</div>
			</div>
		</div>
		<div class="container">
			<br>
				<center>
					<img src="img/logo-front.png" alt="">
				</center>
				<br>
				<div class="card card-default">
					<div class="card-body">
						<form>
							<div class="form-group">
								<label for="loginId">Login ID</label>
								<input type="text" class="form-control" name="userid" id="userid" autocomplete="off" placeholder="Login ID">
							</div> 
							<div class="form-group">
								<label for="loginId">Password</label>
								<input type="password" class="form-control" name="passwd" id="passwd" autocomplete="off" placeholder="Password">
								<input type="hidden" name="ip" id="ip" value="<?= $_SERVER['REKOTE_ADDR']; ?>">
							</div>
							<div class="form-group form-check">
									<input type="checkbox" class="form-check-input" id="exampleCheck1">
									<label class="form-check-label" for="exampleCheck1">Remember ID</label>
									<a href="#" style="float: right">Forgot Password</a>
								  </div>
							<button type="submit" class="btn btn-info">Login</button>
						</form>
					</div>
				</div>
				<br>
			<center>
					<span id="siteseal"><script async type="text/javascript" src="https://seal.godaddy.com/getSeal?sealID=MqdtosGXRlwCRWQLEWSMwFH66Ee5zzocEr3448gh0Ax4Skv64F6mygmqAimK"></script></span>
					<br><br>
					<p>
							Dapatkan Fitur yang Lebih Lengkap di <br>New BEST Mobile 2.0
						</p>
						<p>
							Klik icon/link dibawah ini :
						</p>
						<div style="margin-top: -60px;">
								<a href="https://play.google.com/store/apps/details?id=com.bcasekuritas.BestMobile" style="text-decoration:none;">
									<img src="img/google-play.png" style="width:150px"/>
								</a>
								<a href="https://itunes.apple.com/us/app/bcas-best-mobile-2-0/id1441407981?mt=8" style="text-decoration:none;">
									<img src="img/app-store.png" style="width:150px"/>
								</a>
						</div>
			</center>
		</div>
	</form>
</body>
</html>
