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
				var str = $('#userid').val();
				str = removeSpecialChars(str);
				$('#userid').val(str);
				
				$user = $('#userid').val();
				$pass = $('#passwd').val();
				$ip_adr   = $('#ip').val();
	
				if ( userFill() && passFill() ) {
					$('#button').button('disable', true);
					tr000102($user, $pass);
				}

				function userFill() {
					if($('#userid').val().length == 0) {
						alert('Username cannot be empty');
						location.reload();
					} else {
						return true;
					}
				}
				
				function passFill() {
					if($('#passwd').val().length == 0) {
						alert('Password cannot be empty');
						location.reload();
					} else {
						return true;
					}
				}
				//$(':button[type="submit"]').prop("disabled", true);

				
				return false;
			})
		});

		function removeSpecialChars(str) {
            var buf = '', code, i, len;
            for (i = 0, len = str.length; i < len; i++) {
                code = str.charCodeAt(i);
                if ((code > 47 && code < 58)    // numeric (0-9)
                    || (code > 64 && code < 91) // upper alpha (A-Z)
                    || (code > 96 && code < 123)) { // lower alpha (a-z)
                    buf = buf + str.substring(i, i + 1);
                } else {

                }
            }
            return buf;
        }
	
		function tr000102(userid, passwd) {
			var id = userid;
			var pw = passwd;
			var pw2 = "12345678901234567890";
			$user = $('#userid').val();
			$pass = $('#passwd').val();
			$ip_adr   = $('#ip').val();

            pHashOrg = "tr=000102&loginId=" + id + "&loginPw=" + pw + "&pw2=" + pw2 + "&deviceInfo=WEB";
            pData = "phash=" + Base64.encode(pHashOrg);
				
			$.ajax({
				type: "POST",
				url: "login-do.php",
				dataType : "json",
				data: pData,
				success: function(jsRet){
					if (jsRet.status != 1) {
						alert(mesgFld);
					} else {
						//
						userId = jsRet.out[0].userId;
						statusFld = jsRet.out[0].status;
						mesgFld = jsRet.out[0].mesg;
                        disclaim = jsRet.out[0].disclaim;

						if (statusFld == "0") {
							//alert(mesgFld + " : " + statusFld);
							alert(mesgFld);
							location.reload();
						} else if (statusFld == "0") {
							return false;
						}
						else {
                            if(disclaim == "N" || disclaim == null){
				 			    tr000105($user, $ip_adr);
                                window.open("disclaim.php?disclaim="+disclaim,"_self")
                            }
                            
                            if(disclaim == "Y"){ 
				 			    tr000105($user, $ip_adr);
                                tr000110($user,$pass); 		
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
	
		
		function tr000110(loginId, password){
			console.log("check tr000110");
			$.ajax({
                type: "POST",
                url: "json/trproc.php",
                dataType : "json",
                data: "tr=000110&loginId=" + loginId + "&password=" + password,
                success: function(jsRet){ 
					remainDay = jsRet.out[0].days;
					messg = jsRet.out[0].mesg;
					stat = jsRet.out[0].status;
					console.log(remainDay,messg,stat);
  					
					if (stat == 1) { 
						if (remainDay != 999) {
							if (remainDay < 0){  
								alert(messg);
								window.open("chgPasswordExpired.php","_self");
							} else {	
								alert(messg);
								window.open("chgPassword.php","_self");
							}            
							
						} else {
							window.open("index.php?uFlag=1","_self");                
						}
					}
					else{
						console.log("skip stat 000110 = 0");
						window.open("index.php?uFlag=1","_self");                
					} 
						
                	
				},
                error: function(data, status, err) {
                    console.log("error forward : "+data);
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
						/*
						userId = jsRet.out[0].userId;
						statusFld = jsRet.out[0].status;
						mesgFld = jsRet.out[0].mesg;	
						if (statusFld == "0") {
							//alert(mesgFld + ": " + statusFld);
						}
						else {
							//window.open("index.php", "_self");
						}
						*/
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
		.lsmi{
			white-space: nowrap;
		}
		@media (min-width: 150px) {
			.nb{
				font-size: 0.4rem !important;
			}
		}
		@media (min-width: 200px) {
			.txti{
				font-size: 0.8rem;
			}
			.nb{
				font-size: 0.6rem !important;
			}
		}
		@media (min-width: 250px) {
			.txti{
				font-size: 0.8rem;
			}
			.nb{
				font-size: 0.8rem !important;
			}
		}
		@media (min-width: 300px) {
			.txti{
				font-size: 1rem !important;
			}
		}
		@media (min-width: 400px) {
			.txti{
				font-size: 1.2rem !important;
			}
		}
		@media (min-width: 400px) {
			.txti{
				font-size: 1.2rem !important;
			}
		}
		@media (min-width: 600px) {
			.txti{
				font-size: 1.4rem !important;
			}
		}
		@media (min-width: 800px) {
			.txti{
				font-size: 1.6rem !important;
			}
		}
		@media (min-width: 1000px) {
			.txti{
				font-size: 1.8rem !important;
			}
		}
		@media (min-width: 1200px) {
			.txti{
				font-size: 2rem !important;
			}
		}
	</style>
	

</head> 
<body onload="noBack()" onpageshow="if(event.persisted)noBack();" onunload="">
	<div data-role="page" id="login" data-cache="never">
	  <div data-role="header" data-theme="b">
		<h3>BEST Mobile<br><span style="font-size:13px;">Ver 1.0.0</span></h3>
	  </div>
	  <div data-role="content">
		<div class="ui-grid-solo">
		<center>
			<img src="img/logo-front.png"/>
		</center>
		</div>
		<p>
	     <center>
				<span id="siteseal"><script async type="text/javascript" src="https://seal.godaddy.com/getSeal?sealID=MqdtosGXRlwCRWQLEWSMwFH66Ee5zzocEr3448gh0Ax4Skv64F6mygmqAimK"></script></span>
        </center>
		<center>
				<div class="txti">
					<p><b>We are presenting our new</b></p>
					<p><b>"BEST WEB"</b></p>
					<p><b>with new look and more informative by simply</b></p>
					<p><b>click the link below!</b></p>
				</div>
				<p class="txti lsmi nb"><a href="https://mobileweb.bcasekuritas.co.id">https://mobileweb.bcasekuritas.co.id</a></p>		
		</center>
	  </div>
	</div>
</body>
</html>
