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
	<title>Order List</title> 
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<? include "inc-common.php" ?>
	<style type="text/css">
	<!--
	<? include "css/icon.css" ?>
	//-->
	</style>
	<script type="text/javascript">
		function tr182600(id, bsFlag, status, cntLoop) {
			today = getToday("yyyymmdd");
			var color = [];
			color["B"] = [];
			color["S"] = [];
			$.ajax({
				type: "POST",
				url: "json/trproc.php",
				dataType : "json",
				data: sprintf("tr=182600&userId=%s&cliId=%s&pin=&code=&board=&bsFlag=%s&status=%s&from=%s&to=%s&cnt=40&keyDate=&keyOrderNo=999999999&price=",id, id, bsFlag, status, today, today),
				success: function(jsRet) {
					if (jsRet.status != 1) {
						alert(jsRet.mesg);
					} else {
						//alert( "jsRet=" + JSON.stringify(jsRet) );
						cnt = jsRet.out.length;
						iCnt = 0;
						rows = [];
						loopLimit = cnt;
						cntLoop > cnt ? loopLimit = cnt : loopLimit = cntLoop;
						for (i = 0 + (cntLoop-20); i < loopLimit; i ++) {
							id_v = "id" + iCnt;
							//
							type = jsRet.out[i]["type"];
							qty = Number(jsRet.out[i]["qty"] / 100);
							matchqty = Number(jsRet.out[i]["matchedQty"] / 100);
							balqty = Number(jsRet.out[i]["balQty"] / 100);

							if (type == "B") {
								color["B"].push(iCnt);
							} else if (type == "S") {
								color["S"].push(iCnt);
							}
							//

							cell_v = [
								"W", "A",
								jsRet.out[i]["time"],
								jsRet.out[i]["order"],
								jsRet.out[i]["marketOrderId"],
								jsRet.out[i]["code"],
								jsRet.out[i]["board"],
								type,
								jsRet.out[i]["price"],
								qty,
								matchqty,
								balqty,
								jsRet.out[i]["status"],
								];
							row = {id:id_v, cell:cell_v};
							rows.push(row);
							iCnt++;
						}
						args = {dataType:"json", rows:rows, page:1, total:cnt};
						$('#r-orderList').flexAddData(args);

						cssLoop = cnt;
						cntLoop > cnt ? cssLoop = (20+cnt) - cntLoop : cssLoop = cntLoop;

						for (i=0;i<cssLoop;i++) {
							var r = document.getElementById("r-orderList").getElementsByTagName('tr');
							var c = r[i].getElementsByTagName('td');
							jQuery( c[0] ).css("background-color", "#c4e2e2");
							jQuery( c[1] ).css("background-color", "#ffe1a0");
						}

						for (i=0;i<color["B"].length;i++) {
							var r = document.getElementById("r-orderList").getElementsByTagName('tr');
							var c = r[color["B"][i]].getElementsByTagName('td');
							jQuery( c[7] ).css("color", "#0000ff");
						}
						for (i=0;i<color["S"].length;i++) {
							var r = document.getElementById("r-orderList").getElementsByTagName('tr');
							var c = r[color["S"][i]].getElementsByTagName('td');
							jQuery( c[7] ).css("color", "#ff0000");
						}

					}
				},
				error: function(data, status, err) {
					console.log("error forward : "+data);
					//alert('error communition with server.');
				}
			});
		}

		$(document).ready(function() {
			jQuery('div').live('pagehide', function(event, ui){
				var page = jQuery(event.target);
				if(page.attr('data-cache') == 'never'){
					page.remove();
				};
			});
			args = {
				title : "OrderList",
				dataType : "json",
				height : "auto",
				colModel : [
					{display:"W", width:30, sortable:false, align:'center', process:withdrawFunc},
					{display:"A", width:30, sortable:false, align:'center', process:amendFunc},
					{display:"Time", width:50, sortable:false, align:'center'},
					{display:"Order#", width:50, sortable:false, align:'center'},
					{display:"Market#", width:70, sortable:false, align:'right'},
					{display:"Code", width:50, sortable:false, align:'center'},
					{display:"Board", width:50, sortable:false, align:'right'},
					{display:"Type", width:50, sortable:false, align:'right'},
					{display:"Price", width:50, sortable:false, align:'right'},
					{display:"Qty", width:50, sortable:false, align:'right'},
					{display:"Matched", width:60, sortable:false, align:'right'},
					{display:"Open", width:50, sortable:false, align:'right'},
					{display:"Status", width:50, sortable:false, align:'center'},
				],
				buttons : [
					{name:'Refresh', bclass:'refresh', onpress:qryTr182600},
					{separator: true},
					{name:'Next', bclass:'nextPage', onpress:qryNext},
					{separator: true}
				],
			};
			$('#r-orderList').flexigrid(args);
			qryTr182600();
		
			$("#withdrawOK").click(qryTr189300);
			$("#amendOK").click(qryTr189200);
		});

		//
		function withdrawFunc(celDiv, id) {
			$(celDiv).click(function() {
				row = parseInt(id.substr(3));
				orderNo = $("#row" + id + " td:nth-child(4) div").html();
				stock = $("#row" + id + " td:nth-child(6) div").html();
				price = $("#row" + id + " td:nth-child(9) div").html();
				vol = $("#row" + id + " td:nth-child(10) div").html();
				status = $("#row" + id + " td:nth-child(13) div").html();

				if (status == "W" || status == "A" || status == "M") {
					alert("Cannot Withdraw!");
					return;
				}

				$("#orderNoW").text(orderNo);
				$("#stockW").text(stock);
				$("#priceW").text(price);
				$("#volW").text(vol);

				$.mobile.changePage("#withdrawDialog", { role: "dialog" } );
			});
		}

		var real_prcA = 0;
		var real_volA = 0;
		var real_typeA = 'B';
		function amendFunc(celDiv, id) {
			$(celDiv).click(function() {
				row = parseInt(id.substr(3));
				orderNo = $("#row" + id + " td:nth-child(4) div").html();
				stock = $("#row" + id + " td:nth-child(6) div").html();
				type = $("#row" + id + " td:nth-child(8) div").html();
				price = $("#row" + id + " td:nth-child(9) div").html();
				vol = $("#row" + id + " td:nth-child(10) div").html();
				status = $("#row" + id + " td:nth-child(13) div").html();

				if (status == "W" || status == "A" || status == "M" || status == "N") {
					alert("Cannot Amend!");
					return;
				}
				$("#orderNoA").val(orderNo);
				$("#stockA").val(stock);
				$("#priceA").val(price);
				$("#volA").val(vol);
				//
				real_prcA = Number(price);
				real_volA = Number(vol);
				real_typeA = type;
				//

				tr100000(stock);
				tr800001("<?=$userId;?>", today, stock);
				$.mobile.changePage("#amendDialog", { role: "dialog" } );
			});
		}
		//

		function qryTr182600() {
			var userId = "<?=$userId;?>";
			$.mobile.loading('show');
			tr182600(userId, "0", "0", 20);
			$.mobile.loading('hide');
		}

		function qryNext() {
			var userId = "<?=$userId;?>";
			$.mobile.loading('show');
			tr182600(userId, "0", "0", 40);
			$.mobile.loading('hide');
		}
		
		//withdraw
		function qryTr189300() {
			if ( confirm("Are you sure?") ) {
				var userId = "<?=$userId?>";
				$.mobile.loading('show');
				tr189300(userId, $("#orderNoW").text(), $("#stockW").text(), $("#priceW").text(), $("#volW").text());
				$.mobile.loading('hide');
			}
		}

		//amend
		function qryTr189200() {
			ret = chkFraction();
			//ret = 1;
			if (ret != 1) {
				alert("Cannot Amend. Price is not valid!");
				return;
			}

			if (real_prcA == Number($("#priceA").val()) && Number($("#volA").val()) == real_volA) {
				alert("Cannot Amend. No data changed.");
				return;
			}

			if (real_prcA == Number($("#priceA").val()) && Number($("#volA").val()) > real_volA) {
				alert("Cannot Amend. Qty cannot be greater than before.");
				return;
			}

			//console.log("Balance: " + balA);
			if ( (Number($("#volA").val()) > balA) && real_typeA == 'S' ) {
				alert("Cannot Amend. Balance over [" + balA + "]");
				return;
			}

			cfrm = confirm("Are you sure?");

			if ( cfrm == true ) {
				var userId = "<?=$userId?>";
				$.mobile.loading('show');
				tr189200(userId, $("#orderNoA").val(), $("#stockA").val(), $("#priceA").val(), $("#volA").val());
				$.mobile.loading('hide');
			}
		}

		var uLimit = 0;
		var lLimit = 0;
		var Gprev = 0;

		function tr100000(code) {
			$.ajax({
				type: "POST",
				url: "json/trproc.php",
				dataType : "json",
				data: "tr=100000&code=" + code + "&board=RG",
				success: function(jsRet){
					if (jsRet.status != 1) {
						alert(jsRet.mesg);
					} else {
						// ulimit - llimit
						uLimit = Number(jsRet.out[0].ulimit);
						lLimit = Number(jsRet.out[0].llimit);
						Gprev = Number(jsRet.out[0].prev);
						//
					}
				},
				error: function(data, status, err) {
					console.log("error forward : "+data);
				}
			});
		}

		function chkFraction() {
			price = document.getElementById('priceA').value;

			interval = 0;

			//console.log("prevPrice: " + Gprev);

			if (Gprev < 500) {
				interval = 1;
			}
			if ((Gprev >= 500) && (Gprev < 5000)) {
				interval = 5;
			}
			if (Gprev >= 5000) {
				interval = 25;
			}

			// uLimit - lLimit
			fract = [];
			for (i=Number(lLimit); i<=Number(uLimit); i++) {
				fract.push(i.toString());
				i = (i + Number(interval)) - 1;
			}
			
			for (i=0; i<fract.length; i++) {
				if (price == fract[i]) {
					return 1;
				}
			}

			return 0;
		}

		//withdraw
		function tr189300(id, oldOrd, code, price, qty) {
			$.ajax({
				type: "POST",
				url: "json/trproc.php",
				dataType : "json",
				data: sprintf("tr=189300&userId=%s&dealerId=%s&pin=&oldOrdNo=%s&code=%s&board=RG&price=%s&lot=100&qty=%s", id, id, oldOrd, code, price, qty),
				success: function(jsRet){
					if (jsRet.status != 1) {
						alert(jsRet.mesg);
					} else {
						//
						//console.log(jsRet);
						//
						statusFld = jsRet.out[0].status;
						mesgFld = jsRet.out[0].mesg;

						if (statusFld == "0") {
							alert(mesgFld + ": " + statusFld);
							wBtn = 0;
							window.open("orderList.php", "_self");
						}
						else {
							window.open("orderList.php", "_self");
						}
						//
					}
				},
				error: function(data, status, err) {
					console.log("error forward : "+data);
				}
			});
		}
		
		//amend
		function tr189200(id, oldOrd, code, price, qty) {
			$.ajax({
				type: "POST",
				url: "json/trproc.php",
				dataType : "json",
				data: sprintf("tr=189200&userId=%s&dealerId=%s&pin=&oldOrdNo=%s&code=%s&board=RG&price=%s&lot=100&qty=%s", id, id, oldOrd, code, price, qty),
				success: function(jsRet){
					if (jsRet.status != 1) {
						alert(jsRet.mesg);
					} else {
						//
						console.log(jsRet);
						//
						statusFld = jsRet.out[0].status;
						mesgFld = jsRet.out[0].mesg;

						if (statusFld == "1" && mesgFld == "Order accepted.") {
							alert(mesgFld + ": " + statusFld);
							aBtn = 0;
							window.open("orderList.php", "_self");
						}
						else {
							window.open("orderList.php", "_self");
						}
						//
					}
				},
				error: function(data, status, err) {
					console.log("error forward : "+data);
				}
			});
		}

		var balA = 0;
		//tr800001
		function tr800001(id, today, stock) {
			$.ajax({
				type: "POST",
				url: "json/trproc.php",
				dataType : "json",
				data: "tr=800001&clientID=" + id + "&date=" + today,
				success: function(jsRet){
					if (jsRet.status != 1) {
						alert(jsRet.mesg);
					} else {
						rows = [];
						cnt = jsRet.out.length;
						for (i = 0; i < cnt; i ++) {
							console.log(jsRet.out[i]);
							id_v = i;
							//
							offer = jsRet.out[i].offer;
							offer == "" ? openSellA = 0 : openSellA = Number(offer);
							jsRet.out[i].lot = Number(jsRet.out[i].balance)/100 + openSellA;
							//
							code = jsRet.out[i].stkID;
							lot = jsRet.out[i].lot;
							shares = jsRet.out[i].balance;
							//
							if (code == stock) {
								balA = Number(lot);
							}
						}
					}
				}
			});
		}
		//
	</script>
</head> 
<body style="background:#ffffff;">
<div data-role="page" id="orderlist">
	<? include "page-header.php" ?>
	<!-- <div data-role="main" class="ui-content"> -->
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
<div id="withdrawDialog" data-role="page">
	<div data-role="header" data-theme="b">
		<h2>Withdraw</h2>
	</div>
	<div data-role="main" class="ui-content" style="background-color:#c4e2e2">
		<table border=0 width=100%>
			<tr>
				<td width="25%" style="color:#000000">Order No</td><td id="orderNoW" width="25%" style="color:#000000" align="right"></td>
			</tr>
			<tr>
				<td width="25%" style="color:#000000">Stock</td><td id="stockW" width="25%" style="color:#000000" align="right"></td>
			</tr>
			<tr>
				<td width="25%" style="color:#000000">Price</td><td id="priceW" width="25%" style="color:#000000" align="right"></td>
			</tr>
			<tr>
				<td width="25%" style="color:#000000">Volume</td><td id="volW" width="25%" style="color:#000000" align="right"></td>
			</tr>
			<tr>
				<td colspan="2"> 
					<a id="withdrawOK" href="#" data-role="button">OK</a>
				</td>
			</tr>
		</table>
	</div>
</div>
<div id="amendDialog" data-role="page">
	<div data-role="header" data-theme="b">
		<h2>Amend</h2>
	</div>
	<div data-role="main" class="ui-content" style="background-color:#ffe1a0">
		<table border=0 width=100%>
			<tr>
				<td width="25%" style="color:#000000">Order No</td><td width=25%><input id="orderNoA" type="text" disabled="disabled" /></td>
			</tr>
			<tr>
				<td width="25%" style="color:#000000">Stock</td><td width=25%><input id="stockA" type="text" disabled="disabled" /></td>
			</tr>
			<tr>
				<td width="25%" style="color:#000000">Price</td><td width=25%><input id="priceA" type="text" /></td>
			</tr>
			<tr>
				<td width="25%" style="color:#000000">Volume</td><td width=25%><input id="volA" type="text" /></td>
			</tr>
			<tr>
				<td colspan="2"> 
					<a id="amendOK" href="#" data-role="button">OK</a>
				</td>
			</tr>
		</table>
	</div>
</div>

</body>
</html>
