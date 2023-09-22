<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");

?>
<html>
<head> 
	<title>Trade List</title> 
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
					{display:"Buy Value", width:70, sortable:false, align:'right'},
					{display:"Avg S", width:80, sortable:false, align:'center'},
					{display:"Sell Value", width:70, sortable:false, align:'right'},
					{display:"Net Value", width:80, sortable:false, align:'right'},
				],
				buttons : [
					{name:'Refresh', bclass:'refresh', onpress:qryTr100800},
					{separator: true}
				]
			};
			$('#r-orderList').flexigrid(args);
			qryTr182800();
		});

		function qryTr100800() {
		}
	</script>
</head> 
<body style="backgound:#ffffff;">
<div data-role="page" id="home" data-cache="never">
	<? include "page-header.php" ?>
	<div>
	<table id="r-orderList" class="flexigrid">
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
