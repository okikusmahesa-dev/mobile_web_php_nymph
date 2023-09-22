<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");

session_start();
if (!isset($_SESSION["isLogin"]) || $_SESSION["isLogin"] != 1) {
	echo "Please login...";
	header("refresh:3; url=login.php");
	exit(0);
}
	echo '<script type="text/javascript">setTimeout(function() { window.location.href = "https://mobile.bcasekuritas.co.id/tlogout.php"; }, 15 * 60000);</script>';
	//$_SESSION["url"]="https://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];

?>
<html>
<head> 
	<title>Market Summary</title> 
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<? include "inc-common.php" ?>
	<style type="text/css">
	<? include "css/icon.css" ?>
	<? include "css/marketSummary.css" ?>
	</style>
	<script type="text/javascript">
		$(document).ready(function() {
			jQuery('div').live('pagehide', function(event, ui){
				var page = jQuery(event.target);
				if(page.attr('data-cache') == 'never'){
					page.remove();
				};
			});

			args = {
				title : "Foreign Transaction Summary",
				dataType : "json",
				height : "auto",
				onSuccess : onSuccess,
				colModel : [
					{display:"Code", width:50, sortable:false, align:'center'},
					{display:"Value", width:50, sortable:false, align:'right'},
					{display:"F.Val", width:50, sortable:false, align:'right'},
					{display:"F.NetVal", width:70, sortable:false, align:'right'},
				],
				buttons : [
					{name:'Refresh', bclass:'refresh', onpress:onRefresh},
					{separator: true}
				]
			};
			$('#r-foreign').flexigrid(args);
			qryTr190000();
			qryTr100500();
		});

		function onRefresh() {
			qryTr190000();
			qryTr100500();
		}

		function qryTr190000() {
			tr190000();
		}

		function qryTr100500() {
			tr100500();
		}

		function tr190000() {
			$.ajax({
				type: "POST",
				url: "json/trproc.php",
				dataType : "json",
				data: "tr=190000&index=COMPOSITE",
				success: function(jsRet){
					if (jsRet.status != 1) {
						alert(jsRet.mesg);
					} else {
						//console.log(jsRet);
						rows = [];
						cnt = jsRet.out.length;
						for (i = 0; i < cnt; i++) {
							index = jsRet.out[i].index;
							chg = jsRet.out[i].chg;
							chgR = jsRet.out[i].chgR;
							prev = jsRet.out[i].prevPrice;
							open = jsRet.out[i].open;
							high = jsRet.out[i].high;
							low = jsRet.out[i].low;
							up = jsRet.out[i].up;
							down = jsRet.out[i].down;
							unchange = jsRet.out[i].unchg;
							notrade = jsRet.out[i].noTrd;

							chgP = jsRet.out[i].chgP;

							//index = miniCurr(index);
							//chg = miniCurr(chg);
							//chgR = miniCurr(chgR);
							//prev = miniCurr(prev);
							//open = miniCurr(open);
							//high = miniCurr(high);
							//low = miniCurr(low);
							//up = miniCurr(up);
							//down = miniCurr(down);
							//unchange = miniCurr(unchange);
							//notrade = miniCurr(notrade);

							index = sprintf("%.2f", Number(index));
							prev = sprintf("%.2f", Number(prev));
							open = sprintf("%.2f", Number(open));
							high = sprintf("%.2f", Number(high));
							low = sprintf("%.2f", Number(low));

							//
							if (chgP == "-") {
								$("#last").css("color", "red");
								$("#change").css("color", "red");
								$("#changeR").css("color", "red");
								$("#open").css("color", "red");
								$("#high").css("color", "red");
								$("#low").css("color", "red");
							} else {
								$("#last").css("color", "blue");
								$("#change").css("color", "blue");
								$("#changeR").css("color", "blue");
								$("#open").css("color", "blue");
								$("#high").css("color", "blue");
								$("#low").css("color", "blue");
							}

							$("#up").css("color", "green");
							$("#down").css("color", "green");
							$("#unchange").css("color", "green");
							$("#notrade").css("color", "green");
							//

							index = addCommas(index);
							prev = addCommas(prev);
							open = addCommas(open);
							high = addCommas(high);
							low = addCommas(low);

							$("#last").text(index);
							$("#change").text(chg);
							$("#changeR").text(chgR);
							$("#prev").text(prev);
							$("#open").text(open);
							$("#high").text(high);
							$("#low").text(high);
							$("#up").text(up);
							$("#down").text(down);
							$("#unchange").text(unchange);
							$("#notrade").text(notrade);
						}
					}
				},
				error: function(data, status, err) {
					console.log("error forward : "+data);
					alert('error communition with server.');
				}
			});
		}

		function tr100500() {
			$.ajax({
				type: "POST",
				url: "json/trproc.php",
				dataType : "json",
				data: "tr=100500&unknown=unknown",
				success: function(jsRet){
					if (jsRet.status != 1) {
						alert(jsRet.mesg);
					} else {
						//console.log(jsRet);
						rows = [];
						cnt = jsRet.out.length;
						fBuy = 0;
						fSell = 0;
						for (i = 0; i < cnt; i++) {
							fBuyVal = Number(jsRet.out[i].frgBuyVal);
							fSellVal = Number(jsRet.out[i].frgSellVal);
							fBuy = fBuy + fBuyVal;
							fSell = fSell + fSellVal;
						}
						ng = jsRet.out[0].totVal;
						rg = jsRet.out[1].totVal;
						tn = jsRet.out[2].totVal;
						total = Number(ng) + Number(rg) + Number(tn);
						net = fBuy - fSell;

						/*
						total = addCommas(total);
						ng = addCommas(ng);
						rg = addCommas(rg);
						tn = addCommas(tn);
						fBuy = addCommas(fBuy);
						fSell = addCommas(fSell);
						net = addCommas(net);
						*/

						//
						$("#total").css("color","blue");
						$("#ng").css("color","blue");
						$("#rg").css("color","blue");
						$("#tn").css("color","blue");

						$("#fbuy").css("color","blue");
						$("#fsell").css("color","red");

						if (Number(net) > 0) {
							$("#net").css("color","blue");
						} else if (Number(net) < 0) {
							$("#net").css("color","red");
						}
						//

						//
						total = miniCurr(total);
						ng = miniCurr(ng);
						rg = miniCurr(rg);
						tn = miniCurr(tn);
						fBuy = miniCurr(fBuy);
						fSell = miniCurr(fSell);
						net = miniCurr(net);
						//

						$("#ng").text(ng);
						$("#rg").text(rg);
						$("#tn").text(tn);
						$("#total").text(total);
						$("#fbuy").text(fBuy);
						$("#fsell").text(fSell);
						$("#net").text(net);
					}
				},
				error: function(data, status, err) {
					console.log("error forward : "+data);
					alert('error communition with server.');
				}
			});
		}

		function onSuccess() {
		/*
			$('#r-foreign tr').each( function(){ 
			//
				// val
				var cell = $('td:nth-child(2) > div', this); 
				var val = cell.text();
				console.log(cell);
				console.log(val);
				if (Number(val) != 0) {
					var rgx = val.match(/(.*) (T|M|B)/);
					if (Number(rgx[1]) > 0) {
						cell.css("color", "blue");
					} else if (Number(rgx[1]) < 0) {
						cell.css("color", "red");
					}
				}
				// fval
				var cell = $('td:nth-child(3) > div', this); 
				var val = cell.text();
				if (Number(val) != 0) {
					var rgx = val.match(/(.*) (T|M|B)/);
					if (Number(rgx[1]) > 0) {
						cell.css("color", "blue");
					} else if (Number(rgx[1]) < 0) {
						cell.css("color", "red");
					}
				}
			//
			}); 
		*/
		}

		</script>
</head> 
<body style="backgound:#ffffff;">
<div data-role="page" id="home" data-cache="never">
	<? include "page-header.php" ?>
	<div>
	<table border="0" width="100%">
	<td width="10%">
	<h2 style="margin-top: 5px;margin-bottom: 5px;">Market Summary</h2>
	</td>
	<td align="right" width="25%">
	    <a id="okBtn" href="#" data-role="button" data-icon="refresh" data-mini="true" style="float:right;" onclick = "onRefresh()">Refresh</a><br/>
	</td>
	</table>
	<table border="1" width="100%" class="grid-def" >
		<thead></thead>
		<tbody>
		<tr>
			<td class="r-cell">IDX</td><td class="r-qty" id="last"></td><td class="r-qty" id="change"></td><td class="r-qty" id="changeR"></td>
		</tr>
		<tr>
			<td class="r-cell">Prev</td><td class="r-qty" id="prev"></td>
			<td class="r-cell">Up</td><td class="r-qty" id="up"></td>
		</tr>
		<tr>
			<td class="r-cell">Open</td><td class="r-qty" id="open"></td>
			<td class="r-cell">Down</td><td class="r-qty" id="down"></td>
		</tr>
		<tr>
			<td class="r-cell">High</td><td class="r-qty" id="high"></td>
			<td class="r-cell">Unchange</td><td class="r-qty" id="unchange"></td>
		</tr>
		<tr>
			<td class="r-cell">Low</td><td class="r-qty" id="low"></td>
			<td class="r-cell">No Trade</td><td class="r-qty" id="notrade"></td>
		</tr>
		</tbody>
	</table>
	</div>
	</br>
	<div>
	<table border="1" width="100%" class="grid-def" id="foreignTab">
		<tr>
			<td class="r-cell">RG</td><td class="r-qty" id="rg"></td>
			<td class="r-cell">F.Buy</td><td class="r-qty" id="fbuy"></td>
		</tr>
		<tr>
			<td class="r-cell">NG</td><td class="r-qty" id="ng"></td>
			<td class="r-cell">F.Sell</td><td class="r-qty" id="fsell"></td>
		</tr>
		<tr>
			<td class="r-cell">TN</td><td class="r-qty" id="tn"></td>
			<td class="r-cell">Net</td><td class="r-qty" id="net"></td>
		</tr>
		<tr>
			<td class="r-cell">TOTAL</td><td class="r-qty" id="total"></td>
			<td></td><td></td>
		</tr>
	</table>
	</div>

	<? include "page-footer.php" ?>
</div>
</body>
</html>
