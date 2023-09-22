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
$pinState = 0;
$pinLogin = 0;
	echo '<script type="text/javascript">setTimeout(function() { window.location.href = "https://mobile.bcasekuritas.co.id/tlogout.php"; }, 15 * 60000);</script>';
if ($_SESSION["pinState"] != 1) {
	//echo "<script>alert('Please Input PIN first!');</script>";
	echo "<script>alert('Please Input PIN first.');</script>";
	header("refresh:1; url=inPin.php");
	exit(0);
} else {
	$pinState = $_SESSION["pinState"];
	$pinLogin = $_SESSION["pin"];
	//$_SESSION["url"]="https://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];

}
?>
<html>
<head> 
	<title>Trade List</title> 
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<? include "inc-common.php";?>
	<style type="text/css">
	<!--
	<? include "css/icon.css" ?>
	//-->
	</style>
	<script type="text/javascript">
		function tr182800(id, bsFlag) {
			today = getToday("yyyymmdd");
			var color = [];
			color["B"] = [];
			color["S"] = [];
			$.ajax({
				type: "POST",
				url: "json/trproc.php",
				dataType : "json",
				data: sprintf("tr=182800&userId=%s&cliId=%s&pin=&code=&board=&bsFlag=%s&from=%s&to=%s&cnt=20&keyDate=&key=999999999&price=",id, id, bsFlag, today, today),
				success: function(jsRet) {
					if (jsRet.status != 1) {
						alert(jsRet.mesg);
					} else {
						//alert( "jsRet=" + JSON.stringify(jsRet) );
						cnt = jsRet.out1.length;
						iCnt = 0;
						rows = [];
						//
						for (i = 0; i < cnt; i ++) {
							id_v = "id" + i;
							//
							buySell = jsRet.out1[i]["buySell"];
							price = Number(jsRet.out1[i]["mtcPrice"]);
							qty = Number(jsRet.out1[i]["mtcQty"] / 100);

							if (buySell == "B") {
								color["B"].push(i);
							} else if (buySell == "S") {
								color["S"].push(i);
							}
							//
							cell_v = [
								jsRet.out1[i]["time"],
								jsRet.out1[i]["trade"],
								jsRet.out1[i]["order"],
								jsRet.out1[i]["code"],
								jsRet.out1[i]["board"],
								buySell,
								addCommas(price),
								qty,
								jsRet.out1[i]["mtcQty"],
								];
							row = {id:id_v, cell:cell_v};
							rows.push(row);
						}
						args = {dataType:"json", rows:rows, page:1, total:cnt};
						$('#r-orderList').flexAddData(args);

						for (i=0;i<color["B"].length;i++) {
							var r = document.getElementById("r-orderList").getElementsByTagName('tr');
							var c = r[color["B"][i]].getElementsByTagName('td');
							jQuery( c[5] ).css("color", "#0000ff");
						}
						for (i=0;i<color["S"].length;i++) {
							var r = document.getElementById("r-orderList").getElementsByTagName('tr');
							var c = r[color["S"][i]].getElementsByTagName('td');
							jQuery( c[5] ).css("color", "#ff0000");
						}
					}
				},
				error: function(data, status, err) {
					console.log("error forward : "+data);
					alert('error communition with server.');
				}
			});
			$.mobile.loading('hide');
		}

		$(document).ready(function() {
			jQuery('div').live('pagehide', function(event, ui){
				var page = jQuery(event.target);
				if(page.attr('data-cache') == 'never'){
					page.remove();
				};
			});
			args = {
				title : "TradeList",
				dataType : "json",
				height : "auto",
				colModel : [
					{display:"Time", width:50, sortable:false, align:'center'},
					{display:"Trade#", width:70, sortable:false, align:'right'},
					{display:"Market#", width:70, sortable:false, align:'right'},
					{display:"Code", width:50, sortable:false, align:'center'},
					{display:"Board", width:50, sortable:false, align:'right'},
					{display:"Type", width:50, sortable:false, align:'right'},
					{display:"Price", width:50, sortable:false, align:'right'},
					{display:"Qty", width:50, sortable:false, align:'right'},
				],
				buttons : [
					{name:'Refresh', bclass:'refresh', onpress:qryTr182800},
					{separator: true}
				]
			};
			$('#r-orderList').flexigrid(args);
			qryTr182800();
		});

		function qryTr182800() {
			$.mobile.loading('show');
			tr182800('<?=$userId;?>', "0");
			console.log("refresh");
		}
	</script>
</head> 
<body style="backgound:#ffffff;">
<div data-role="page" id="home" data-cache="never">
	<? include "page-header.php" ?>
	<div>
	<table id="r-orderList" class="flexigrid">
		<thead>
		</thead>
		<tbody>
		</tbody>
	</table>
	</div>
	<? include "page-footer.php" ?>
</div>
</body>
</html>
