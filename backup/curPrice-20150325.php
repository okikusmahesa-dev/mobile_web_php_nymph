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
?>
<html>
<head> 
	<title>Order Book</title> 
	<meta name="viewport" content="width=device-width, initial-scale=1"> 
	<? include "inc-common.php" ?>
	<style type="text/css">
	<? include "css/icon.css" ?>
	<? include "css/curPrice.css" ?>
	</style>
	<script type="text/javascript">
		function vol2lot(qty) {
			return qty / 100;
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
						//console.log(jsRet.out[0])
						//
						var bidAdd = "";
						var offAdd = "";
						rows = [];
						for (i = 1; i <= 11; i++) {
							//
							if (i == 11) {
								id_v = i;
								bidAdd = "";
								bidVol = jsRet.out[0]["bidVolSum"];
								bidPrc = "SUM";
								offPrc = "SUM";
								offVol = jsRet.out[0]["offVolSum"];
								offAdd = "";
							}
							else {
								id_v = i;
								bidAdd = jsRet.out[0]["bid" + i + "a"];
								bidVol = jsRet.out[0]["bid" + i + "v"];
								bidPrc = jsRet.out[0]["bid" + i];
								offPrc = jsRet.out[0]["off" + i];
								offVol = jsRet.out[0]["off" + i + "v"];
								offAdd = jsRet.out[0]["off" + i + "a"];
							}
							
							if (bidAdd == undefined) {
								bidAdd = "";
							}
							if (offAdd == undefined) {
								offAdd = "";
							}
							//
							cell_v = [
								bidAdd,
								bidVol / 100,
								bidPrc,
								offPrc,
								offVol / 100,
								offAdd
								];
							row = {id:id_v, cell:cell_v};
							rows.push(row);
						}
						args = {dataType:"json", rows:rows, page:1, total:11};
						$('#r-quote').flexAddData(args);
						//console.log(args);
						var r = document.getElementById("r-quote").getElementsByTagName('tr');
						var c = r[10].getElementsByTagName('td');
						jQuery( c[2] ).css("background-color", "#cccccc");
						jQuery( c[3] ).css("background-color", "#cccccc");
						/*
						for (i = 0; i < 10; i++) {
							var clm = r[i].getElementsByTagName('td');
							jQuery( clm[1] ).css("background-color", "#ffb6c1");
							jQuery( clm[4] ).css("background-color", "#afeeee");
						}
						*/
					}
					//alert( "jsRet=" + JSON.stringify(jsRet) );
				},
				error: function(data, status, err) {
					console.log("error forward : "+data);
					//alert('error communition with server.');
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
						if (jsRet.out.length < 1) return;
						$("#price").text(jsRet.out[0].price);
						var chgV = getChgV(jsRet.out[0].chgP, jsRet.out[0].chg);
						$("#chg").text(chgV + "(" + jsRet.out[0].chgR + "%)");
						//
						$("#value").text(sprintf("%.0f", Number(jsRet.out[0].val) / 1000000.0));
						$("#volume").text(vol2lot(jsRet.out[0].vol));
						$("#freq").text(jsRet.out[0].freq);
						//
						$("#oprice").text(jsRet.out[0].open);
						$("#hprice").text(jsRet.out[0].high);
						$("#lprice").text(jsRet.out[0].low);
						$("#prev").text(jsRet.out[0].prev);
					}
					setTimeout(tr100001(code, board), 1000);
					//alert( "jsRet=" + JSON.stringify(jsRet) );
				},
				error: function(data, status, err) {
					console.log("error forward : "+data);
					//alert('error communition with server.');
				}
			});
			timerUpd();
		}
		function qryTr100000() {
			var code = $("#codeTxt").val();
			code = code.toUpperCase();
			$("#codeTxt").val(code);
			tr100000(code, "RG");
		}

		function bidOrder(celDiv, id) {
			/*
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
			*/
		}

		function offOrder(celDiv, id) {
		}

		function timerUpd() {
			setTimeout(qryTr100000, 5000)
		}
		
		$(document).ready(function() {
			jQuery('div').live('pagehide', function(event, ui){
				var page = jQuery(event.target);
				if(page.attr('data-cache') == 'never'){
					page.remove();
				};
			});
			//
			$("#codeTxt").keyup(function(event) {
				if (event.keyCode == 13) {
					qryTr100000();
				}
			});
			//
			$("#okBtn").click(qryTr100000);
			//
			var code = $("#codeTxt").val();
			code = code.toUpperCase();
			$("#codeTxt").val(code);
			tr100000(code, "RG");
			// flexigrid
			args = {
				title : "Order Book",
				dataType : "json",
				height : "auto",
				singleSelect : false,
				colModel : [
					{display:"Add", width:30, sortable:false, align:'center'},
					{display:"Bid(v)", width:50, sortable:false, align:'right'},
					{display:"Bid", width:40, sortable:false, align:'center', process:bidOrder},
					{display:"Off", width:40, sortable:false, align:'center', process:offOrder},
					{display:"Off(v)", width:50, sortable:false, align:'left'},
					{display:"Add", width:30, sortable:false, align:'center'},
				],
				buttons : [
					{name:'Refresh', bclass:'refresh', onpress:qryTr100000},
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
	<div>
		<input id="codeTxt" type="text" value="BBCA" maxlength=8 style="float:left;width:120;text-align:center" />
		<a id="okBtn" href="#" data-role="button" data-icon="refresh" style="float:right">Refresh</a><br/>
		<center>
		<label id="price" class="lprice" style="font-size:20px"></label>
		</center>
	</div>
	<div data-role="main">
	<table border="1" data-role="table" class="ui-responsive grid-def" id="curPrice" width="100%">
		<thead>
		</thead>
		<tbody>
		<tr>
			<td class="r-cell">Prev</td><td id="prev" class="r-qty"></td>
			<td class="r-cell">Chg(%)</td><td id="chg" class="r-qty"></td>
		</tr>
		<tr>
			<td class="r-cell">Open</td><td id="oprice" class="r-qty"></td>
			<td class="r-cell">Value(M)</td><td id="value" class="r-qty"></td>
		</tr>
		<tr>
			<td class="r-cell">High</td><td id="hprice" class="r-qty"></td>
			<td class="r-cell">Volume</td><td id="volume" class="r-qty"></td>
		</tr>
		<tr>
			<td class="r-cell">Low</td><td id="lprice" class="r-qty"></td>
			<td class="r-cell">Freq</td><td id="freq" class="r-qty"></td>
		</tr>
		</tbody>
	</table>
	</div>
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
