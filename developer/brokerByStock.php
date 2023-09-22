<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");

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
				colModel : [
					{display:"Broker", width:50, sortable:false, align:'center'},
					{display:"Avg B", width:80, sortable:false, align:'right'},
					{display:"Buy Val", width:70, sortable:false, align:'right'},
					{display:"Avg S", width:80, sortable:false, align:'center'},
					{display:"Sell Val", width:70, sortable:false, align:'right'},
					{display:"Net Value", width:80, sortable:false, align:'right'},
				],
				buttons : [
					{name:'Refresh', bclass:'refresh', onpress:qryTr100800},
					{separator: true}
				]
			};
			$('#r-broker').flexigrid(args);
			qryTr100800();
		});

		function qryTr100800() {
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
				<input id="codeTxt" type="text" value="BBCA" maxlength=8 style="width=100;ime-mode:disabled;text-transform:uppercase;" data-mini="true"/>
			</td>
			<td></td>
			<td></td>
			<td align="right" width="25%">
				<a id="okBtn" href="#" data-role="button" data-icon="refresh" data-mini="true" style="float:right;">Refresh</a><br/>
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
