<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");
?>
<html>
<head> 
	<title>Stock Quote</title> 
	<meta name="viewport" content="width=device-width, initial-scale=1"> 
	<? include "inc-common.php" ?>
	<style type="text/css">
	<!--
	<? include "css/icon.css" ?>
	.grid-def {
		border-collapse:collapse;
		border:1px solid black;
	}
	.col-header {
		background-color:#b9c9f3;
		text-align : center;
	}
	.r-cell {
		background-color:#b9c9f3;
		text-align : center;
	}
	.r-qty {
		text-align : right;
	}
	.data-row {height:23px;}
	.bid-qty {
		background-color:#feeeee; height:23px; border:1px solid black;
		text-align : right;
	}
	.bid-price {
		background-color:#feeeee;; border:1px solid black;
		text-align : center;
	}
	.off-qty {
		background-color:#e4f2fa; height:23px; border:1px solid black;
		text-align : center;
	}
	.off-price {
		background-color:#e4f2fa;; border:1px solid black;
		text-align : right;
	}
	.nmbtn1 {
		font-family : Arial, Helvetica, sans-serif;
		font-size : 11px;
	}
	//-->
	</style>
	<script type="text/javascript">
		function vol2lot(qty) {
			return qty / 500;
		}
		function tr100001(code, board) {
			$.ajax({
				type: "POST",
				url: "json/trproc.php",
				dataType : "json",
				data: "tr=100001&code=" + code + "&board=" + board,
				success: function(jsRet){
					if (jsRet.status != 1) {
						alert(jsRet.mesg);
					} else {
						//
						rows = [];
						for (i = 1; i <= 20; i ++) {
							if (i <= 10) {
								idx = 11 - i;
								prcName = "off" + idx;
								volName = "off" + idx + "v";
								bidVol = "";
								price = jsRet.out[0][prcName];
								offVol = vol2lot(jsRet.out[0][volName]);
							}
							if (i >= 11) {
								idx = i - 10;
								prcName = "bid" + idx;
								volName = "bid" + idx + "v";
								bidVol = vol2lot(jsRet.out[0][volName]);
								price = jsRet.out[0][prcName];
								offVol = "";
							}
							id_v = sprintf("id-%d", (i - 1));
							cell_v = [
								"<button class='nmbtn1'>W</button>", "<button class='nmbtn1'>A</button>",
								"",
								bidVol,
								price,
								offVol,
								"",
								"<button class='nmbtn1'>A</button>", "<button class='nmbtn1'>W</button>",
								];
							row = {id:id_v, cell:cell_v};
							rows.push(row);
						}
						args = {dataType:"json", rows:rows, page:1, total:20};
						$('#r-quote').flexAddData(args);
						console.log(args);
					}
					//alert( "jsRet=" + JSON.stringify(jsRet) );
				},
				error: function(data, status, err) {
					console.log("error forward : "+data);
					alert('error communition with server.');
				}
			});
		}
		function tr100000(code, board) {
			$.ajax({
				type: "POST",
				url: "json/trproc.php",
				dataType : "json",
				data: "tr=100000&code=" + code + "&board=" + board,
				success: function(jsRet){
					if (jsRet.status != 1) {
						alert(jsRet.mesg);
					} else {
						$("#price").text(jsRet.out[0].price);
						var chgV = getChgV(jsRet.out[0].chgP, jsRet.out[0].chg);
						$("#chg").text(chgV + "(" + jsRet.out[0].chgR + "%)");
						//
						$("#value").text(sprintf("%.0f", Number(jsRet.out[0].val) / 1000.0));
						$("#volume").text(vol2lot(jsRet.out[0].vol));
						$("#freq").text(jsRet.out[0].freq);
						//
						$("#oprice").text(jsRet.out[0].open);
						$("#hprice").text(jsRet.out[0].high);
						$("#lprice").text(jsRet.out[0].low);
						$("#prev").text(jsRet.out[0].prev);
					}
					tr100001(code, board);
					//alert( "jsRet=" + JSON.stringify(jsRet) );
				},
				error: function(data, status, err) {
					console.log("error forward : "+data);
					alert('error communition with server.');
				}
			});
		}
		function qryTr100000() {
			var code = $("#codeTxt").val();
			code = code.toUpperCase();
			$("#codeTxt").val(code);
			tr100000(code, "RG");
		}

		function bidOrder(celDiv, id) {
			$(celDiv).click(function() {
				idNum = parseInt(id.substr(3));
				if (idNum >= 11) {
					//alert(celDiv + ":" + id.substr(3));
					code = $("#codeTxt").val();
					price = $("#row" + id + " td:nth-child(5) div").html();
					qty = 1;
					//alert("buy order=" + sprintf("%s,%s,%d", code, price, qty));
					goBuy(code, parseInt(price), qty);
				}
			});
		}

		function offOrder(celDiv, id) {
		}

		$(document).ready(function() {
			jQuery('div').live('pagehide', function(event, ui){
				var page = jQuery(event.target);
				if(page.attr('data-cache') == 'never'){
					page.remove();
				};
			});
			$("#codeTxt").keyup(function(event) {
				if (event.keyCode == 13) {
					qryTr100000();
				}
			});
			$("#okBtn").click(qryTr100000);
			tr100000("TLKM", "RG");
			// flexigrid
			args = {
				title : "Quote",
				dataType : "json",
				height : "auto",
				singleSelect : false,
				colModel : [
					{display:"W", width:30, sortable:false, align:'center'},
					{display:"A", width:30, sortable:false, align:'center'},
					{display:"Bid", width:40, sortable:false, align:'center', process:bidOrder},
					{display:"Bid(v)", width:50, sortable:false, align:'right'},
					{display:"Price", width:50, sortable:false, align:'center'},
					{display:"Off(v)", width:50, sortable:false, align:'right'},
					{display:"Off", width:40, sortable:false, align:'right', process:offOrder},
					{display:"A", width:30, sortable:false, align:'center'},
					{display:"W", width:30, sortable:false, align:'center'},
				],
				buttons : [
					{name:'Refresh', bclass:'edit', onpress:qryTr100000},
					{separator: true}
				]
			};
			$('#r-quote').flexigrid(args);
		});
	</script>
</head> 
<body style="background:#ffffff;">
<div data-role="page" id="home" data-cache="never">
<? include "page-header.php" ?>
	<div style="float:block;background:#ffffff;height:30px;">
		<input id="codeTxt" type="text" value="TLKM" maxlength=8 style="float:left;width:80;" />
		<a id="okBtn" href="#" data-role="button" data-icon="search" style="float:right">Refresh</a><br/>
	</div>
	<div>
	<table border="1" class="grid-def" width="100%">
		<thead>
		</thead>
		<tbody>
		<tr>
			<td width="25%" class="r-cell">Price</td><td id="price" class="r-qty" width="25%"></td>
			<td width="25%" class="r-cell">Chg(%)</td><td id="chg" class="r-qty" width="25%"></td>
		</tr>
		<tr>
			<td class="r-cell">Value(T)</td><td id="value" class="r-qty"></td>
			<td class="r-cell">Volume</td><td id="volume" class="r-qty"></td>
		</tr>
		<tr>
			<td class="r-cell">Freq</td><td id="freq" class="r-qty"></td>
			<td class="r-cell">&nbsp;</td><td></td>
		</tr>
		<tr>
			<td class="r-cell">Open</td><td id="oprice" class="r-qty"></td>
			<td class="r-cell">High</td><td id="hprice" class="r-qty"></td>
		</tr>
		<tr>
			<td class="r-cell">Low</td><td id="lprice" class="r-qty"></td>
			<td class="r-cell">Prev</td><td id="prev" class="r-qty"></td>
		</tr>
		</tbody>
	</table>
	</div>
	<hr/>
	<table id="r-quote" class="flexigrid">
		<thead>
		</thead>
		<tbody>
		</tbody>
	</table>
<? include "page-footer.php" ?>
</div>
</body>
</html>
