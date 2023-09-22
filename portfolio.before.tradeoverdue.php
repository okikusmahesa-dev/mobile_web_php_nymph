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
	//echo '<script type="text/javascript">setTimeout(function() { window.location.href = "https://mobile.bcasekuritas.co.id/tlogout.php"; }, 15 * 60000);</script>';
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

//check time
$dw = date('w');
if($dw>5){
//	echo '<script type="text/javascript">'; 
//	echo 'alert("saat ini portfolio sedang dalam proses kalkulasi,\nstatus portfolio bisa dilihat kembali di hari kerja jam 08.15 WIB pagi");'; 
//	echo 'window.location.href = "index.php";';
//	echo '</script>';
}
$pagi = "08:14:59";
$sore = "17:30:00";
if(time() > strtotime($sore) || time() < strtotime($pagi) ){
//	echo '<script type="text/javascript">'; 
//	echo 'alert("saat ini portfolio sedang dalam proses kalkulasi,\nstatus portfolio bisa dilihat kembali di jam 08.15 WIB pagi");'; 
//	echo 'window.location.href = "index.php";';
//	echo '</script>';
}
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
		var ordVal = 0;
		var t_lv = 0;
		var g_curRatio = 0.0;
		var g_potRatio = 0.0;
				
		function vol2lot(qty) {
			return qty / 100;
		}
		
		function drawHeaderPortfolio(cell_v) {
			rows = [];
			row = {id:0, cell:cell_v};
			rows.push(row);
			args = {dataType: "json", rows: rows, page: 1, total: 1};
			$('#r-headportfolio').flexAddData(args);
		}

		//initPage
		$(document).ready(function() {			
			//r-headportfolio
			hport = {
				title: "Portfolio",
				dataType: "json",
				height: "auto",
				colModel: [
					{display: "RDI", width:60, sortable: false, align: "center"},
					{display: "AR/AP", width:60, sortable: false, align: "center"},
					{display: "Open Buy", width:80, sortable: false, align: "center"},
					{display: "Cash", width:60, sortable: false, align: "center"},
					{display: "Trd Limit", width:80, sortable: false, align: "center"},
					{display: "Cur.R", width:40, sortable: false, align: "center"},
					{display: "Pot.R", width:40, sortable: false, align: "center"}
				],
				buttons : [
					{name:'Refresh', bclass:'refresh', onpress:refreshAll},
					{separator: true}
				]
			};
			$('#r-headportfolio').flexigrid(hport);

			cell_v = [
				"", "", "", "", "", "", "", "", "", ""
				];
			drawHeaderPortfolio(cell_v);

			args = {
				//title : "Portfolio",
				dataType : "json",
				height : "auto",
				colModel : [
					{display:"Code", width:60, sortable:false, align:'center'},
					{display:"Lot", width:30, sortable:false, align:'right'},
					{display:"Shares", width:60, sortable:false, align:'right', hide:'true'},
					{display:"Buy Prc", width:60, sortable:false, align:'right'},
					{display:"Last Prc", width:60, sortable:false, align:'right'},
					{display:"Buy Val", width:100, sortable:false, align:'right', hide:'true'},
					{display:"Market Val", width:80, sortable:false, align:'right'},
					{display:"Haircut", width:30, sortable:false, align:'right'},
					{display:"Stock Val", width:80, sortable:false, align:'right'},
					{display:"Unrealized", width:80, sortable:false, align:'right'},
					{display:"(%)", width:40, sortable:false, align:'right'},
					{display:"Done (B)", width:30, sortable:false, align:'right'},
					{display:"Done (S)", width:30, sortable:false, align:'right'},
					{display:"Open (B)", width:30, sortable:false, align:'right'},
					{display:"Open (S)", width:30, sortable:false, align:'right'}
				]
			};
			$('#r-portfolio').flexigrid(args);

			if (<?=$pinState;?> == 1) {
				refreshAll(); 
				tr800300(<?=$userId;?>, <?=$pinLogin;?>);
			}
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
							jam = "03.15";
							if (curDate.getHours()>6){
								jam = "16.25";
							}
							alert("Portfolio saat ini sedang dalam proses kalkulasi\nbisa diakses kembali pada pukul "+ jam);
							location.href = "index.php?uFlag=1";
						}
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
						console.log(jsRet.out);
						
						rows = [];
						cnt = jsRet.out.length;
						var ops_exist = 0;
						var color = [];
						color["up"] = [];
						color["down"] = [];
						//
						t_lv=0;

						for (i = 0; i < cnt; i ++) {
							id_v = i;
							//
							jsRet.out[i].lqVal = Number(jsRet.out[i].mktVal) * (1.0 - Number(jsRet.out[i].hairCut));
							jsRet.out[i].lot = vol2lot(jsRet.out[i].balance);
							jsRet.out[i].hairCut = Number(jsRet.out[i].hairCut) * 100.0;
							ops = sprintf("%.0f", Number(jsRet.out[i].offer));
							code = jsRet.out[i].stkID;
							lot = jsRet.out[i].lot;
							shares = jsRet.out[i].balance;
							bprc = sprintf("%.2f", Number(jsRet.out[i].avgPrice));
							lprc = jsRet.out[i].mktPrice;
							//bval = sprintf("%.2f", Number(jsRet.out[i].stkVal));
							bval = sprintf("%.2f", ((Number(shares) + (Number(ops) * 100))* Number(bprc))); //buying value
							mval = jsRet.out[i].mktVal;

							if (shares == 0) {
								jsRet.out[i].profitVal = Number(jsRet.out[i].balance) - Number(bval);
							}else {
								jsRet.out[i].profitVal = (Number(shares)*Number(lprc)) - (Number(shares)*Number(bprc));
							}

							$check = Number(jsRet.out[i].stkVal) * 100.0;

							if ($check < 1) {
								jsRet.out[i].profitRate = "0.00";
							} else {
								jsRet.out[i].profitRate = jsRet.out[i].profitVal / Number(jsRet.out[i].stkVal) * 100.0;
							}

							hc = jsRet.out[i].hairCut;
							sval = sprintf("%.2f", Number(jsRet.out[i].lqVal));
							unrl = parseFloat(jsRet.out[i].profitVal).toFixed(0);
							prcnt = parseFloat(jsRet.out[i].profitRate).toFixed(2);
							dnb = sprintf("%.0f", Number(jsRet.out[i].buy));
							dns = sprintf("%.0f", Number(jsRet.out[i].sell));
							opb = sprintf("%.0f", Number(jsRet.out[i].bid));

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
							if (lot==0){
								unrl = 0.0;
							}

							cell_v = [
								/* "S", */
								code,
								Math.floor(lot),
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
							
							t_lot = jsRet.out[i].lot;
							t_bal = jsRet.out[i].balance;
							t_price = jsRet.out[i].mktPrice;
							t_hc = jsRet.out[i].hairCut;
							t_offer = jsRet.out[i].offer;

							if(jsRet.out[i].offer.length==0){
								t_offer = 0.0;
							}
							lv = (t_price*(parseInt(t_bal)+(parseInt(t_offer)*100))*(100-t_hc)/100);
							//console.log(lv+"=("+t_price+"*("+t_bal+"+("+parseInt(t_offer)+"*"+100+"))*("+100+"-"+t_hc+")/"+100+")");
							//console.log("t_lv="+t_lv);
							t_lv+=lv;
							//console.log("t_lv="+t_lv);
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

		function refreshAll() {
			totLq = 0;
			ordVal = 0;
			//qryTr800017();
			qryTr182600();							// orderlist, open order value
			qryTr800011();							// cRat, pRat
			qryTr800001();							// account stock
			setTimeout(qryTr800000, 5000);			// cash : rdi, arap, openbuy, cash, trd limit, cRat, pRat
			//setTimeout(refreshAll, 5000);
		}

		function qryTr800017(){
			tr800017();
		}

		function qryTr182600() {
			var userId = "<?=$userId;?>";
			var now = new Date();
			nowStr = now.format("yyyymmdd");
			$.mobile.loading('show');
			tr182600(userId, nowStr);
		}
		
		function qryTr800001() {
			var userId = "<?=$userId;?>";
			var now = new Date();
			nowStr = now.format("yyyymmdd");
			$.mobile.loading('show');
			tr800001(userId, nowStr);
		}

		function qryTr800000() {
			var userId = "<?=$userId;?>";
			var clientId = "<?=$clientId?>";
			var now = new Date();
			nowStr = now.format("yyyymmdd");
			$.mobile.loading('show');
			tr800000(userId, clientId, nowStr); 
		}
		
		function qryTr800011() {
			var userId = "<?=$userId;?>";
			$.mobile.loading('show');
			tr800011(userId)
		}

		function tr800011(user) {
			$.ajax({
				type: "POST",
				url: "json/trproc.php",
				dataType: "json",
				data: "tr=800011&userId=" + user + "&pin=" + "1234567890"
				+ "&code=" + "SRIL" + "&price=" + "0" + "&lot=" + "0" + "&oldOrdNo=" + "0",
				success: function(jsRet) {
					if (jsRet.status != 1) {
						alert(jsRet.mesg);
					} else {
						cnt = jsRet.out.length;
						if ( cnt > 0 ) {
                            console.log(jsRet.out);

							g_curRatio = sprintf("%.2f", Number(jsRet.out[0].curRatio) * 100);
							g_potRatio = sprintf("%.2f", Number(jsRet.out[0].potRatio) * 100);
						}
					}
					$.mobile.loading('hide');
				},
				error: function(data, status, err) {
					$.mobile.loading('hide');
					console.log("error tr800011 : "+data);
				}
			});		
		}

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
					    console.log(jsRet.out);	
						
						rows = [];
						//
						rdi = sprintf("%.0f", Number(jsRet.out[0].rdn));
						arap = sprintf("%.0f", Number(jsRet.out[0].cashBalance) - Number(rdi));
						openbuy = sprintf("%.0f", Number(jsRet.out[0].bid));
						cash = sprintf("%d", Number(jsRet.out[0].cashBalance) - Number(openbuy));
						debt = Number(jsRet.out[0].cashBalance) - Number(openbuy);
						asset = t_lv + ordVal;
						trdlimit = sprintf("%.0f", (debt * 2.5) + (asset * 1.5));
						console.log("asset="+ t_lv + " + "+ ordVal );
						debt >= 0 ? g_potRatio = "0.00" : g_potRatio;
						/*
						console.log(Number(jsRet.out[0].cashBalance) + "|" + openbuy + "|" + totLq + "|" + ordVal);
						console.log("potratio=> "+potratio);
						*/
						arap == "-0" ? arap = "0" : arap;
						cell_v = [
							addCommas(rdi),
							addCommas(arap),
							addCommas(openbuy),
							addCommas(cash),
							addCommas(trdlimit),
							g_curRatio + "%",
							g_potRatio + "%",
						];
						drawHeaderPortfolio(cell_v);
					}

					var r = document.getElementById("r-headportfolio").getElementsByTagName('tr');
					var c = r[0].getElementsByTagName('td');
					arapVal = c[1].innerText;
					if (Number(arapVal) != 0) {
					//	jQuery( c[1] ).css("color", "#ff0000");
					}
					$.mobile.loading('hide');
				},
				error: function(data, status, err) {
					$.mobile.loading('hide');
					console.log("error forward : "+data);
				}
			});		
		}
		//
		
		// Order List
		function tr182600(id, date) {
			$.ajax({
				type: "POST",
				url: "json/trproc.php",
				dataType: "json",
				//data: sprintf("tr=182600&userId=%s&cliId=%s&pin=&code=&board=&bsFlag=%s&status=%s&from=%s&to=%s&cnt=500&keyDate=&keyOrderNo=999999999&price=",id, id, 0, status, date, date),
				data: sprintf("tr=182600&userId=%s&cliId=%s&pin=&code=&board=&bsFlag=%s&status=%s&from=&to=&cnt=500&keyDate=&keyOrderNo=999999999&price=",id, id, "0", "0"),
				success: function(jsRet) {
					if (jsRet.status != 1) {
						alert(jsRet.mesg);
					} else {
						console.log(jsRet.out);
						cnt = jsRet.out.length;

						for (i = 0; i < cnt; i++) {
							if ((jsRet.out[i].status == "O" || jsRet.out[i].status == "N")
								&& jsRet.out[i].type == "B") {
								price = jsRet.out[i].price;
								qty = jsRet.out[i].qty;
								code = jsRet.out[i].code;
								hc = jsRet.out[i].hairCut;
								lqRate = 1.00 - Number(hc);

								ordVal = ordVal + Number(price) * Number(qty) * Number(lqRate);
							}
						}
					}
					$.mobile.loading('hide');
				},
				error: function(data, status, err) {
					$.mobile.loading('hide');
					console.log("error : tr182600 : "+data);
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
					$.mobile.loading('hide');
				},
				error: function(data, status, err) {
					$.mobile.loading('hide');
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
