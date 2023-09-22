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
//$userId = "6673";
//$clientId = "6673";
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

$portfCheckBegin = "16:10:00";
$portfCheckEnd = "16:30:00";
$timeNow = date("H:i:s");


if(strtotime($timeNow) > strtotime($portfCheckBegin) && strtotime($timeNow) < strtotime($portfCheckEnd) ){
#	echo '<script type="text/javascript">';
#	echo 'alert("saat ini portfolio sedang dalam proses kalkulasi,\nstatus portfolio bisa dilihat kembali di jam 16:25 WIB");';
#	echo 'window.location.href = "index.php";';
#	echo '</script>';
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
		var g_totLq = 0;
		var g_ordVal = 0;
		var g_t_lv = 0;
		var g_curRatio = 0.0;
		var g_potRatio = 0.0;		
		var g_cell_v = [];
		var g_cell_v1 = [];
		var g_cash = 0.0;
		function vol2lot(qty) {
			return qty / 100;
		}

		function drawHeaderGrid(id_grid, cell_v) {
			rows = [];
			row = {id:0, cell:cell_v};
			rows.push(row);
			args = {dataType: "json", rows: rows, page: 1, total: 1};
			$(id_grid).flexAddData(args);
		}

		//initPage
		$(document).ready(function() {			
			//r-headportfolio
			
			hport = {
				title: "Portfolio",
				dataType: "json",
				height: "auto",
				colModel: [
					{display: "RDI", width:82, sortable: false, align: "center"},
					{display: "AR/AP", width:90, sortable: false, align: "center"},
					{display: "Open Buy", width:85, sortable: false, align: "center"},
					{display: "Cash", width:85, sortable: false, align: "center"},
					{display: "Limit", width:85, sortable: false, align: "center"},
				//	{display: "T1", width:53, sortable: false, align: "center"},
				//	{display: "T2", width:53, sortable: false, align: "center"},
				//	{display: "T3", width:53, sortable: false, align: "center"}
				],
				buttons : [
					{name:'Refresh', bclass:'refresh', onpress:refreshAll},
					{separator: true}
				]
			};

			hport1 = {
				//title : "Portfolio1",
				dataType: "json",
				height: "auto", 
				colModel :[
					{display: "Total Buy", width:82, sortable: false, align: "center"},
					{display: "Total Market", width:90, sortable: false, align: "center"},
					{display: "Unrealized", width:85, sortable: false, align: "center"},
					{display: "Total Stock", width:85, sortable: false, align: "center"},
					//{display: "Limit", width:85, sortable: false, align: "center"},
					//{display: "C. Ratio", width:50, sortable: false, align: "center"},
					//{display: "P. Ratio", width:50, sortable: false, align: "center"},
					{display: "Total Net", width:85, sortable: false, align: "center"}

				],
				//buttons : [
				//	{name:'Refresh', bclass:'refresh', onpress:refreshAll},
				//	{separator: true}
				//]
			};
			$('#r-headportfolio1').flexigrid(hport1);
			$('#r-headportfolio').flexigrid(hport);

			g_cell_v = [
				"", "", "", "", "", "", ""
				];
			g_cell_v1 = [
			    "", "", "", "", "", "", ""
                ];
			
			drawHeaderGrid('#r-headporfolio', g_cell_v);
			drawHeaderGrid('#r-headporfolio1', g_cell_v1);

			args = {
				//title : "PortfolioList",
				dataType : "json",
				height : "auto",
				colModel : [
					{display:"Code", width:60, sortable:false, align:'center'},
					{display:"Lot", width:30, sortable:false, align:'right'},
					{display:"Shares", width:60, sortable:false, align:'right', hide:'true'},
					{display:"Avg Buy Prc", width:95, sortable:false, align:'right'},
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
                // toni 20180906
                // add string to handle user id that start with letter
				tr800300('<?=$userId;?>', <?=$pinLogin;?>);
				refreshAll(); 
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
						console.log("check here"+jsRet.out[0].flag);
						if (jsRet.out[0].flag=="N"){
							
							curDate = new Date();
							jam = "03.15";
							if (curDate.getHours()>6){
								jam = "17.25";
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
						g_t_lv=0;
						totalMktVal = 0;
						totalBuyVal = 0;
						unrealized = 0;
						totalStock = 0;
						for (i = 0; i < cnt; i ++) {
							var stkType = jsRet.out[i].stkType;
						
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
							if (stkType == "" || stkType == "B" || stkType == "D" || stkType == "O") {
								bval = "0.0"
							} else {
								bval = sprintf("%.2f", ((Number(shares) + (Number(ops) * 100))* Number(bprc))); //buying value
							}
							mval = jsRet.out[i].mktVal;

								//totalBuyVal = totalBuyVal +Number(bval);
							totalBuyVal = totalBuyVal +Number(bval);
							totalMktVal = totalMktVal +Number(mval);

							if (shares == 0) {
								jsRet.out[i].profitVal = Number(jsRet.out[i].balance) - Number(bval);
							} else {
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
							if (stkType == "" || stkType == "B" || stkType == "D" || stkType == "O") {
								unrl = "0.0";
							} else {
								unrl = parseFloat(jsRet.out[i].profitVal).toFixed(0);
							}
							prcnt = parseFloat(jsRet.out[i].profitRate).toFixed(2);
							dnb = sprintf("%.0f", Number(jsRet.out[i].buy));
							dns = sprintf("%.0f", Number(jsRet.out[i].sell));
							opb = sprintf("%.0f", Number(jsRet.out[i].bid));
						  
                            if (lot != 0) {	
							    unrealized = unrealized + Number(unrl);
                            }else{
                                unrealized = unrealized;
                            }
							totalStock = totalStock + Number(sval);
							console.log("stkType ="+stkType+ " code "+code);				
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
								g_totLq = 0;
							} else {
								g_totLq = g_totLq + Number(sval) + ops_exist;
							}

							if (Number(mval) > Number(bval)) {
								color["up"].push(i);
							} else if (Number(mval) < Number(bval)) {
								color["down"].push(i);
							}
							if (lot==0){
								unrl = 0.0;
							}

							if(stkType == "R"){
								mval = "0";
								prcnt = "0.00";
								sval = "0.00";
							}
							console.log("unrl "+ unrl+ " code "+code);
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
							g_t_lv += lv;
							//console.log("t_lv="+t_lv);
						}
						//console.log("totalMktVal :"+totalMktVal);
						console.log("g_t_lv = " + g_t_lv);
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

						var totalNet = Number(totalMktVal) + Number(g_cash);
						g_cell_v1 = [
							addCommas(totalBuyVal),
							addCommas(totalMktVal),
							addCommas(unrealized),
							addCommas(totalStock),
							//addCommas(trdlimit),
						//	g_curRatio + "%",
							//g_potRatio + "%",
							addCommas(totalNet)
						];
						drawHeaderGrid('#r-headportfolio1', g_cell_v1);
			            qryTr800000();			// cash : rdi, arap, openbuy, cash, trd limit, cRat, pRat
					}
				}
			});
		}

		function refreshAll() {
		    g_totLq = 0;
		    g_ordVal = 0;
		    g_t_lv = 0;
		    g_curRatio = 0.0;
		    g_potRatio = 0.0;		
		    g_cell_v = [];
		    g_cell_v1 = [];
			qryTr800017();
			qryTr800005();							// Trade Over Due
			qryTr800001();							// account stock
			qryTr800011();							// cRat, pRat
			qryTr182600();							// orderlist, open order value
			//qryTr800000();			            // cash : rdi, arap, openbuy, cash, trd limit, cRat, pRat
			                                        // call after 800001. for calcurating trd limit
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
			//$.mobile.loading('show');
			tr800000(userId, clientId, nowStr); 
			//setTimeout(qryTr800000, 5000);			// cash : rdi, arap, openbuy, cash, trd limit, cRat, pRat
		}
		
		function qryTr800011() {
			var userId = "<?=$userId;?>";
			$.mobile.loading('show');
			tr800011(userId)
			//setTimeout(qryTr800011, 5000);			// cash : rdi, arap, openbuy, cash, trd limit, cRat, pRat
		}
		function qryTr800005() {
			 var clientID = "<?=$userId;?>"
			// console.log("cek clientId ="+clientID);	
			 $.mobile.loading('show');
			 tr800005(clientID);

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

		var tdate=0.0;	
		function tr800005(clientID){
			$.ajax({
				 type: "POST",
				 url: "json/trproc.php",
				 dataType: "json",
				 data: "tr=800005&clientID="+clientID,
				 success: function(jsRet) {
				 	if (jsRet.status != 1) {
				  		alert(jsRet.mesg);
				   	} else {
				    	console.log(jsRet.out);
					
						t1 = sprintf("%.0f", Number(jsRet.out[0].t1));
						t2 = sprintf("%.0f", Number(jsRet.out[0].t2));

						tdate1 = jsRet.out[0].tdate1;
						tdate2 = jsRet.out[0].tdate2;

						
						$('#tdate1').text(convertYYYYMMDD_DDMMYYYY(tdate1, '/'));	
						$('#tdate2').text(convertYYYYMMDD_DDMMYYYY(tdate2, '/'));	

						
						$('#t1').text(addCommas(t1));	
						$('#t2').text(addCommas(t2));

					}
					$.mobile.loading('hide');
				},
				error : function(data, status, err){
					$.mobile.loading('hide');
					console.log("error tr800005 :"+data);
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
					    console.log("jsRet 800000 = "+ jsRet);	
						
						rows = [];
						rdi = sprintf("%d", Number(jsRet.out[0].rdn));
						//rdi = sprintf("%d", Number(jsRet.out[0].rdn));
						arap = sprintf("%.0f", Number(jsRet.out[0].cashBalance) - Number(rdi));
						openbuy = sprintf("%.0f", Number(jsRet.out[0].bid));
						cash = sprintf("%d", Number(jsRet.out[0].cashBalance) - Number(openbuy));
						debt = Number(jsRet.out[0].cashBalance) - Number(openbuy);
						asset = g_t_lv + g_ordVal;
						trdlimit = sprintf("%.0f", (debt * 2.5) + (asset * 1.5));
						//totalbuy = jsRet.out[i].lqVal;
						console.log("RDI="+ rdi);
						console.log("debt="+ debt);
						console.log("asset="+ g_t_lv + " + "+ g_ordVal );
						console.log("trdlimit="+ trdlimit);

						debt >= 0 ? g_potRatio = "0.00" : g_potRatio;
						/*
						console.log(Number(jsRet.out[0].cashBalance) + "|" + openbuy + "|" + totLq + "|" + ordVal);
						console.log("potratio=> "+potratio);
						*/
						arap == "-0" ? arap = "0" : arap;
						g_cell_v[0] = addCommas(rdi);
						g_cell_v[1] = addCommas(arap);
						g_cell_v[2] = addCommas(openbuy);
						g_cell_v[3] = addCommas(cash);
						g_cell_v[4] = addCommas(trdlimit);
						drawHeaderGrid('#r-headportfolio', g_cell_v);
						g_cash = cash;

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

								g_ordVal = g_ordVal + Number(price) * Number(qty) * Number(lqRate);
							}
						}
                         
                        console.log("g_ordVal = " + g_ordVal);
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
		<table data-role="table" class="ui-responsive ui-shadow" id="clientInfo" width="300">
			<thead>
				<tr>
					<th id="clientno" witdh="100"></th>
					<th id="name" align="left"></th>	
				</tr>
			</thead>	
		</table>
		<br>
			<div class="bDiv" style="width:300px;">
				<table data-role="table" class="ui-responsive ui-shadow" id="" width ="100%">
					<thead>
						<tr>
							<td colspan="3" style="text-align:center;">Trade Due</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>	
			<div class="bDiv" style="width:300px;">
				<table data-role="table" class="ui-responsive ui-shadow" id="" width ="100%">
					<tbody>
						<tr class="flexigrid" >
							<td id="tdate1" style="text-align:center;width:100px;"></td>
							<td id="tdate2" style="text-align:center;width:100px;"></td>
						</tr>
						<tr class="flexigrid autoht" >
							<td id="t1" style="text-align:center;width:100px;"></td>
							<td id="t2" style="text-align:center;width:100px;"></td>
						</tr>
					</tbody>
				</table>
			</div>
	</div>
	<div>
		<table id="r-headportfolio" class="flexigrid">
		<thead></thead>
		<tbody></tbody>
		</table>
	</div>
	<div>
		<table id="r-headportfolio1" class="flexigrid">
		<thead></thead>
		<tbody></tbody>
		</table>
	</div>
	<br>
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
