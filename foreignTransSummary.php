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
	<title>Foreign Transaction Summary</title> 
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
				if($(this).val() == 'Buy') {
					qryTr130500("1");
				} else if($(this).val() == 'Sell') {
					qryTr130500("3");
				}
			});

			args = {
				title : "Foreign Transaction Summary",
				dataType : "json",
				height : "auto",
				onSuccess : onSuccess,
				colModel : [
					{display:"Code", width:50, sortable:false, align:'center'},
					{display:"Value", width:50, sortable:false, align:'right'},
					{display:"F.Val", width:50, sortable:false, align:'right'},
					{display:"F.NetVal", width:70, sortable:false, align:'right'},
				],
				buttons : [
					{name:'Refresh', bclass:'refresh', onpress:onRefresh},
					{separator: true}
				]
			};
			$('#r-foreign').flexigrid(args);
			qryTr130500("1");
		});

		function onRefresh() {
			if (document.getElementById("radio-choice-1").checked) {
				qryTr130500("1");
			} else if (document.getElementById("radio-choice-2").checked) {
				qryTr130500("3");
			} else {
				qryTr130500("1");
			}
		}

		function qryTr130500(type) {
			today = getToday("yyyymmdd");
			tr130500(today, type);
		}

		function tr130500(today, type) {
			console.log("Type: " + type);
			$.ajax({
				type: "POST",
				url: "json/trproc.php",
				dataType : "json",
				data: "tr=130500&board=RG&type=" + type + "&from=" + today + "&to=" + today,
				success: function(jsRet){
					if (jsRet.status != 1) {
						alert(jsRet.mesg);
					} else {
						//console.log(jsRet);
						rows = [];
						cnt = jsRet.out.length;
						for (i = 0; i < cnt; i++) {
							id_v = i;

							code = jsRet.out[i].code;
							value = jsRet.out[i].val;
							fBuy = jsRet.out[i].frgBuy;
							fSell = jsRet.out[i].frgSell;
							netVal = jsRet.out[i].netBuySell;

							type == "3" ? fVal = fSell : fVal = fBuy;

							value = miniCurr(value);
							fBuy = miniCurr(fBuy);
							fSell = miniCurr(fSell);
							netVal = miniCurr(netVal);
							fVal = miniCurr(fVal);

							cell_v = [
								code,
								value,
								fVal,
								netVal
								];
							row = {id:id_v, cell:cell_v};
							rows.push(row);
						}
						args = {dataType:"json", rows:rows, page:1, total:11};
						$('#r-foreign').flexAddData(args);
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
			$('#r-foreign tr').each( function(){ 
			/*
				// val
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
				// fval
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
			*/
				// fnetval
				var cell = $('td:nth-child(4) > div', this); 
				var cell1 = $('td:nth-child(3) > div', this); 
				var cell2 = $('td:nth-child(2) > div', this); 
				var val = cell.text();
				if (document.getElementById("radio-choice-1").checked) {
					cell.css("color", "blue");
					cell1.css("color", "blue");
					cell2.css("color", "blue");
				}
				if (document.getElementById("radio-choice-2").checked) {
					cell.css("color", "red");
					cell1.css("color", "red");
					cell2.css("color", "red");
				} else {
					cell.css("color", "blue");
				}
				/*
				if (Number(val) != 0) {
					var rgx = val.match(/(.*) (T|M|B)/);
					if (Number(rgx[1]) > 0) {
						cell.css("color", "blue");
					} else if (Number(rgx[1]) < 0) {
						cell.css("color", "red");
					}
				}
				*/
		}); 
		}

		</script>
</head> 
<body style="backgound:#ffffff;">
<div data-role="page" id="home" data-cache="never">
	<? include "page-header.php" ?>

		<fieldset data-role="controlgroup">
			<input type="radio" name="radio-choice-1" id="radio-choice-1" value="Buy" checked="checked" />
			<label for="radio-choice-1">Buy</label>

			<input type="radio" name="radio-choice-1" id="radio-choice-2" value="Sell"  />
			<label for="radio-choice-2">Sell</label>
		</fieldset>

	<div>
	<table id="r-foreign" class="flexigrid">
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
