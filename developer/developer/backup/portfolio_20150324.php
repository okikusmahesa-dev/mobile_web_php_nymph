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
$pinState = 0;
$pinLogin = 0;
if ($_SESSION["pinState"] != 1) {
	echo "<script>alert('Please Input PIN first!');</script>";
	header("refresh:1; url=inPin.php");
	exit(0);
} else {
	$pinState = $_SESSION["pinState"];
	$pinLogin = $_SESSION["pin"];
}
?>
<html>
<head> 
	<title>Portfolio</title> 
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<? include "inc-common.php" ?>
	<style type="text/css">
	<!--
	<? include "css/icon.css" ?>
	//-->
	</style>
	<script type="text/javascript" src="js/stockmap.js"></script>
	<script type="text/javascript">
		//global var
		var totLq = 0;
		var ordList = 0;
		var stockMap = [];
				
		function vol2lot(qty) {
			return qty / 100;
		}
		
		//initPage
		$(document).ready(function() {			
			args = {
				//title : "Portfolio",
				dataType : "json",
				height : "auto",
				colModel : [
					{display:"Sell", width:30, sortable:false, align:'center'},
					{display:"Code", width:60, sortable:false, align:'center'},
					{display:"Lot", width:60, sortable:false, align:'right'},
					{display:"Shares", width:100, sortable:false, align:'right'},
					{display:"Buy Prc", width:100, sortable:false, align:'right'},
					{display:"Last Prc", width:100, sortable:false, align:'right'},
					{display:"Buy Val", width:100, sortable:false, align:'right'},
					{display:"Market Val", width:100, sortable:false, align:'right'},
					{display:"Hair Cut", width:60, sortable:false, align:'right'},
					{display:"Stock Val", width:100, sortable:false, align:'right'},
					{display:"Unrealized", width:100, sortable:false, align:'right'},
					{display:"(%)", width:40, sortable:false, align:'right'},
					{display:"Done (B)", width:60, sortable:false, align:'right'},
					{display:"Done (S)", width:60, sortable:false, align:'right'},
					{display:"Open (B)", width:60, sortable:false, align:'right'},
					{display:"Open (S)", width:60, sortable:false, align:'right'}
				]
			};
			$('#r-portfolio').flexigrid(args);

			//
			initStock();
			//

			//r-headportfolio
			hport = {
				title: "Portfolio",
				dataType: "json",
				height: "auto",
				colModel: [
					{display: "RDI", width:100, sortable: false, align: "right"},
					{display: "AR/AP", width:100, sortable: false, align: "right"},
					{display: "Open Buy", width:100, sortable: false, align: "right"},
					{display: "Cash", width:100, sortable: false, align: "right"},
					{display: "Trd Limit", width:100, sortable: false, align: "right"},
					{display: "Cur Ratio", width:100, sortable: false, align: "right"},
					{display: "Pot Ratio", width:100, sortable: false, align: "right"}
				],
				buttons : [
					{name:'Refresh', bclass:'refresh', onpress:refreshAll},
					{separator: true}
				]
			};
			$('#r-headportfolio').flexigrid(hport);
			//

			//
			rows = [];
			id_v = 0;
			cell_v = [
				"", "", "", "", "", "", "", "", "", ""
				];
			row = {id:id_v, cell:cell_v};
			rows.push(row);

			args = {dataType: "json", rows: rows, page: 1, total: 1};
			$('#r-headportfolio').flexAddData(args);

			//
			
			//
			if (<?=$pinState;?> == 1) {
				refreshAll(); 
				tr800300(<?=$userId;?>, <?=$pinLogin;?>);
			}
			//
		});

		function tr800001(id, today) {
			$.ajax({
				type: "POST",
				url: "json/trproc.php",
				dataType : "json",
				data: "tr=800001&clientID=" + id + "&date=" + today,
				success: function(jsRet){
					if (jsRet.status != 1) {
						alert(jsRet.mesg);
					} else {
						//
						rows = [];
						cnt = jsRet.out.length;
						var ops_exist = 0;
						//
						for (i = 0; i < cnt; i ++) {
							id_v = i;
							//
							jsRet.out[i].lqVal = Number(jsRet.out[i].mktVal) * (1.0 - Number(jsRet.out[i].hairCut));
							jsRet.out[i].profitVal = Number(jsRet.out[i].mktVal) - Number(jsRet.out[i].stkVal);
							$check = Number(jsRet.out[i].stkVal) * 100.0;
							if ($check < 1) {
								jsRet.out[i].profitRate = "0.00";
							} else {
								jsRet.out[i].profitRate = jsRet.out[i].profitVal / Number(jsRet.out[i].stkVal) * 100.0;
							}
							jsRet.out[i].lot = vol2lot(jsRet.out[i].balance);
							jsRet.out[i].hairCut = Number(jsRet.out[i].hairCut) * 100.0;
							//
							code = jsRet.out[i].stkID;
							lot = jsRet.out[i].lot;
							shares = jsRet.out[i].balance;
							bprc = sprintf("%.2f", Number(jsRet.out[i].avgPrice));
							lprc = jsRet.out[i].mktPrice;
							bval = sprintf("%.2f", Number(jsRet.out[i].stkVal));
							mval = jsRet.out[i].mktVal;
							hc = jsRet.out[i].hairCut;
							sval = sprintf("%.2f", Number(jsRet.out[i].lqVal));
							unrl = parseFloat(jsRet.out[i].profitVal).toFixed(0);
							prcnt = parseFloat(jsRet.out[i].profitRate).toFixed(2);
							dnb = sprintf("%.0f", Number(jsRet.out[i].buy));
							dns = sprintf("%.0f", Number(jsRet.out[i].sell));
							opb = sprintf("%.0f", Number(jsRet.out[i].bid));
							ops = sprintf("%.0f", Number(jsRet.out[i].offer));
							//
								if (ops != "0") {
									ops_exist = Number(ops) * 100 * Number(lprc) * (1.0 - Number(hc/100));
								} else {
									ops_exist = 0;
								}
								totLq = totLq + Number(sval) + ops_exist;
							//
							cell_v = [
								"S",
								code,
								lot,
								shares,
								bprc,
								lprc,
								bval,
								mval,
								hc,
								sval,
								unrl,
								prcnt,
								dnb,
								dns,
								opb,
								ops
								];
							row = {id:id_v, cell:cell_v};
							rows.push(row);
						}
						args = {dataType: "json", rows: rows, page: 1, total: cnt};
						$('#r-portfolio').flexAddData(args);
					}
				}
			});
		}

		//
		function refreshAll() {
			//totLq = 0;
			//ordList = 0;
			qryTr182600();
			qryTr800001();
			setTimeout(qryTr800000, 10000);

		}
		//

		function qryTr182600() {
			var userId = "<?=$userId;?>";
			var now = new Date();
			nowStr = now.format("yyyymmdd");
			tr182600(userId, nowStr);
		}
		
		function qryTr800001() {
			var userId = "<?=$userId;?>";
			var now = new Date();
			nowStr = now.format("yyyymmdd");
			tr800001(userId, nowStr);
		}

		//
		function qryTr800000() {
			var userId = "<?=$userId;?>";
			var clientId = "<?=$clientId?>";
			var now = new Date();
			nowStr = now.format("yyyymmdd");
			tr800000(userId, clientId, nowStr);
		}
		//

		//
		function tr800000(user, client, date) {
			$.ajax({
				type: "POST",
				url: "json/trproc.php",
				dataType: "json",
				data: "tr=800000&userID=" + user + "&clientID=" + client + "&date=" + date,
				success: function(jsRet) {
					if (jsRet.status != 1) {
						alert(jsRet.mesg);
					} else {
						console.log(jsRet.out[0]);
						rows = [];
						// different field get from SAS (08:55-16:45): todo?
						rdi = sprintf("%.0f", Number(jsRet.out[0].rdn));
						arap = sprintf("%.0f", Number(jsRet.out[0].cashBalance) - Number(rdi));
						openbuy = sprintf("%.0f", Number(jsRet.out[0].bid));
						cash = sprintf("%d", Number(jsRet.out[0].cashBalance) - Number(openbuy));
						debt = Number(jsRet.out[0].cashBalance) - Number(openbuy);
						asset = totLq + ordList;
						trdlimit = sprintf("%.0f", (debt * 2.5) + (asset * 1.5));
						potratio = sprintf("%.2f", Number(-(debt / asset) * 100));
						debt >= 0 ? potratio = "0.00" : potratio;
						//
						console.log(Number(jsRet.out[0].cashBalance) + "|" + openbuy + "|" + totLq + "|" + ordList);
						console.log("potratio=> "+potratio);
						//
						curratio = jsRet.out[0].curRatio;
						curratio === undefined ? curratio = "0.00" : curratio = sprintf("%.2f", Number(curratio));
						console.log(curratio);
						//
						cell_v = [
							rdi,
							arap,
							openbuy,
							cash,
							trdlimit,
							curratio + "%",
							potratio + "%",
						];
						row = {id: 0, cell: cell_v};
						rows.push(row);
					}
					args = {dataType: "json", rows: rows, page: 1, total: 1};
					$('#r-headportfolio').flexAddData(args);
				},
				error: function(data, status, err) {
					console.log("error forward : "+data);
				}
			});
		}
		//
		
		//tr128600
		function tr182600(id, date) {
			$.ajax({
				type: "POST",
				url: "json/trproc.php",
				dataType: "json",
				data: sprintf("tr=182600&userId=%s&cliId=%s&pin=&code=&board=&bsFlag=%s&status=%s&from=%s&to=%s&cnt=500&keyDate=&keyOrderNo=999999999&price=",id, id, 0, status, date, date),
				success: function(jsRet) {
					if (jsRet.status != 1) {
						alert(jsRet.mesg);
					} else {
						console.log(jsRet.out);
						console.log(jsRet.out2);
						//
						cnt = jsRet.out.length;

						for (i = 0; i < cnt; i++) {
							if (jsRet.out[i].status == "O" && jsRet.out[i].type == "B") {
								price = jsRet.out[i].price;
								qty = jsRet.out[i].qty;
								code = jsRet.out[i].code;
								//
							}
							//console.log(ordList);
						}
						//
					}
				},
				error: function(data, status, err) {
					console.log("error : "+data);
				}
			});
		}
		//

		function tr800300(id, pin) {
			$.ajax({
				type: "POST",
				url: "json/trproc.php",
				dataType : "json",
				data: "tr=800300&userId=" + id + "&pin=" + pin,
				success: function(jsRet){
					if (jsRet.status != 1) {
						alert(jsRet.mesg);
					} else {
						//
						ClientNo = jsRet.out[0].ClientNo;
						Name = jsRet.out[0].Name;
						$('#clientno').text(ClientNo);
						$('#name').text(Name);
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
<body style="backgound:#ffffff;">
<div data-role="page" id="portfolio" data-cache="never">
	<? include "page-header.php" ?>
	<div data-role="main" class="ui-content">
		<table data-role="table" class="ui-responsive ui-shadow" id="clientInfo">
			<thead>
			<tr>
				<th id="name"></th>
			</tr>
			</thead>
			<tbody>
				<td id="clientno"></td>
			</tbody>
		</table>
	</div>
	<div>
		<table id="r-headportfolio" class="flexigrid">
		<thead></thead>
		<tbody></tbody>
		</table>
	</div>
	<div>
		<table id="r-portfolio" class="flexigrid">
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
