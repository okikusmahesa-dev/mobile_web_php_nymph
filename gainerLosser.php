<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");
//require_once("json/cekping.php");
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
	<title>Top Gainers Lossers</title> 
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

			$('input:radio[name="radio-choice-1"]').change(function() {
				if($(this).val() == 'Gainer') {
					qryTr130000("3");
				} else if($(this).val() == 'Losser') {
					qryTr130000("6");
				}
			});

			args = {
				title : "Top Gainers Lossers",
				dataType : "json",
				height : "auto",
				onSuccess : onSuccess,
				colModel : [
					{display:"Code", width:50, sortable:false, align:'center'},
					{display:"LastPrc", width:50, sortable:false, align:'right'},
					{display:"Change", width:50, sortable:false, align:'right'},
					{display:"(%)", width:30, sortable:false, align:'right'},
					{display:"Value", width:50, sortable:false, align:'right'},
					{display:"Volume", width:50, sortable:false, align:'right'},
					{display:"Freq", width:50, sortable:false, align:'right'},
				],
				buttons : [
					{name:'Refresh', bclass:'refresh', onpress:onRefresh},
					{separator: true}
				]
			};
			$('#r-gainerlosser').flexigrid(args);
			qryTr130000("3");
		});

		function onRefresh() {
			if (document.getElementById("radio-choice-1").checked) {
				qryTr130000("3");
			} else if (document.getElementById("radio-choice-2").checked) {
				qryTr130000("6");
			} else {
				qryTr130000("3");
			}
			console.log("refresh");
		}

		function qryTr130000(type) {
			tr130000(type);
		}

		function tr130000(type) {
			$.ajax({
				type: "POST",
				url: "json/trproc.php",
				dataType : "json",
				data: "tr=130000&type=ALL&type1=" + type + "&fromNo=0&toNo=20",
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
							lastPrc = jsRet.out[i].last;
							chg = jsRet.out[i].chg;
							chgR = jsRet.out[i].chgR;
							val = jsRet.out[i].val;
							vol = jsRet.out[i].vol;
							freq = jsRet.out[i].freq;

							val = miniCurr(val);
							vol = miniCurr(vol);
							lastPrc = addCommas(lastPrc);
							freq = addCommas(freq);

							cell_v = [
								code,
								lastPrc,
								chg,
								chgR,
								val,
								vol,
								freq
								];
							row = {id:id_v, cell:cell_v};
							rows.push(row);
						}
						args = {dataType: "json", rows: rows, page: 1, total: cnt};
						$('#r-gainerlosser').flexAddData(args);
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
			$('#r-gainerlosser tr').each( function(){ 
				// chg(%)
				var cell = $('td:nth-child(4) > div', this); 
				var cell2 = $('td:nth-child(3) > div', this); 
				var cell3 = $('td:nth-child(2) > div', this); 
				var cell4 = $('td:nth-child(5) > div', this); 
				var cell5 = $('td:nth-child(6) > div', this); 
				var cell6 = $('td:nth-child(7) > div', this); 
				var val = cell.text();
				if (Number(val) > 0) {
					cell.css("color", "blue");
					cell2.css("color", "blue");
					cell3.css("color", "blue");
					cell4.css("color", "blue");
					cell5.css("color", "blue");
					cell6.css("color", "blue");
				}
				else if (Number(val) < 0) {
					cell.css("color", "red");
					cell2.css("color", "red");
					cell3.css("color", "red");
					cell4.css("color", "red");
					cell5.css("color", "red");
					cell6.css("color", "red");
				}
			}); 
		}


	</script>
</head> 
<body style="backgound:#ffffff;">
<div data-role="page" id="home" data-cache="never">
	<? include "page-header.php" ?>

	<fieldset data-role="controlgroup">
		<input type="radio" name="radio-choice-1" id="radio-choice-1" value="Gainer" checked="checked" />
		<label for="radio-choice-1">Gainer</label>

		<input type="radio" name="radio-choice-1" id="radio-choice-2" value="Losser"  />
		<label for="radio-choice-2">Losser</label>
	</fieldset>

	<div>
	<table id="r-gainerlosser" class="flexigrid">
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
