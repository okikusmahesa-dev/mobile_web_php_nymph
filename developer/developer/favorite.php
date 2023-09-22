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
	<title>Portfolio</title> 
	<link rel="stylesheet" type="text/css" href="./css/favorite.css?20130314a" />
	<? include "inc-common.php" ?>
	<style type="text/css">
	<!--
	<? include "css/icon.css" ?>
	//-->
	</style>
	<script type="text/javascript">
		function tr101200(id, groupNo) {
			$.ajax({
				type: "POST",
				url: "json/trproc.php",
				dataType : "json",
				data: "tr=101200&id=" + id + "&groupNo=" + groupNo,
				success: function(jsRet){
					if (jsRet.status != 1) {
						alert(jsRet.mesg);
					} else {
						//alert( "jsRet=" + JSON.stringify(jsRet) );
						cnt = jsRet.out.length;
						rows = [];
						for (i = 0; i < cnt; i ++) {
							id_v = "id" + i;
							cell_v = [
								"B", "S",
								jsRet.out[i]["code"],
								jsRet.out[i]["price"],
								jsRet.out[i]["chg"],
								jsRet.out[i]["chgR"],
								jsRet.out[i]["high"],
								jsRet.out[i]["low"],
								vol2lot(jsRet.out[i]["bidVol"]),
								jsRet.out[i]["bid"],
								jsRet.out[i]["off"],
								vol2lot(jsRet.out[i]["OffVol"]),
								vol2lot(jsRet.out[i]["vol"]),
								];
							row = {id:id_v, cell:cell_v};
							rows.push(row);
						}
						args = {dataType:"json", rows:rows, page:1, total:cnt};
						//alert( "args=" + JSON.stringify(args) );
						//alert( "args=" + jQuery.toJSON(args));
						$('#r-favorite').flexAddData(args);
						//$('#r-favorite').flexReload();
					}
				},
				error: function(data, status, err) {
					console.log("error forward : "+data);
					alert('error communition with server.');
				}
			});
		}
		function tr101201(id) {
			$.ajax({
				type: "POST",
				url: "json/trproc.php",
				dataType : "json",
				data: "tr=101201&id=" + id + "&nop=0",
				success: function(jsRet) {
					if (jsRet.status != 1) {
						alert(jsRet.mesg);
					} else {
						//alert( "jsRet=" + JSON.stringify(jsRet) );
						cnt = jsRet.out.length;
						iCnt = 0;
						for (i = 0; i < cnt; i ++) {
							if (jsRet.out[i]["title"].length > 0) {
								if (iCnt == 0) {
									item = sprintf("<option value='%s' selected='selected'>%s</option>", jsRet.out[i]["no"], jsRet.out[i]["title"]);
								}
								else {
									item = sprintf("<option value='%s'>%s</option>", jsRet.out[i]["no"], jsRet.out[i]["title"]);
								}
								$("#r-group").append(item);
								iCnt ++;
							}
						}
					}
					//alert("first group=" + $("#r-group>option:first").val());
					$("#r-group")[0].selectedIndex = 0;
					$("#r-group").selectmenu("refresh");
					tr101200(id, $("#r-group>option:first").val());
				},
				error: function(data, status, err) {
					console.log("error forward : "+data);
					alert('error communition with server.');
				}
			});
		}
		$(document).ready(function() {
			jQuery('div').live('pagehide', function(event, ui){
				var page = jQuery(event.target);
				if(page.attr('data-cache') == 'never'){
				page.remove();
				};
			});
			//$("#r-group").combobox();
			$("#r-group").change(function() {
				//alert("select=" + $("#r-group").children("option:selected").val());
				tr101200("cna001", $("#r-group").children("option:selected").val());
			});
			args = {
				title : "Favorite Stock",
				dataType : "json",
				height : "auto",
				colModel : [
					{display:"Buy", width:30, sortable:false, align:'center'},
					{display:"Sell", width:30, sortable:false, align:'center'},
					{display:"Code", width:50, sortable:false, align:'center'},
					{display:"Price", width:70, sortable:false, align:'right'},
					{display:"Chg", width:50, sortable:false, align:'right'},
					{display:"Chg(%)", width:50, sortable:false, align:'right'},
					{display:"High(P)", width:70, sortable:false, align:'right'},
					{display:"Log(P)", width:70, sortable:false, align:'right'},
					{display:"Bid(V)", width:70, sortable:false, align:'right'},
					{display:"Bid", width:50, sortable:false, align:'right'},
					{display:"Off", width:50, sortable:false, align:'right'},
					{display:"Off(V)", width:70, sortable:false, align:'right'},
					{display:"Volume", width:70, sortable:false, align:'right'},
				]
			};
			$('#r-favorite').flexigrid(args);
			tr101201("cna001");
		});

		function removeGroupAll() {
			$("#r-group").find('option').each(function() {
				$(this).remove();
			});
		}
		function favorite_query() {
			tr101200("cna001", "1");
		}
	</script>
</head> 
<body style="backgound:#ffffff;">
<div data-role="page" id="home" data-cache="never">
	<? include "page-header.php" ?>
	<div id="header1" class="ui-grid-d" style="margin:0;padding:0;">
		<div class="ui-block-a">
		<select id="r-group">
		</select></div>
		<div class="ui-block-b"><button type="button" onclick="favorite_query();return false;" data-icon="refresh">Refresh</button></div>
	</div>
	<table id="r-favorite" class="flexigrid">
		<thead>
		</thead>
		<tbody>
		</tbody>
	</table>
	<? include "page-footer.php" ?>
</div>
</body>
</html>
