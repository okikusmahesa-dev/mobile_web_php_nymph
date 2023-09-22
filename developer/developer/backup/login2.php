<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");
?>
<html>
<head> 
	<title>NmHTS</title> 
	<? include "inc-common.php" ?>
	<style type="text/css">
	<!--
	<? include "css/icon.css" ?>
	//-->
	</style>
	<script type="text/javascript">
		$(document).ready(function() {
			jQuery('div').live('pagehide', function(event, ui){
				var page = jQuery(event.target);
				if(page.attr('data-cache') == 'never'){
					page.remove();
				};
			});
			$("#passwd").keyup(function(event) {
				if (event.keyCode == 13) {
					qryTr000100();
				}
			});
			$("#okBtn").click(qryTr000100);
		});
		
		
		function tr000100(user, pass) {
			alert("tr000102");
			var user = String(user);
			var pass = String(pass);
			var pw2 = "12345678901234567890";
			$.ajax({
				type: "POST",
				url: "json/trproc.php",
				dataType : "json",
				data: "tr=000102&id=" + user + "&pw=" + pass + "&pw2=" + pw2,
				success: function(jsRet){
					alert(id + "|" + pw + "|" + pw2);
					if (jsRet.status != 1) {
						alert(jsRet.mesg);
					} else {
						//
						statusFld = jsRet.out[0].status;
						mesgFld = jsRet.out[0].mesg;
						//
						if (statusFld == "0") {
							alert(mesgFld + ": " + statusFld);
						}
						console.log(jsRet.out);
						//
					}
					//alert( "jsRet=" + JSON.stringify(jsRet) );
				},
				error: function(data, status, err) {
					console.log("error forward : "+data);
					alert('error communition with server.');
				}
			});
		}

		function qryTr000100() {
			//alert("QRYTR000100");
			var user = $("#userid").val();
			var pass = $("#passwd").val();
			user = user.toLowerCase();
			pass = pass.toLowerCase();
			//alert(user + "|" + pass);
			$("#userid").val(user);
			$("#passwd").val(pass);
			tr000100(user, pass);
		}

	</script>
</head> 
<body>
	<div data-role="page" id="login" data-cache="never">
	  <? include "page-header.php" ?>

	  <div data-role="main" class="ui-content">
		<form method="post" action="index.php">
		  <p>
			<label for="userid">UserID:</label> <br>
			<input type="text" name="userid" id="userid">
		  </p>
		  <p>
			<label for="passwd">Password:</label> <br>
			<input type="password" name="passwd" id="passwd">
		  </p>
		  <a id="okBtn" href="#" data-role="button" data-icon="search" style="float:right">OK</a><br/>
		  <!-- <input type="submit" data-inline="true" value="Submit"> -->
		</form>
		<table border="1" class="grid-def" width="100%">
		<thead></thead>
		<tbody>
		<tr>
			<td width="25%" class="r-cell">Username</td><td id="usertb" width="25%"></td>
			<td width="25%" class="r-cell">Password</td><td id="passtb" width="25%"></td>
		</tr>
		</tbody>
		</table>
	  </div>
	</div>
</body>
</html>