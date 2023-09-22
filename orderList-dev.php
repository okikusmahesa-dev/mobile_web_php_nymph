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
	//echo "<script>alert('Please Input PIN first!');</script>";
	echo "<script>alert('Please Input PIN first.');</script>";
	header("refresh:1; url=inPin.php");
	exit(0);
} else {
	$pinState = $_SESSION["pinState"];
	$pinLogin = $_SESSION["pin"];
	//$_SESSION["url"]="https://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
	 //echo "<script>alert('$_SESSION[url]')</script>";
	echo '<script type="text/javascript">setTimeout(function() { window.location.href = "https://mobile.bcasekuritas.co.id/tlogout.php"; }, 15 * 60000);</script>';
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
		function tr182600(id, bsFlag, status, cnt) {
			today = "";//getToday("yyyymmdd");
			var color = [];
			color["B"] = [];
			color["S"] = [];
			$.mobile.loading('show')
            $.ajax({
				type: "POST",
				url: "json/trproc.php",
				dataType : "json",
				data: sprintf("tr=182600&userId=%s&cliId=%s&pin=&code=&board=&bsFlag=%s&status=%s&from=%s&to=%s&cnt=%d&keyDate=&keyOrderNo=999999999&price=",id, id, bsFlag, status,today,today, cnt),
				success: function(jsRet) {
					if (jsRet.status != 1) {
						alert(jsRet.mesg);
					} else {
						//alert( "jsRet=" + JSON.stringify(jsRet) );
                        console.log(jsRet);
						cnt = jsRet.out.length;
						iCnt = 0;
						rows = [];
						for (i = 0; i < cnt; i ++) {
							id_v = "id" + i;
                            
                            var Messages = ""
                            
                            if(jsRet.out[i]["status"] == "O"){
                                Messages = "Open";
                            }else if(jsRet.out[i]["status"] == "M"){
                                Messages = "Matched";
                            }else if(jsRet.out[i]["status"] == "A"){
                                Messages = "Amended";
                            }else if(jsRet.out[i]["status"] == "W"){
                                Messages = "Withdraw";
                            }else if(jsRet.out[i]["status"] == "P"){
                                Messages = "Partial Matched";
                            }else if(jsRet.out[i]["status"] == "D"){
                                Messages = "Delayed";
                            }else if(jsRet.out[i]["status"] == "B"){
                                Messages = "Bad";
                            }else if(jsRet.out[i]["status"] == "N"){
                                Messages = "New";
                            }

							//
							type = jsRet.out[i]["type"];
							qty = Number(jsRet.out[i]["qty"] / 100);
							matchqty = Number(jsRet.out[i]["matchedQty"] / 100);
							balqty = Number(jsRet.out[i]["balQty"] / 100);

							if (type == "B") {
								color["B"].push(i);
							} else if (type == "S") {
								color["S"].push(i);
							}
							//

							cell_v = [
								"W", "A",
								jsRet.out[i]["code"],
								type,
								jsRet.out[i]["price"],
								qty,
								jsRet.out[i]["status"],
                                matchqty,
								balqty,
								jsRet.out[i]["board"],
								jsRet.out[i]["time"],
								jsRet.out[i]["order"],
								jsRet.out[i]["marketOrderId"],
								Messages,
                                ];
							row = {id:id_v, cell:cell_v};
							rows.push(row);
						}
						args = {dataType:"json", rows:rows, page:1, total:cnt};
						$('#r-orderList').flexAddData(args);

						for (i=0;i<cnt;i++) {
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
                    $.mobile.loading('hide')
				},
				error: function(data, status, err) {
					console.log("error forward : "+data);
					alert('error communition with server.');
					window.location.replace("index.php?uFlag=1");
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
				height : 350,
                singleSelect : true,
				colModel : [
					{display:"W", width:30, sortable:false, align:'center', process:withdrawFunc},
					{display:"A", width:33, sortable:false, align:'center', process:amendFunc},
					{display:"Code", width:50, sortable:false, align:'center'},
					{display:"Type", width:30, sortable:false, align:'right'},
					{display:"Price", width:50, sortable:false, align:'right'},
					{display:"Qty", width:50, sortable:false, align:'right'},
					{display:"Status", width:50, sortable:false, align:'center'},
                    {display:"Matched", width:60, sortable:false, align:'right'},
					{display:"Open", width:50, sortable:false, align:'right'},
					{display:"Board", width:50, sortable:false, align:'right'},
					{display:"Time", width:50, sortable:false, align:'center'},
					{display:"Order#", width:50, sortable:false, align:'center'},
					{display:"Market#", width:70, sortable:false, align:'right'},
				    {display:"Messages", width:70, sortable:false, align:'center'},
                ],
				buttons : [
					{name:'Refresh', bclass:'refresh', onpress:qryTr182600},
					{separator: true}
				],
			};
			$('#r-orderList').flexigrid(args);
			qryTr182600();
		
			$("#withdrawOK").one("click",function(){
				qryTr189300();
			});


            $("#amendOK").one("click",function(){
				if(chkTime()=="OK"){	
					qryTr189200();
				}else{
					alert("Cannot amend in this time");
				}
			});


			//$("#withdrawOK").click(qryTr189300);
			//$("#amendOK").click(qryTr189200);
		});

		//
		function withdrawFunc(celDiv, id) {
			$(celDiv).click(function() {
				qryTr182600();
				setTimeout(function(){
				row = parseInt(id.substr(3));
				orderNo = $("#row" + id + " td:nth-child(12) div").text();
				stock = $("#row" + id + " td:nth-child(3) div").text();
				price = $("#row" + id + " td:nth-child(5) div").text();
				vol = $("#row" + id + " td:nth-child(6) div").text();
                board = $("#row" + id + " td:nth-child(10) div").text();
				status = $("#row" + id + " td:nth-child(7) div").text();
                
				if (board == "NG" || board == "TN" || board == "TS") {
                    alert("TN/NG/TS Cannot Withdraw");
                    return;
                }
                //if (status == "W" || status == "A" || status == "M" || 
                 //   status == "B" || status == "D") {
				//	alert("Cannot Withdraw!");
				//	return;
				//}
				if (status == "O" || status == "N" || status == "P"){
					$("#orderNoW").text(orderNo);
					$("#stockW").text(stock);
					$("#priceW").text(price);
					$("#volW").text(vol);
					$.mobile.changePage("#withdrawDialog", { role: "dialog" } );
				}
				else{
					alert("Cannot Withdraw");
					return;
				}
				},3000);
			});
		}

		var real_prcA = 0;
		var real_volA = 0;
		var real_typeA = 'B';

		function amendFunc(celDiv, id) {
			$(celDiv).click(function() {
				qryTr182600();
				setTimeout(function(){
				row = parseInt(id.substr(3));
				orderNo = $("#row" + id + " td:nth-child(12) div").text();
				stock = $("#row" + id + " td:nth-child(3) div").text();
				type = $("#row" + id + " td:nth-child(4) div").text();
				price = $("#row" + id + " td:nth-child(5) div").text();
				vol = $("#row" + id + " td:nth-child(6) div").text();
                board = $("#row" + id + " td:nth-child(10) div").text();
				status = $("#row" + id + " td:nth-child(7) div").text();
                
                if (board == "TN" || board == "NG" || board == "TS") {
                    alert("NG/TN/TS Cannot Ammend");
                    return;
                }
				//if (status == "W" || status == "A" || status == "M" || 
              //      status == "N" || status == "B" || status == "D") {
			//		alert("Cannot Amend!");
			//		return;
			//	}
				if (status == "O" || status == "P" ){
					$("#orderNoA").val(orderNo);
					$("#stockA").val(stock);
					$("#priceA").val(price);
					$("#volA").val(vol);
					real_prcA = Number(price);
					real_volA = Number(vol);
					real_typeA = type;
					tr100000(stock);
					tr800001("<?=$userId;?>", today, stock);

				//if (status == "P") {
				//	$("#volA").prop('disabled', 'true');
				//}

					$.mobile.changePage("#amendDialog", { role: "dialog" } );
				}else{
					alert("Cannot Amend");
					return;
				}
				},3000);
			});
		}
		//

		function qryTr182600() {
			var userId = "<?=$userId;?>";
			var jum = 30
            $(".bDiv").scroll(function(){
                var scroll_top = $(this).scrollTop()
                var scroll_height = $(this)[0].scrollHeight - 350
                console.log("Height : " + scroll_height)
                console.log("Scroll Position : " + scroll_top)
                if(scroll_top == scroll_height){
                    if (scroll_height != 0) {
                        jum += 30
                        tr182600(userId, "0", "0", jum)
                    } 
                }
            })
            $.mobile.loading('show');
			tr182600(userId, "0", "0", jum);
			$.mobile.loading('hide');
			console.log("refresh");
		}
		
		//withdraw
		function qryTr189300() {
			console.log("text=" + String($("#priceW").text()));
			console.log("val =" + String($("#priceW").val()));

			if ( confirm("Are you sure?") ) {
				var userId = "<?=$userId?>";
				$.mobile.loading('show');
				if($("#orderNoW").text().length > 0 &&  $("#stockW").text().length && $("#priceW").text().length > 0 && $("#volW").text().length>0){
					tr189300(userId, $("#orderNoW").text(), $("#stockW").text(), $("#priceW").text(), $("#volW").text());
				}else{
					alert("Please Try Again");
					window.open("orderList.php","_self");
				}
				$.mobile.loading('hide');
			}
		}

		//amend
		function qryTr189200() {
			console.log("uLimit: " + uLimit + " lLimit: " + lLimit);
			console.log("text=" + String($("#priceA").text()));
			console.log("val =" + String($("#priceA").val()));

			ret = chkFraction();
			//alert("lLimit_lama/lLimit_baru = " +lLimit+"/"+lLimit_baru+ " uLimit_lama/uLimit_baru = " +uLimit+"/"+uLimit_baru);
			//alert("Fract = "+fract);
			//ret = 1;

			//console.log("Balance: " + balA);
			if ( (Number($("#volA").val()) > balA) && real_typeA == 'S' ) {
				window.open("orderList.php", "_self");
				alert("Cannot Amend. Balance over [" + balA + "]");
				return;
			}
//
			if (Number($("#priceA").val()) > uLimit) {
				window.open("orderList.php", "_self");
				alert("Cannot Order. Price cannot be more than " + uLimit + "!");
				return;
			}

			if (Number($("#priceA").val()) < lLimit) {
				window.open("orderList.php", "_self");
				alert("Cannot Order. Price cannot be less than " + lLimit + "!");
				return;
			}
//

			if (ret != 1) {
				window.open("orderList.php", "_self");
				alert("Cannot Amend. Price is not valid!");
				return;
			}

			if (real_prcA == Number($("#priceA").val()) && Number($("#volA").val()) == real_volA) {
				window.open("orderList.php", "_self");
				alert("Cannot Amend. No data changed.");
				return;
			}

			if (real_prcA == Number($("#priceA").val()) && Number($("#volA").val()) > real_volA) {
				window.open("orderList.php", "_self");
				alert("Cannot Amend. Qty cannot be greater than before.");
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

		function chkTime() {

		//var cDt = new Date();
		//tm = cDt.format("HH:MM:ss");
		//var now_utc = new Date(now.getUTCHours(),now.getUTCMinutes,now.getUTSSeconds());
		//var tm2 = new Date(now_utc.getTime() + offset)
		//window.alert(tm);
		//window.alert(tm2.toLocaleString());

		//if ( tm > "08:00:00" && tm < "16:15:00" ) {
		//  return "OK";
		//} else {
		//  return "NO";
		//}

		<?php
		$open = "08:55:01";
		$open2 = "08:59:59";
		$close = "16:00:01";
		$close2 = "16:04:59";
		if((time() > strtotime($open) && time() < strtotime($open2))||(time() > strtotime($close) && time() < strtotime($close2))){
		?>
		return "NO";
		<?php
		}
		else {
		?>
		return "OK";
		<?php
		}
		?>


		return "NO";

		}

		function chkFraction() {
			price = document.getElementById('priceA').value;
			code = document.getElementById('stockA').value;

			interval = 0;

			//console.log("prevPrice: " + Gprev);

			var reks = false;

			if (code.substring(0,1) == 'X' || code.substring(0,2) == 'R-') {
				console.log("x...");
				interval = 1;
				lLimit_baru = lLimit;
				uLimit_baru = uLimit;
				reks = true;
			} else {
				if (Gprev < 200) {
					interval = 1;
					lLimit_baru = lLimit;
					uLimit_baru = uLimit;
					//alert("MASUK BOS 200");
				}
				else if((Gprev >= 200) && (Gprev < 500)) {
					if(lLimit%2!=0){
						lLimit_baru= lLimit-(lLimit%2)+2;
						uLimit_baru= uLimit-(uLimit%2)-2;
					}
					else{
						lLimit_baru = lLimit;
						uLimit_baru = uLimit;
					}
					interval = 2;
				}
				else if((Gprev >= 500) && (Gprev < 2000)) {
					if(lLimit%5!=0){
						lLimit_baru= lLimit-(lLimit%5)+5;
						uLimit_baru= uLimit-(uLimit%5)-5;
					}
					else{
						lLimit_baru = lLimit;
						uLimit_baru = uLimit;
					}
					interval = 5;
				}
				else if((Gprev >= 2000) && (Gprev < 5000)) {
					if(lLimit%10!=0){
						lLimit_baru= lLimit-(lLimit%10)+10;
						uLimit_baru= uLimit-(uLimit%10)-10;
					}
					else{
						lLimit_baru = lLimit;
						uLimit_baru = uLimit;
					}
					interval = 10;
				}
				else if (Gprev >= 5000){
					 if(lLimit%25!=0){
						lLimit_baru= lLimit-(lLimit%25)+25;
						uLimit_baru= uLimit-(uLimit%25)-25;
			 		}
			 		else{
						lLimit_baru = lLimit;
						uLimit_baru = uLimit;
			 		}
					interval = 25;
				}
			}

			console.log(interval);


			// uLimit - lLimit
			fract = [];
			x = 0;
			for (i=Number(lLimit_baru); i<=Number(uLimit_baru); i++) {
				fract.push(x.toString());
				if (reks) {
					i = (i + 1) - 1;
					x = i;
				} else {

					if (i < 200) {
						i = (i + 1) - 1;
						x = i;
					} else if ((i >= 200) && (i < 500)) {
						i = (i + 2) - 1;
						x = (i + 1);
					} else if ((i >= 500) && (i < 2000)) {
						i = (i + 5) - 1;
						x = (i + 1);
					} else if ((i >= 2000) && (i < 5000)) {
						i = (i + 10) - 1;
						x = (i + 1);
					} else if (i >= 5000) {
						i = (i + 25) - 1;
						x = (i + 1);
					} else {
						i = (i + Number(interval)) - 1;
						x = (i + Number(interval)) - 1;
					}

				}
			}

			//alert("DISINI!!! : " + Gprev +" price: "+ price + " code: "+code+" -- llimit baru "+lLimit_baru+" ulimit baru "+uLimit_baru);
			//alert("DISINI!!! : " +fract);

			
			console.log(fract);
/*
			for (i=0; i<fract.length; i++) {
				if (price == fract[i]) {
					return 1;
				}
			}
*/

		if((price >= lLimit_baru) && (price <= uLimit_baru)){
			
			if (code.substring(0,1) == 'X' || code.substring(0,2) == 'R-') 
				ret = price % 1;
			else if(price > 5000)
				ret = price % 25;
			else if((price > 2000) && (price <= 5000))
				ret = price % 10;
			else if((price > 500) && (price <= 2000))
				ret = price % 5;
			else if((price > 200) && (price <= 500))
				ret = price % 2;
			else if((price >= 50) && (price <= 200))
				ret = price % 1;
			else if(price < 50)
				//alert("MASUK BOS ELSE IF BARU");
				ret = price % 1;
		}
		else{
			return 0;
		}
			
		if(ret > 0){
			return 0;}
		else{
			return 1;}
			
			//return 0;
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
					//alert("LAGI DI KIRIM BOS"+jsRet.out[0].mesg+"____"+jsRet.out[0].status);
					if (jsRet.status != 1) {
						alert(jsRet.mesg);
					} else {
						//
						console.log(jsRet);
						//
						statusFld = jsRet.out[0].status;
						mesgFld = jsRet.out[0].mesg;
						//alert("MASUK ELSE BOS");

						if (statusFld == "1")// delete by toni 20160808===>> && mesgFld == "Order accepted.") 
						{
							alert(mesgFld + ": " + statusFld);
							aBtn = 0;
							window.open("orderList.php", "_self");
						}
						else {
							//alert("MASUK ELSE 2 BOS");
							window.open("orderList.php", "_self");
							alert(mesgFld + "");
						}
						//
					}
				},
				error: function(data, status, err) {
					console.log("error forward : "+data);
							//alert("MASUK error");
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
    <style>
        table tbody tr td{
            padding: 8px;
        }
        .flexigrid tr.erow td{
            background: #ffffff;
        }
        .flexigrid div.bDiv tr.trSelected:hover td,.flexigrid div.bDiv tr.trSelected:hover td.sorted,.flexigrid div.bDiv tr.trOver.trSelected td.sorted,.flexigrid div.bDiv tr.trOver.trSelected td,.flexigrid tr.trSelected td.sorted,.flexigrid tr.trSelected td{
            background: none;
        }
        .flexigrid div.cDrag div:hover,.flexigrid div.cDrag div.dragging{
            background: none;
        }
    </style>
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
