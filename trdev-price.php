<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");

session_start();
//print_r($_SESSION);
/*
if ($_SESSION["isLogin"] != 1) {
	echo "Please login...";
	header("refresh:3; url=login.php");
	exit(0);
}
*/
	#echo '<script type="text/javascript">setTimeout(function() { window.location.href = "https://mobile.bcasekuritas.co.id/tlogout.php"; }, 15 * 60000);</script>';
//$userId = $_SESSION["userId"];
//$clientId = $_SESSION["loginId"];
?>
<html>
<head>
	<title>tr dev</title> 
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="js/css/flexigrid.pack.css" />
    <link rel="stylesheet" href="css/jquery.mobile-1.2.0.min.css" />
    <script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
    <script src="js/jquery-ui.js"></script>
    <script type="text/javascript" src="js/flexigrid.pack.js"></script>
    <script type="text/javascript" src="js/jquery.mobile-1.2.0.min.js"></script>
    <script src="js/common.js?20170921a"></script>
    <script src="js/sprintf.js"></script>
    <script src="js/date.format.js"></script>
	<style type="text/css">
	<!--
	//-->
	</style>
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
        //tr800001(userId, nowStr);
        //tr182600(userId,"0","0");
    });
		//

    $(function() {
        $('form').submit(function() {
            var tr = $('#tr').val();
            var in1 = $('#in1').val();
            var in2 = $('#in2').val();
            var in3 = $('#in3').val();
            var in4 = $('#in4').val();
            var in5 = $('#in5').val();
            var in6 = $('#in6').val();

            qrytr(tr, in1, in2, in3, in4, in5, in6);
            function idFill() {

                if($('#tr').val().length == 0) {
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

    function qrytr(tr, in1, in2, in3, in4, in5, in6) {
	    $.ajax({
	        type: "POST",
			url: "trproc.php",
			dataType : "json",
			data: sprintf("tr=%s&in1=" + id + "&pin=" + pin + "&clientId=" + clientId,
				data: sprintf("tr=182600&userId=%s&cliId=%s&pin=&code=&board=&bsFlag=%s&status=%s&from=&to=&cnt=40&keyDate=&keyOrderNo=999999999&price=",id, id, bsFlag, status),
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
					} else {
                            /*
							var now = new Date();
							nowStr = now.format("yyyymmdd");
							tr800000(id, clientId, nowStr);
							tr800001(id, nowStr);
							tr182600(id,"0","0");
                            */
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
<div data-role="page" id="inPin">
	<div data-role="header" data-theme="b">
		<h1>TR Test</h1>
	</div>
<form method="post">

	<div data-role="main" class="ui-content">
		<div data-role="fieldcontain">
			<label for="id">TR Code</label>
			<input type="text" name="tr" id="tr" data-mini="true" value="">
		</div>
		<div data-role="fieldcontain">
        <table border=0>
            <tr><td><label>in1</label></td>
			    <td><input type="text" name="in1" id="in1" data-mini="true" value="">
                </td></tr>
            <tr><td><label>in2</label></td>
			    <td><input type="text" name="in2" id="in2" data-mini="true" value="">
                </td></tr>
            <tr><td><label>in3</label></td>
			    <td><input type="text" name="in3" id="in3" data-mini="true" value="">
                </td></tr>
            <tr><td><label>in4</label></td>
			    <td><input type="text" name="in4" id="in4" data-mini="true" value="">
                </td></tr>
            <tr><td><label>in5</label></td>
			    <td><input type="text" name="in5" id="in5" data-mini="true" value="">
                </td></tr>
            <tr><td><label>in6</label></td>
			    <td><input type="text" name="in6" id="in6" data-mini="true" value="">
                </td></tr>
        </table>
		</div>
		<button id="okBtn" type="submit" data-inline="true" data-theme="b" data-mini="true" disabled="disabled"> Ok </button>
		<a href="index.php?uFlag=1" data-inline="true" data-role="button" data-theme="b" data-mini="true">Cancel</a>
	</div>

</form>
	<div data-role="footer" data-theme="b">
		<h1>&nbsp</h1>
	</div>
</div>
<pre>
<?
?>
</pre>
</body>
</html>
