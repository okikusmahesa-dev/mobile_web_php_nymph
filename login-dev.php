<?
//header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
//header("Cache-Control: no-cache");
//header("Pragma: no-cache");
set_time_limit(0);
	session_start();
//	session_destroy();

if(isset($_SESSION['userId']) || isset($_SESSION['loginId'])){
    header("Location: index.php");
}

?>
<html>
<meta name="viewPort" content="width=device-width, initial-scale=1.0" />
<head> 
	<title>BEST Mobile</title> 
	<? include "inc-common.php" ?>
	<style type="text/css">
	<!--
	<? include "css/icon.css" ?>
	//-->
	</style>
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
	
	<style type="text/css">
		.lg-container{
			width:auto;
			margin:auto auto;
			padding:20px 40px;
			border:2px solid #aaa;
			background:#fff;
			border-radius:10px;
		}
	</style>
	

</head> 
<body onload="noBack()" onpageshow="if(event.persisted)noBack();" onunload="">
	<div data-role="page" id="login" data-cache="never">
	  <div data-role="header" data-theme="b">
		<h1>BEST Mobile</h1>
	  </div>
	  <div data-role="content">
		<div class="ui-grid-solo">
		<center>
			<img src="img/logo-front.png"/>
		</center>
		</div>
		<p>
		<form method="post" action="index.php">
			<div id="loginformDiv" class="lg-container">
				<h1> Login </h1>
				<div id="usernameDiv" data-role="field-contain">
					<input type="text" name="userid" placeholder="Login ID" id="userid"/>
				</div>
				<div id="passwordDiv" data-role="field-contain">
					<input type="password" name="passwd" placeholder="Password" id="passwd"/>
					<input type="hidden" name="ip" placeholder="ip" id="ip" value="<?php echo $_SERVER['REMOTE_ADDR'];  ?>" />
				</div>
				<div id="okDiv" data-role="field-contain">
					<button type="submit" data-inline="true" data-theme="b"> Login </button>
				</div>
			</div>
		</form>
	  
	     <center>
<!--	     <span id="siteseal"><script type="text/javascript" src="https://seal.godaddy.com/getSeal?sealID=3UKNeeAb5sLFhLC9GBQTVeAzAJvtKLXpBFboXZAwAxV3SIswmKjxSnFvA3Yy"></script></span>
-->
           <span id="siteseal"><script async type="text/javascript" src="https://seal.godaddy.com/getSeal?sealID=n4Tgq8gmamS7T5wi49E5BUW4VY1FA0mBH7EB9WMU20S9x3BIflE8DJlce2Es"></script></span>
        </center>
		<center>
			<p>
				<b>Rasakan pengalaman baru di <br>New BEST Mobile 2.0</b>
			</p>
			<p>
				<b>Klik icon/link dibawah ini : </b>
			</p>
		</center>
		<center style="margin-top:-50px">
		 <a href="https://play.google.com/store/apps/details?id=com.bcasekuritas.BestMobile" style="text-decoration:none;">
			<img src="img/google-play.png" style="width:150px"/>
		</a>
		<a href="https://itunes.apple.com/us/app/bcas-best-mobile-2-0/id1441407981?mt=8" style="text-decoration:none;">
			<img src="img/app-store.png" style="width:150px"/>
		</a>
		</center>
	  </div>
	</div>
</body>
</html>
