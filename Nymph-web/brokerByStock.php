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

?>
<html>
<head> 
	<title>Broker Transaction by Stock</title> 
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
				title : "Broker Transaction by Stock",
				dataType : "json",
				height : "auto",
				onSuccess : onSuccess,
				colModel : [
					{display:"Broker", width:50, sortable:false, align:'center'},
					{display:"BAvg", width:50, sortable:false, align:'right', hide:true},
					{display:"BVal", width:50, sortable:false, align:'right'},
					{display:"SAvg", width:50, sortable:false, align:'right', hide:true},
					{display:"SVal", width:50, sortable:false, align:'right'},
					{display:"NetVal", width:50, sortable:false, align:'right'},
				],
				buttons : [
					{name:'Refresh', bclass:'refresh', onpress:qryTr100800},
					{separator: true}
				]
			};
			$('#r-broker').flexigrid(args);
			qryTr100800();

			$("#codeTxt").keyup(function(e){
				if (event.keyCode == 13) {
					code = document.getElementById('codeTxt').value;
					qryTr100800();
				}
			});
		});

		


		function qryTr100800() {
			var code = $("#codeTxt").val();
			tr100800(code);
			console.log("refresh");
		}

		function tr100800(code) {
			today = getToday("yyyymmdd");
			$.ajax({
				type: "POST",
				url: "json/trproc.php",
				dataType : "json",
				data: "tr=100800&code=" + code + "&board=RG&from=" + today + "&to=" + today,
				success: function(jsRet){
					if (jsRet.status != 1) {
						alert(jsRet.mesg);
					} else {
						rows = [];
						cnt = jsRet.out.length;
						//
						for (i = 0; i < cnt; i ++) {
							id_v = i;
							//
							code = jsRet.out[i].code;
							buyAvg = Number(jsRet.out[i].buyAvg);
							buyVal = Number(jsRet.out[i].buyVol) * Number(jsRet.out[i].buyAvg);
							sellAvg = Number(jsRet.out[i].sellAvg);
							sellVal = Number(jsRet.out[i].sellVol) * Number(jsRet.out[i].sellAvg);
							netVal = buyVal - sellVal;

							buyAvg = miniCurr(buyAvg);
							buyVal = miniCurr(buyVal);
							sellAvg = miniCurr(sellAvg);
							sellVal = miniCurr(sellVal);
							netVal = miniCurr(netVal);

							//
							cell_v = [
								code,
								buyAvg,
								buyVal,
								sellAvg,
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
				/*
				// bavg
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
				*/
				// bval
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
				/*
				// savg
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
				*/
				// sval
				var cell = $('td:nth-child(5) > div', this); 
				var val = cell.text();
				if (Number(val) != 0) {
					var rgx = val.match(/(.*) (T|M|B)/);
					if (Number(rgx[1]) > 0) {
						cell.css("color", "blue");
					} else if (Number(rgx[1]) < 0) {
						cell.css("color", "red");
					}
				}
				// netval
				var cell = $('td:nth-child(6) > div', this); 
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
			<td width="25%">
				<input id="codeTxt" type="text" value="BBCA" maxlength="7" style="width=100;ime-mode:disabled;text-transform:uppercase;" data-mini="true"/>
			</td>
			<td></td>
			<td></td>
			<td align="right" width="25%">
				<!-- <a id="okBtn" href="#" data-role="button" data-icon="refresh" data-mini="true" style="float:right;">Refresh</a><br/> -->
			</td>
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
