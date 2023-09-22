<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");

session_start();
/*
print_r($_SESSION);
if (!isset($_SESSION["isLogin"]) || $_SESSION["isLogin"] != 1) {
	echo "Please login...";
	header("refresh:3; url=login.php");
	exit(0);
}
*/
	echo '<script type="text/javascript">setTimeout(function() { window.location.href = "https://mobile.bcasekuritas.co.id/tlogout.php"; }, 15 * 60000);</script>';
$userId = 2692;
?>
<html>
<head> 
	<title>Running</title> 
	<meta name="viewport" content="width=device-width, initial-scale=1"> 
	<link rel="stylesheet" type="text/css" href="./css/favorite.css?20130314a" />
	<? include "inc.php" ?>
	<style type="text/css">
	<!--
	//-->
	</style>
	<script type="text/javascript">
		var lastMktNo = "";
		var rows = [
			{}, {}, {}, {}, {}, {}, {}, {}, {}, {},
			{}, {}, {}, {}, {}, {}, {}, {}, {}, {},
		];
		var rowIdx = 0;
		function vol2lot(qty) {
			return qty / 100;
		}
		function tr120001(mktNo) {
			console.log(mktNo);
			$.ajax({
				type: "POST",
				url: "json/trproc.php",
				dataType : "json",
				data: "tr=120001&mktNo=" + mktNo,
				success: function(jsRet){
					if (jsRet == null) tr120001("0");
					if (jsRet.status != 1) {
						alert("err=" + jsRet.mesg);
					} else {
						//alert( "jsRet=" + JSON.stringify(jsRet) );
						cnt = jsRet.trade.length;
						if (cnt == 0) return;
						for (i = 0; i < cnt; i ++) {
							rowIdx ++;
							if (rowIdx >= 20) rowIdx = 0;
							id_v = "id-" + rowIdx;
							lastMktNo = jsRet.trade[i]["mktNo"];
							if (jsRet.trade[i]["boardcode"] != "RG") {
								code = jsRet.trade[i]["KeyField"] + "." + jsRet.trade[i]["boardcode"];
							}
							else {
								code = jsRet.trade[i]["KeyField"];
							}
							// fill data
							cell_v = [
								jsRet.trade[i]["time"].replace(/(\w{2,2})(\w{2,2})(\w{2,2})/, '$1:$2:$3'),
								code,
								addCommas(jsRet.trade[i]["price"]),
								//jsRet.trade[i]["changecolor"],
								addCommas(jsRet.trade[i]["chg"]),
								jsRet.trade[i]["chgR"],
								addCommas(vol2lot(jsRet.trade[i]["lasttick"])),
								jsRet.trade[i]["buyercode"],
								jsRet.trade[i]["buyertype"],
								jsRet.trade[i]["sellertype"],
								jsRet.trade[i]["sellercode"],
								jsRet.trade[i]["mktNo"],
							];
							row = {id:id_v, cell:cell_v, };
							rows[rowIdx] = row;
						}
						args = {dataType:"json", rows:rows, page:1, total:cnt};
						$("#r-running").flexAddData(args);
						/*
						$("#rowid-1").attr("idx", "1");
						$("#rowid-1 > td:nth-child(1)").attr("idx", "11");
						$("#rowid-1 > td:nth-child(1) > div").css("color", "red");
						*/
						//
						setTimeout(qryData, 2000);
						//$("#r-running").$("#id-" + rowIdx).addClass("trSelected");
					}
				},
				error: function(data, status, err) {
					console.log("error forward : "+data);
					//alert('error communition with server.');
				}
			});
			$.mobile.loading('hide');
		}
		$(document).ready(function() {
			jQuery('div').live('pagehide', function(event, ui){
				var page = jQuery(event.target);
				if(page.attr('data-cache') == 'never'){
				page.remove();
				};
			});
			// init fist grid
			cnt = 0;
			rows = [];
			args = {
				title : "Running Trade",
				dataType : "json",
				height : "auto",
				singleSelect : true,
				onSuccess : running_onSuccess,
				colModel : [
					{display:"Time", width:50, sortable:false, align:'center'},
					{display:"Code", width:50, sortable:false, align:'center'},
					{display:"Price", width:50, sortable:false, align:'right'},
					{display:"Chg", width:40, sortable:false, align:'right'},
					{display:"(%)", width:40, sortable:false, align:'right'},
					{display:"Volume", width:50, sortable:false, align:'right'},
					{display:"Buyer", width:50, sortable:false, align:'right'},
					{display:"B.T", width:30, sortable:false, align:'center'},
					{display:"S.T", width:30, sortable:false, align:'center'},
					{display:"Seller", width:50, sortable:false, align:'right'},
				],
				buttons : [
					{name:'Refresh', bclass:'refresh', onpress: initPage},
					{separator: true}
				]
			};
			$('#r-running').flexigrid(args);
			setTimeout(initPage, 1000);
		});

		function initPage() {
			//setRunning();
			$.mobile.loading('show');
			tr120001("0");
		}

		function qryData() {
			tr120001(lastMktNo);
		}

		function running_onSuccess() {
		/*
			$("#rowid-" + rowIdx).addClass("trSelected");
			$("#rowid-7 > td:nth-child(5) > div").css("color", "red");
			$("#rowid-7 > td:nth-child(6) > div").css("color", "red");
			for (i = 0; i < cnt; i ++) {
				rowIdx ++;
				if (rowIdx >= 20) rowIdx = 0;
				id_v = "rowid-" + rowIdx;
				if (Number(jsRet.trade[i]["chgR"]) >= 0) {
					$("#" + id_v + " > td:nth-child(5) > div").css("color", "blue");
				}
				else {
					$("#" + id_v + " > td:nth-child(5) > div").css("color", "red");
				}
			}
		*/
			$('#r-running tr').each( function(){ 
				// rate
				var cell = $('td:nth-child(5) > div', this); 
				var cell2 = $('td:nth-child(4) > div', this); 
				var cell3 = $('td:nth-child(3) > div', this); 
				var val = cell.text();
				if (Number(val) > 0) {
					cell.css("color", "blue");
					cell2.css("color", "blue");
					cell3.css("color", "blue");
				}
				else if (Number(val) < 0){
					cell.css("color", "red");
					cell2.css("color", "red");
					cell3.css("color", "red");
				}

				// Buyer
				var cell = $('td:nth-child(8) > div', this); 
				var cell2 = $('td:nth-child(7) > div', this); 
				var val = cell.text();
				if (val == "D") {
					cell.css("color", "blue");
					cell2.css("color", "blue");
				}
				else {
					cell.css("color", "red");
					cell2.css("color", "red");
				}
	
				// Seller
				var cell = $('td:nth-child(9) > div', this); 
				var cell2 = $('td:nth-child(10) > div', this); 
				var val = cell.text();
				if (val == "D") {
					cell.css("color", "blue");
					cell2.css("color", "blue");
				}
				else {
					cell.css("color", "red");
					cell2.css("color", "red");
				}		
			}); 
		}

		function setRunning() {
			cnt = 10;
			rows = [];
			for (i = 0; i < cnt; i ++) {
				id_v = "id" + i;
				cell_v = [ "", "", "", "", "",
					"", "", "", "", "", "",
					];
				row = {id:id_v, cell:cell_v};
				rows.push(row);
			}
			args = {dataType:"json", rows:rows, page:1, total:cnt};
			//alert( "args=" + JSON.stringify(args) );
			//alert( "args=" + jQuery.toJSON(args));
			$('#r-running').flexAddData(args);
			//$('#r-running').flexReload();
		}
	</script>
</head> 
<body style="backgound:#ffffff;">
<div data-role="page" id="home" data-cache="never">
<? include "ph.php" ?>
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
