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
//$_SESSION["url"]="https://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
	echo '<script type="text/javascript">setTimeout(function() { window.location.href = "https://mobile.bcasekuritas.co.id/tlogout.php"; }, 15 * 60000);</script>';

?>
<html>
<head> 
	<title>Broker Activity</title> 
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<? include "inc-common.php" ?>
	<style type="text/css">
	<? include "css/icon.css" ?>
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
				title : "Broker Activity",
				dataType : "json",
				height : "auto",
				onSuccess : onSuccess,
				colModel : [
					{display:"Code", width:50, sortable:false, align:'center'},
					{display:"BuyVal", width:50, sortable:false, align:'right'},
					{display:"SellVal", width:50, sortable:false, align:'right'},
					{display:"NetVal", width:50, sortable:false, align:'right'},
				],
				buttons : [
					{name:'Refresh', bclass:'refresh', onpress:qryTr140101},
					{separator: true}
				]
			};
			$('#r-broker').flexigrid(args);
			qryTr140101();

			$("#brokerTxt").keyup(function(e){
				if (event.keyCode == 13) {
					code = document.getElementById('brokerTxt').value;
					qryTr140101();
				}
			});
		});

		function qryTr140101() {
			var code = $("#brokerTxt").val();
			tr140101(code);
			console.log("refresh");
		}

		function tr140101(code) {
			today = getToday("yyyymmdd");
			$.ajax({
				type: "POST",
				url: "json/trproc.php",
				dataType : "json",
				data: "tr=140101&code=" + code + "&board=RG&from=" + today + "&to=" + today,
				success: function(jsRet){
					if (jsRet.status != 1) {
						alert(jsRet.mesg);
					} else {
						//console.log(jsRet.out);
						rows = [];
						cnt = jsRet.out.length;
						//
						for (i = 0; i < cnt; i ++) {
							id_v = i;
							//
							code = jsRet.out[i].code;
							buyVal = jsRet.out[i].buyVal;
							sellVal = jsRet.out[i].sellVal;
							netVal = Number(buyVal) - Number(sellVal);

							buyVal = miniCurr(buyVal);
							sellVal = miniCurr(sellVal);
							netVal = miniCurr(netVal);

							cell_v = [
								code,
								buyVal,
								sellVal,
								netVal
								];
							row = {id:id_v, cell:cell_v};
							rows.push(row);
						}
						args = {dataType: "json", rows: rows, page: 1, total: cnt};
						$('#r-broker').flexAddData(args);
						onSuccess();
					}
				},
				error: function(data, status, err) {
					console.log("error forward : "+data);
					alert('error communition with server.');
				}
			});
		}

		function onSuccess() {
			$('#r-broker tr').each( function(){ 
				// bVal
				var cell = $('td:nth-child(2) > div', this); 
				var val = cell.text();
				if (Number(val) != 0) {
					var rgx = val.match(/(.*) (T|M|B)/);
					if (Number(rgx[1]) > 0) {
						cell.css("color", "blue");
					} else if (Number(rgx[1]) < 0) {
						cell.css("color", "red");
					}
				}
				// sVal
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
				// netVal
				var cell = $('td:nth-child(4) > div', this); 
				var val = cell.text();
				if (Number(val) != 0) {
					var rgx = val.match(/(.*) (T|M|B)/);
					if (Number(rgx[1]) > 0) {
						cell.css("color", "blue");
					} else if (Number(rgx[1]) < 0) {
						cell.css("color", "red");
					}
				}
			}); 
		}


	</script>
</head> 
<body style="backgound:#ffffff;">
<div data-role="page" id="home" data-cache="never">
	<? include "page-header.php" ?>
	<div>
	<table border="0" width="100%">
		<tr>
			<td width="10%">
				Broker:
			</td>
			<td width="25%">
				<input id="brokerTxt" type="text" value="SQ" maxlength=2 style="width=100;ime-mode:disabled;text-transform:uppercase;" data-mini="true"/>
			</td>
			<td></td>
			<td align="right" width="25%">
				<!-- <a id="okBtn" href="#" data-role="button" data-icon="refresh" data-mini="true" style="float:right;">Refresh</a><br/> -->
			</td>
		</tr>
		<tr>
			<td></td>
			<td colspan="3" id="brokerName"></td>
		</tr>
	</table>

	<table id="r-broker" class="flexigrid">
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
