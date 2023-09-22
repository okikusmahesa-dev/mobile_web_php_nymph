<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");


$userId = $_GET['id']
$clientId = $_GET['logid']
$pinState = 0;
$pinLogin = 0;

?>
<html>
<head> 
	<title>Portfolio</title> 
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<? include "inc-common.php" ?>
	<style type="text/css">
	<? include "css/icon.css" ?>
	</style>
	<script type="text/javascript">
		var totLq = 0;
		var ordList = 0;
				
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
					/* {display:"Sell", width:30, sortable:false, align:'center'}, */
					{display:"Code", width:60, sortable:false, align:'center'},
					{display:"Lot", width:30, sortable:false, align:'right'},
					{display:"Shares", width:60, sortable:false, align:'right', hide:'true'},
					{display:"Buy Prc", width:60, sortable:false, align:'right'},
					{display:"Last Prc", width:60, sortable:false, align:'right'},
					{display:"Buy Val", width:100, sortable:false, align:'right', hide:'true'},
					{display:"Market Val", width:100, sortable:false, align:'right', hide:'true'},
					{display:"Haircut", width:50, sortable:false, align:'right'},
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

			//r-headportfolio
			hport = {
				title: "Portfolio",
				dataType: "json",
				height: "auto",
				colModel: [
					{display: "RDI", width:60, sortable: false, align: "right"},
					{display: "AR/AP", width:60, sortable: false, align: "right"},
					{display: "Open Buy", width:80, sortable: false, align: "right"},
					{display: "Cash", width:60, sortable: false, align: "right"},
					{display: "Trd Limit", width:80, sortable: false, align: "right"},
					{display: "Cur Ratio", width:80, sortable: false, align: "right"},
					{display: "Pot Ratio", width:80, sortable: false, align: "right"}
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
				$.mobile.loading('show');
				refreshAll(); 
				tr800300(<?=$userId;?>, <?=$pinLogin;?>);
			}
			//
		});
		
		//trTimeFlag added by Toni 20161128
		function tr800017(){
			$.ajax({
				type : "POST",
				url : "json/trproc.php",
				dataType : "json",
				data: "tr=800017&id=1",
				success: function(jsRet){
					if (jsRet.status !=1){
						alert("Error: "+ jsRet.mesg);
					}else{
						if (jsRet.out[0].flag=="N"){
							curDate = new Date();
							//alert(curDate.getHours());
							jam = "03.15";
							if (curDate.getHours()>6){
								jam = "16.25";
							}
							alert("Portfolio saat ini sedang dalam proses kalkulasi\nbisa diakses kembali pada pukul "+ jam);
							location.href = "index.php?uFlag=1";
						}
						//alert("hasil = " + jsRet.out[0].flag);
					}
				}
			});
		}
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
						/*
						console.log(jsRet.out);
						console.log(jsRet.out2);
						*/
						//
						rows = [];
						cnt = jsRet.out.length;
						var ops_exist = 0;
						var color = [];
						color["up"] = [];
						color["down"] = [];
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
							//bval = sprintf("%.2f", Number(jsRet.out[i].stkVal));
							bval = sprintf("%.2f", Number(shares) * Number(bprc));
							mval = jsRet.out[i].mktVal;
							hc = jsRet.out[i].hairCut;
							sval = sprintf("%.2f", Number(jsRet.out[i].lqVal));
							unrl = parseFloat(jsRet.out[i].profitVal).toFixed(0);
							prcnt = parseFloat(jsRet.out[i].profitRate).toFixed(2);
							dnb = sprintf("%.0f", Number(jsRet.out[i].buy));
							dns = sprintf("%.0f", Number(jsRet.out[i].sell));
							opb = sprintf("%.0f", Number(jsRet.out[i].bid));
							ops = sprintf("%.0f", Number(jsRet.out[i].offer));

							suspend = jsRet.out[i].suspend;

							dnb == "0" ? dnb = "" : dnb;
							dns == "0" ? dns = "" : dns;
							opb == "0" ? opb = "" : opb;
							ops == "0" ? ops = "" : ops;
							//
							if (ops != "0") {
								ops_exist = Number(ops) * 100 * Number(lprc) * (1.0 - Number(hc/100));
							} else {
								ops_exist = 0;
							}

							if (suspend == "Y") {
								totLq = 0;
							} else {
								totLq = totLq + Number(sval) + ops_exist;
							}

							if (Number(mval) > Number(bval)) {
								color["up"].push(i);
							} else if (Number(mval) < Number(bval)) {
								color["down"].push(i);
							}

							//
							cell_v = [
								/* "S", */
								code,
								lot,
								addCommas(shares),
								addCommas(bprc),
								addCommas(lprc),
								addCommas(bval),
								addCommas(mval),
								hc,
								addCommas(sval),
								addCommas(unrl),
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

						for (i=0;i<color["down"].length;i++) {
							var r = document.getElementById("r-portfolio").getElementsByTagName('tr');
							var c = r[color["down"][i]].getElementsByTagName('td');
							jQuery( c[6] ).css("color", "#ff0000");
							jQuery( c[9] ).css("color", "#ff0000");
							jQuery( c[10] ).css("color", "#ff0000");
						}
						for (i=0;i<color["up"].length;i++) {
							var r = document.getElementById("r-portfolio").getElementsByTagName('tr');
							var c = r[color["up"][i]].getElementsByTagName('td');
							jQuery( c[6] ).css("color", "#0000ff");
							jQuery( c[9] ).css("color", "#0000ff");
							jQuery( c[10] ).css("color", "#0000ff");
						}

						
					}
				}
			});
		}

		//
		function refreshAll() {
			totLq = 0;
			ordList = 0;
			qryTr800017();
			qryTr182600();
			qryTr800001();
			setTimeout(qryTr800000, 5000);
			//setTimeout(refreshAll, 5000);
		}
		//
		function qryTr800017(){
			tr800017();
		}

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
						//console.log(jsRet.out[0]);
						rows = [];
						//
						rdi = sprintf("%.0f", Number(jsRet.out[0].rdn));
						arap = sprintf("%.0f", Number(jsRet.out[0].cashBalance) - Number(rdi));
						openbuy = sprintf("%.0f", Number(jsRet.out[0].bid));
						cash = sprintf("%d", Number(jsRet.out[0].cashBalance) - Number(openbuy));
						debt = Number(jsRet.out[0].cashBalance) - Number(openbuy);
						asset = totLq + ordList;
						trdlimit = sprintf("%.0f", (debt * 2.5) + (asset * 1.5));
						potratio = sprintf("%.2f", Number(-(debt / asset) * 100));
						debt >= 0 ? potratio = "0.00" : potratio;
						/*
						console.log(Number(jsRet.out[0].cashBalance) + "|" + openbuy + "|" + totLq + "|" + ordList);
						console.log("potratio=> "+potratio);
						*/
						curratio = jsRet.out[0].curRatio;
						curratio === undefined ? curratio = "0.00" : curratio = sprintf("%.2f", Number(curratio));
						//console.log(curratio);
						arap == "-0" ? arap = "0" : arap;
						//
						cell_v = [
							addCommas(rdi),
							addCommas(arap),
							addCommas(openbuy),
							addCommas(cash),
							addCommas(trdlimit),
							curratio + "%",
							potratio + "%",
						];
						row = {id: 0, cell: cell_v};
						rows.push(row);
					}
					args = {dataType: "json", rows: rows, page: 1, total: 1};
					$('#r-headportfolio').flexAddData(args);

					var r = document.getElementById("r-headportfolio").getElementsByTagName('tr');
					var c = r[0].getElementsByTagName('td');
					arapVal = c[1].innerText;
					if (Number(arapVal) != 0) {
					//	jQuery( c[1] ).css("color", "#ff0000");
					}
				},
				error: function(data, status, err) {
					console.log("error forward : "+data);
				}
			});
			$.mobile.loading('hide');
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
						//console.log(jsRet.out);
						//console.log(jsRet.out2);
						//
						cnt = jsRet.out.length;

						for (i = 0; i < cnt; i++) {
							if (jsRet.out[i].status == "O" && jsRet.out[i].type == "B") {
								price = jsRet.out[i].price;
								qty = jsRet.out[i].qty;
								code = jsRet.out[i].code;
								hc = jsRet.out[i].hairCut;
								lqRate = 1.00 - Number(hc);

								ordList = ordList + Number(price) * Number(qty) * Number(lqRate);
								//console.log(ordList + " = > " + price + " * " + qty + " * " + hc);
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
					alert('error communition with server.');
					window.location.replace("portfolio.php");
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
	</br>
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
