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
	echo '<script type="text/javascript">setTimeout(function() { window.location.href = "https://mobile.bcasekuritas.co.id/tlogout.php"; }, 15 * 60000);</script>';
$userId = $_SESSION["userId"];
$clientId = $_SESSION["loginId"];

$_SESSION["pin"] = '';
$_SESSION["pinState"] = 0;

if($_SESSION["disclaim"] == "N" || $_SESSION["disclaim"] == "null" || $_SESSION["disclaim"] == "n"){
    header("Location: index.php");
    exit(0);
}

?>
<html>
<head>
	<title>Input Pin</title> 
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<? include "inc-common.php" ?>
	<script type="text/javascript">
    var userId = "<?=$userId?>";
    var clientId = "<?=$clientId?>";
    var now = new Date();
    var nowStr = now.format("yyyymmdd");
    var retTr800000 = 0;    // if get return, will be 1
    var retTr800001 = 0;    // if get return, will be 1
    var retTr182600 = 0;    // if get return, will be 1

    // Initialize
    $(document).ready(function() {
        tr800000(userId, clientId, nowStr);
		tr800001(userId, nowStr);
        //tr182600(userId,"0","0");
    });
		//

    function checkRetTr() {
    // return 1 : if get all returns
        //if (retTr800000 == 1) { 
        if (retTr800000 == 1 && retTr800001 == 1) { 
            // && retTr800001 == 1 && retTr182600 == 1) {
            $('#okBtn').prop('disabled', false);
            return 1;
        } else
            return 0;
    }

    $(function() {
        $('form').submit(function() {
            $id = $('#id').val();
            $pin = $('#pin').val();
            ///*
                if ( idFill() && pinFill() ) {
                    //alert('START SERVICE');
                    tr000203($pin);
                    //alert('AFTER SERVICE');
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
	
		function tr000203(pin) {
			var id = "<?=$userId?>";
			var clientId = "<?=$clientId?>";
			$.ajax({
				type: "POST",
				url: "pin-do_encrypt.php",
				dataType : "json",
				data: "tr=000203&id=" + id + "&pin=" + pin + "&clientId=" + clientId,
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
                            /*
							var now = new Date();
							nowStr = now.format("yyyymmdd");
							tr800000(id, clientId, nowStr);
							tr800001(id, nowStr);
							tr182600(id,"0","0");
                            */
							window.open("index.php?uFlag=1", "_self");
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
		function tr182600(id, bsFlag, status){
			today = getToday("yyyymmdd");
			$.ajax({
				type: "POST",
				url: "json/trproc.php",
				dataType: "json",
				data: sprintf("tr=182600&userId=%s&cliId=%s&pin=&code=&board=&bsFlag=%s&status=%s&from=&to=&cnt=40&keyDate=&keyOrderNo=999999999&price=",id, id, bsFlag, status),
				success: function(jsRet){
					if(jsRet.status !=1){
						alert(jsRet.mesg);
					}else{
                        retTr182600 = 1;
                        checkRetTr();
						console.log('182600 Successed!!!');
					}
				}
			});
		}	

		function tr800000(user, client, date){
			$.ajax({
				type: "POST",
				url: "json/trproc.php",
				dataType: "json",
				data: "tr=800000&userID="+user+"&clientID="+client+"&date="+date,
				success: function(jsRet){
					if(jsRet.status !=1){
						alert(jsRet.mesg);
					}else{
                        retTr800000 = 1;
                        checkRetTr();
						console.log('800000 Successed!!!');
					}
				}
			});
		
		}
	
		function tr800001(id, today){
			$.ajax({
				type: "POST",
				url: "json/trproc.php",
				dataType: "json",
				data: "tr=800001&clientID=" + id + "&date=" + today,
				success: function(jsRet){
					if(jsRet.status !=1){
						alert(jsRet.mesg);
					}else{
                        retTr800001 = 1;
                        checkRetTr();
						console.log('800001 Successed!!!');
					}
				}
			});
		
		}


		//
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
					<div class="card-body">
						<form>
							<div class="form-group">
								<label for="ClientID">Client ID</label>
								<input type="text" class="form-control" id="id" name="id" value="<?=$userId?>" readonly>
							</div>
							<div class="form-group">
								<label for="InputPIN">Input PIN</label>
								<input type="password" class="form-control" id="pin" name="pin" placeholder="Input Your PIN">
							</div>
							<button type="submit" class="btn btn-info" id="okBtn" disabled="true">Confirm</button>
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
