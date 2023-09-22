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
	<title>Running</title> 
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<? include "inc-common.php" ?>
	<style type="text/css">
	<!--
	<? include "css/icon.css" ?>
	//-->
	</style>
	<script type="text/javascript">
		$(document).ready(function() {			
			args = {
				title : "Running Trade",
				dataType : "json",
				height : "auto",
				colModel : [
					{display:"Time", width:50, sortable:false, align:'center'},
					{display:"Code", width:50, sortable:false, align:'center'},
					{display:"Price", width:50, sortable:false, align:'right'},
					{display:"Chg", width:50, sortable:false, align:'right'},
					{display:"%", width:30, sortable:false, align:'right'},
					{display:"Vol", width:50, sortable:false, align:'right'},
					{display:"Buyer", width:50, sortable:false, align:'right'},
					{display:"B Type", width:50, sortable:false, align:'right'},
					{display:"S Type", width:50, sortable:false, align:'right'}
				]
			};
			$('#r-running').flexigrid(args);
			qryTr120001();
		});
		
		function qryTr120001() {
			//no input
			tr120001();
		}
		
		var rowIdx = 0;
		
		function tr120001() {
			$.ajax({
				type: "POST",
				url: "json/trproc.php",
				dataType : "json",
				data: "tr=120001&mktNo=0&cnt=15", //15 rows
				success: function(jsRet){
					if (jsRet.status != 1) {
						alert(jsRet.mesg);
					} else {
						rows = [];
						cnt = jsRet.trade.length;
						if (cnt < 1) return;
						//rowIdx ++;
						for (i = 0; i < cnt; i ++) {
							id_v = i;
							//if (rowIdx >= 15) rowIdx = 0;
							//id_v = rowIdx;
							//
							time = jsRet.trade[i].time;
							code = jsRet.trade[i].KeyField;
							price = jsRet.trade[i].price;
							change = jsRet.trade[i].chg;
							prcnt = jsRet.trade[i].chgR;
							volume = jsRet.trade[i].lasttick;
							buyer = jsRet.trade[i].buyercode;
							btype = jsRet.trade[i].buyertype;
							stype = jsRet.trade[i].sellertype;
							//
							cell_v = [
								time.toString(),
								code.toString(),
								price.toString(),
								change.toString(),
								prcnt.toString(),
								volume.toString(),
								buyer.toString(),
								btype.toString(),
								stype.toString()
								];
							row = {id: id_v, cell: cell_v};
							rows.push(row);
						}
						args = {dataType: "json", rows: rows, page: 1, total: cnt};
						$('#r-running').flexAddData(args);
					}
				}
			});
			setTimeout(qryTr120001, 2000);
		}
	</script>
</head> 
<body>
<div data-role="page" id="running" data-cache="never">
<? include "page-header.php" ?>
	<table id="r-running" class="flexigrid">
		<thead>
		</thead>
		<tbody>
		</tbody>
	</table>
<? include "page-footer.php" ?>
</div>
</body>
</html>
