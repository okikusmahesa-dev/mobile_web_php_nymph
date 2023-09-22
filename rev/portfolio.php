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
	<style>
		table.tbMob{
			display: none;
		}
		#name{
			max-width:140px;
		}
		td.r-cell{
			border-right:1px solid #dee2e6;
		}
		td.r-qty{
			border-right:1px solid #dee2e6;
		}
		td.r{
			border-right:1px solid #dee2e6;
		}
		td.cl-1{
			background-color:#f7f7f7;
		}
		td.cl-3{
			background-color:#f7f7f7;
		}
		@media only screen and (max-width: 600px) {
			table.tbMob{
				display: inline-table;
			}
			table.tbDks{
				display: none;
			}
			.right{
			text-align:right;
			}
			#name{
				max-width:143px;
				vertical-align:inherit;
				padding:2px;
				text-align:center;
			}
			td.cl-1{
				max-width:143px;
				vertical-align:inherit;
				padding:2px;
				text-align:center;
			}
			td.cl-3{
				max-width:143px;
				vertical-align:inherit;
				padding:2px;
				text-align:center;
			}
			td.nama{
				text-align:center;
			}
		}
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

		//initPage

		
		$(document).ready(function() {	
			if (window.matchMedia("(max-width: 1090px)").matches) {
				var screen = window.innerHeight;
				var tinggi = ""+(screen-480)+"px";
				args = {
				paging: false,
				searching: false,
				ordering:  false,
				"info": false,
				scrollX: true,
				scrollY: tinggi,
				fixedColumns:   true,
				"columnDefs": [
				{ "title": "Code", "targets": 0, className: 'dt-body-right' },
				{ "title": "Lot", "targets": 1, className: 'dt-body-right' },
				{ "title": "Shares", "targets": 2, className: 'dt-body-right' },
				{ "title": "Avg Buying", "targets": 3, className: 'dt-body-right' },
				{ "title": "Last Price", "targets": 4, className: 'dt-body-right' },
				{ "title": "Buy Value", "targets": 5, className: 'dt-body-right' },
				{ "title": "Market Value", "targets": 6, className: 'dt-body-right' },
				{ "title": "Haircut", "targets": 7, className: 'dt-body-right' },
				{ "title": "Stock Value", "targets": 8, className: 'dt-body-right' },
				{ "title": "Unrealized", "targets": 9, className: 'dt-body-right' },
				{ "title": "(%)", "targets": 10, className: 'dt-body-right' },
				{ "title": "Done (B)", "targets": 11, className: 'dt-body-right' },
				{ "title": "Done (S)", "targets": 12, className: 'dt-body-right' },
				{ "title": "Open (B)", "targets": 13, className: 'dt-body-right' },
				{ "title": "Open (S)", "targets": 14, className: 'dt-body-right' }
				],

				columns : [
					{ data: 'code' },
					{ data: 'lot' },
					{ data: 'shares' },
					{ data: 'bprc' },
					{ data: 'lprc' },
					{ data: 'bval' },
					{ data: 'mval' },
					{ data: 'hc' },
					{ data: 'sval' },
					{ data: 'unrl' },
					{ data: 'prcnt' },
					{ data: 'dnb' },
					{ data: 'dns' },
					{ data: 'opb' },
					{ data: 'ops' }
				]
			};
			} else {
				args = {
				paging: false,
				searching: false,
				ordering:  false,
				"info": false,
				scrollX: false,
				fixedColumns:   true,
				"columnDefs": [
				{ "title": "Code", "targets": 0, className: 'dt-right' },
				{ "title": "Lot", "targets": 1, className: 'dt-right' },
				{ "title": "Shares", "targets": 2, className: 'dt-right' },
				{ "title": "Avg Buying", "targets": 3, className: 'dt-right' },
				{ "title": "Last Price", "targets": 4, className: 'dt-right' },
				{ "title": "Buy Value", "targets": 5, className: 'dt-right' },
				{ "title": "Market Value", "targets": 6, className: 'dt-right' },
				{ "title": "Haircut", "targets": 7, className: 'dt-right' },
				{ "title": "Stock Value", "targets": 8, className: 'dt-right' },
				{ "title": "Unrealized", "targets": 9, className: 'dt-right' },
				{ "title": "(%)", "targets": 10, className: 'dt-right' },
				{ "title": "Done (B)", "targets": 11, className: 'dt-right' },
				{ "title": "Done (S)", "targets": 12, className: 'dt-right' },
				{ "title": "Open (B)", "targets": 13, className: 'dt-right' },
				{ "title": "Open (S)", "targets": 14, className: 'dt-right' }
				],

				columns : [
					{ data: 'code' },
					{ data: 'lot' },
					{ data: 'shares' },
					{ data: 'bprc' },
					{ data: 'lprc' },
					{ data: 'bval' },
					{ data: 'mval' },
					{ data: 'hc' },
					{ data: 'sval' },
					{ data: 'unrl' },
					{ data: 'prcnt' },
					{ data: 'dnb' },
					{ data: 'dns' },
					{ data: 'opb' },
					{ data: 'ops' }
				]
			};
			}

			
			$('#table_id').DataTable(args);
			

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
							//console.log("stkType ="+stkType+ " code "+code);				
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
							var data = [
								{
								"code":   code,
								"lot":     Math.floor(lot),
								"shares": addCommas(shares),
								"bprc":     addCommas(bprc),
								"lprc":     addCommas(lprc),
								"bval":     addCommas(bval),
								"mval":     addCommas(mval),
								"hc":     	hc,
								"sval":     addCommas(sval),
								"unrl":     addCommas(unrl),
								"prcnt":     prcnt,
								"dnb":     dnb,
								"dns":     dns,
								"opb":     opb,
								"ops":     ops
								} 
							]	

							var table = $('#table_id').DataTable();
							table.rows.add(data).draw();
							
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
						//qryTr800000();			// cash : rdi, arap, openbuy, cash, trd limit, cRat, pRat
						//console.log("totalMktVal :"+totalMktVal);
						console.log("g_t_lv = " + g_t_lv);
						
						for (i=0;i<color["down"].length;i++) {
							var r = document.getElementById("table_id").getElementsByTagName('tr');
							var c = r[color["down"][i]].getElementsByTagName('td');
							jQuery( c[6] ).css("color", "#ff0000");
							jQuery( c[9] ).css("color", "#ff0000");
							jQuery( c[10] ).css("color", "#ff0000");
						}
						for (i=0;i<color["up"].length;i++) {
							var r = document.getElementById("table_id").getElementsByTagName('tr');
							var c = r[color["up"][i]].getElementsByTagName('td');
							jQuery( c[6] ).css("color", "#0000ff");
							jQuery( c[9] ).css("color", "#0000ff");
							jQuery( c[10] ).css("color", "#0000ff");
						}

						var totalNet = Number(totalMktVal) + Number(g_cash);
							$("#totBuyVal").text(addCommas(totalBuyVal));
							$("#totMktVal").text(addCommas(totalMktVal));
							$("#unrealized").text(addCommas(unrealized));
							$("#totStkVal").text(addCommas(totalStock));
							$("#totNet").text(addCommas(totalNet));

							$("#totBuyVal1").text(addCommas(totalBuyVal));
							$("#totMktVal1").text(addCommas(totalMktVal));
							$("#unrealized1").text(addCommas(unrealized));
							$("#totStkVal1").text(addCommas(totalStock));
							$("#totNet1").text(addCommas(totalNet));
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
			qryTr800000();			            // cash : rdi, arap, openbuy, cash, trd limit, cRat, pRat
			qryTr800005();							// Trade Over Due
			
			qryTr800011();							// cRat, pRat
			qryTr182600();							// orderlist, open order value
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
			tr182600(userId, nowStr);
		}
		
		function qryTr800001() {
			var userId = "<?=$userId;?>";
			var now = new Date();
			nowStr = now.format("yyyymmdd");
			tr800001(userId, nowStr);
		}

		function qryTr800000() {
			var userId = "<?=$userId;?>";
			var clientId = "<?=$clientId?>";
			var now = new Date();
			nowStr = now.format("yyyymmdd");
		
			tr800000(userId, clientId, nowStr); 
			//setTimeout(qryTr800000, 5000);			// cash : rdi, arap, openbuy, cash, trd limit, cRat, pRat
		}
		
		function qryTr800011() {
			var userId = "<?=$userId;?>";
			tr800011(userId)
			//setTimeout(qryTr800011, 5000);			// cash : rdi, arap, openbuy, cash, trd limit, cRat, pRat
		}
		function qryTr800005() {
			 var clientID = "<?=$userId;?>"
			// console.log("cek clientId ="+clientID);	
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
				},
				error: function(data, status, err) {
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
				},
				error : function(data, status, err){
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
					    console.log("jsRet 800000 = "+ jsRet.out[0].rdn);	
						
						rows = [];
						rdi = sprintf("%.0f", Number(jsRet.out[0].rdn));
						arap = sprintf("%.0f", Number(jsRet.out[0].cashBalance) - Number(rdi));
						openbuy = sprintf("%.0f", Number(jsRet.out[0].bid));
						cash = sprintf("%d", Number(jsRet.out[0].cashBalance) - Number(openbuy));
						debt = Number(jsRet.out[0].cashBalance) - Number(openbuy);
						asset = g_t_lv + g_ordVal;
						trdlimit = sprintf("%.0f", (debt * 2.5) + (asset * 1.5));
						//totalbuy = jsRet.out[i].lqVal;
						console.log("debt="+ debt);
						console.log("asset="+ g_t_lv + " + "+ g_ordVal );
						console.log("trdlimit="+ trdlimit);

						debt >= 0 ? g_potRatio = "0.00" : g_potRatio;
						/*
						console.log(Number(jsRet.out[0].cashBalance) + "|" + openbuy + "|" + totLq + "|" + ordVal);
						console.log("potratio=> "+potratio);
						*/
						arap == "-0" ? arap = "0" : arap;
						$("#rdi").text(addCommas(rdi));
						$("#rdi1").text(addCommas(rdi));
						$("#arap").text(addCommas(arap));
						$("#arap1").text(addCommas(arap));
						$("#opbuy").text(addCommas(openbuy));
						$("#opbuy1").text(addCommas(openbuy));
						$("#cash").text(addCommas(cash));
						$("#cash1").text(addCommas(cash));
						$("#buylimit").text(addCommas(trdlimit));
						$("#buylimit1").text(addCommas(trdlimit));
				
						g_cash = cash;
						qryTr800001();							// account stock
					}
	
				},
				error: function(data, status, err) {
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
				},
				error: function(data, status, err) {
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
<body>
	<form method="POST">
		<div class="container" data-cache="never">
			<div class="wrapper">
				<!-- Sidebar  -->
				<?php include "sidebar.php"?>
				<!-- Page Content  -->
				<div id="content">
					<?php include "page-header.php"?>
					<div class="row" style="margin-right: 0; margin-left: 0; padding-bottom: 60px;">
						<div class="col-sm">
							<div class="card card-default">
								<div class="card-header" style="padding : .0rem; margin-top:-1px;margin-right:-1px;">
									<div class="table-responsive">
										<table class="table" style="margin-bottom:0">
										  <thead>
										  </thead>
										  <tbody>
											<tr>
												<td class="r-cell nama"><?=strtoupper($clientId)?></td>
												<td class="r-cell right" id="tdate1"></td>
												<td class="r-cell right" id="tdate2"></td>
											</tr>
											<tr style="background-color : #fff;">
												<td id="name" class="r-qty"></td>
												<td id="t1" class="r-qty right"></td>
												<td id="t2" class="r-qty right"></td>
											</tr>
										  </tbody>
										</table>
									</div>
							      </div>
								  <div class="card-body" style="padding : 0; margin-top:-1px;margin-right:-1px;">
								  	<div class="table-responsive">
										<table class="table tbDks" style="margin-bottom:0">
										  <thead>
										  </thead>
										  <tbody>
											<tr>
												<td class="cl-1 r">RDI</td>
												<td class="cl-1 r">AR/AP</td>
												<td class="cl-1 r">Open Buy</td>
												<td class="cl-1 r">Cash Available</td>
												<td class="cl-1 r">Buying Limit</td>
												
												
												
											</tr>
											<tr>
												<td id="rdi" class="r right"></td>
												<td id="arap" class="r right"></td>
												<td id="opbuy" class="r right"></td>
												<td id="cash" class="r right"></td>
												<td id="buylimit" class="r right"></td>
												
												
											</tr>
											<tr>
												<td class="cl-3 r">Total Buy Val</td>
												<td class="cl-3 r">Total Mkt Val</td>
												<td class="cl-3 r">Unrealized</td>
												<td class="cl-3 r">Total Stk Val</td>
												<td class="cl-3 r">Net Assets</td>
												
											</tr>
											<tr>
												<td id="totBuyVal" class="r right"></td>
												<td id="totMktVal" class="r right"></td>
												<td id="unrealized" class="r right"></td>
												<td id="totStkVal" class="r right"></td>
												<td id="totNet" class="r right"></td>
											</tr>
										  </tbody>
										</table>

										<!-- ---------------------------------- -->

										<table class="table tbMob" style="margin-bottom:0">
										  <thead>
										  </thead>
										  <tbody>
											<tr>
												<td class="cl-1 r">RDI</td>
												<td id="rdi1" class="r right"></td>
												<td class="cl-3 r">Total Buy Val</td>
												<td id="totBuyVal1" class="r right"></td>
											</tr>
											<tr>
												<td class="cl-1 r">AR/AP</td>
												<td id="arap1" class="r right"></td>
												<td class="cl-3 r">Total Mkt Val</td>
												<td id="totMktVal1" class="r right"></td>
											</tr>
											<tr>
												<td class="cl-1 r">Open Buy</td>
												<td id="opbuy1" class="r right"></td>
												<td class="cl-3 r">Unrealized</td>
												<td id="unrealized1" class="r right"></td>
											</tr>
											<tr>
												<td class="cl-1 r">Cash Available</td>
												<td id="cash1" class="r right"></td>
												<td class="cl-3 r">Total Stk Val</td>
												<td id="totStkVal1" class="r right"></td>
											</tr>
											<tr>
												<td class="cl-1 r">Buying Limit</td>
												<td id="buylimit1" class="r right"></td>
												<td class="cl-3 r">Net Assets</td>
												<td id="totNet1" class="r right"></td>
											</tr>
										  </tbody>
										</table>
									</div>
								</div>
								<div class="card-footer" style="padding:0;">
									<div class="table-responsive">
										<table id="table_id" class="table" style="font-size:12px;">
											<thead>
											</thead>
											<tbody style="font-size:12px;">
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>


					</div>					
					<footer class="fixed-bottom">
						<div class="card text-white bg-info">
							<div class="card-header" style="text-align: center;max-height: 50px;">
								Footer
							</div>
						</div>
					</footer>
				</div>
			</div>			
			<div class="overlay"></div>
		</div>
	</form>
</body>
</html>
