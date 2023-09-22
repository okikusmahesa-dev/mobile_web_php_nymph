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
$pinState = 0;
$pinLogin = 0;
	echo '<script type="text/javascript">setTimeout(function() { window.location.href = "https://mobile.bcasekuritas.co.id/tlogout.php"; }, 15 * 60000);</script>';
if ($_SESSION["pinState"] != 1) {
	echo "<script>alert('Please Input PIN first.');</script>";
	header("refresh:1; url=inPin.php");
	exit(0);
} else {
	$pinState = $_SESSION["pinState"];
	$pinLogin = $_SESSION["pin"];
	$_SESSION["url"]="https://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];

}
?>
<html>
<head> 
	<title>Form Status Right Issue  </title> 
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<? include "inc-common.php" ?>

	<style type="text/css">
	<!--
	<? include "css/icon.css" ?>
	//-->
	</style>
	<!-- <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script> -->
	<script type="text/javascript">
		
		function tr805002(userId){
			
           today = getToday("yyyymmdd");
           $.ajax({
                type: "POST",
                url: "json/trproc.php",
                dataType: "json",
                data: sprintf("tr=805002&custNo=" +  userId),
                success: function(jsRet) {
						if(jsRet.status != 1) {
							alert(jsRet.mesg);
						} else {
							rows = [];
							cnt = jsRet.out.length;
							for (i = 0; i < cnt; i++) {
								cell_v = [
									jsRet.out[i]["seq"],
									jsRet.out[i]["stock_code"],
									jsRet.out[i]["dateapp"],
									addCommas(jsRet.out[i]["qty"]),
									addCommas(jsRet.out[i]["amount"]),
									jsRet.out[i]["sts_msg"],
									jsRet.out[i]["entry_date"],
									// jsRet.out[i]["dataFrom"],
									// jsRet.out[i]["dataTo"]
								];
								row = {cell:cell_v};
								rows.push(row);
							}
							args = {dataType: "json", rows: rows, page: 1, total: cnt};
							$('#status-right').flexAddData(args);
						}
					},
					error : function(data, status, err) {
						alert('error cummunication with server.');
					}
				});
			}
			$(document).ready(function() {
				jQuery('div').live('pagehide', function(event, ui) {
					var page = jQuery(event.target);
					if(page.attr('data-cahce') == 'never') {
						page.remove();
					};
				});
				// $("#r-group").change(function() {
				// 	qryTr101211();
				// })
				args = {
					title : "Status Exercise",
					dataType : "json",
					height : "auto",
					colModel : [
						
						{display:"No", width:60, sortable:false, align:'center'},
						{display:"Stock", width:70, sortable:false, align:'center'},
						{display:"Apply Date", width:90, sortable:false, align:'center'},
						{display:"Shares", width:65, sortable:false, align:'right'},
						{display:"Amount", width:70, sortable:false, align:'right'},
						{display:"Status", width:75, sortable:false, align:'center'},
						{display:"Entry_Date", width:120, sortable:false, align:'center'}
							
					],
                    buttons : [
                        {name:'Refresh', bclass:'refresh', onpress:refreshAll},
                        {separator: true},
                        // {name:'Create Group', bclass:'edit', onpress:editPage},
                    ]
				};
				$('#status-right').flexigrid(args);
				// tr101201("<?=$clientId;?>");
				tr805002("<?=$userId;?>");

			});
			function qryTr805002() {
				var clientId = "<?=$userId;?>";
				$.mobile.loading('show');
				tr103100(clientId)
				$.mobile.loading('hide');
			}
			function refreshAll() {
				location.reload();
			}
			$(document).ready( function () {
    $('#status-right').DataTable({
    	"pagingType" : "full_numbers",
    	"filter" : false
    });
} );


		
	</script>
</head> 
<body style="backgound:#ffffff;">
<div data-role="page" id="home" data-cache="never">
	<? include "page-header.php" ?>
	<div>

	<table id="status-right" class="flexigrid">
		<thead>
			<tr>
               <th></th>
            </tr>
		</thead>
		<tbody>
		</tbody>
	</table>
<td align="center" style="padding: 0 10px"><a id="histBtn" href="" style="width:10%" data-role="button" data-icon="back" data-mini="true" onclick="goRightIssue()" >Back</a>

	</div>
	<? include "page-footer.php" ?>
</div>
</body>
</html>
