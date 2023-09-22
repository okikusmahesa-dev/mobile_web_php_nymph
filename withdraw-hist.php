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
	<title>Historical Withdraw </title> 
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<? include "inc-common.php" ?>
	<style type="text/css">
	<!--
	<? include "css/icon.css"?>
	//-->
	</style>
	<script type="text/javascript">
		function tr710013(clientNo, name){
           console.log(clientNo);
            console.log(name);
           today = getToday("yyyymmdd");
           $.ajax({
                type: "POST",
                url: "json/trproc.php",
                dataType: "json",
                data: sprintf("tr=710013&ClientNo=%s&name=",clientNo),
                success: function(jsRet){
                    console.log(jsRet);
                    if(jsRet.status != 1){
                        alert(jsRet.mesg);
                    }else{
                        cnt = jsRet.out.length;
                        iCnt = 0;
                        rows = [];
                        for(i=0;i<cnt;i++){
                            id_v = "id"+1;
                               trfDt1 = jsRet.out[i]['transfer_date'].substring(0,4); 
                               trfDt2 = jsRet.out[i]['transfer_date'].substring(4,6); 
                               trfDt3 = jsRet.out[i]['transfer_date'].substring(6,8); 
							   /*
                               if(jsRet.out[i]['transfer_date'] < today){
                                    status = "Sent to Administrator";
                               }else if(jsRet.out[i]["status"]==0){
                                    status = "Sent to Administrator";
                               }else if(jsRet.out[i]["status"]==2){
                                    status = "Sent to Administrator";
                               }else if(jsRet.out[i]["status"]==3){
                                    status = "Sent to Administrator";
                               }
							   */


                            cell_v = [
                                jsRet.out[i]["entry_date"].substring(0,19),
                                trfDt1+'-'+trfDt2+'-'+trfDt3,
                                //jsRet.out[i]["AccountNo"],
                                jsRet.out[i]["acctnoxxx"],
                                jsRet.out[i]["Bank"],
                               addCommas(jsRet.out[i]["transfer_account"]),
                                //status,
                                jsRet.out[i]["status_msg"],
                            ];
                            row=  {id:id_v, cell:cell_v};
                            rows.push(row);
                        }
                        args={dataType:"json", rows:rows, page:1, total:cnt};
                        $('#r-orderList').flexAddData(args);
                        $.mobile.loading('hide');
                    }
                },
                error: function(data,status,err){
                    console.log("error forward: "+data);
                }
            });
        }

		$(document).ready(function() {
			/*jQuery('div').live('pagehide', function(event, ui){
				var page = jQuery(event.target);
				if(page.attr('data-cache') == 'never'){
					page.remove();
				};
			});*/
			args = {
				title : "Withdraw History",
				dataType : "json",
				height : "auto",
				colModel : [
					{display:"Entry Date", width:130, sortable:false, align:'center'},
					{display:"Trf Date", width:70, sortable:false, align:'center'},
					{display:"Account No", width:90, sortable:false, align:'center'},
					{display:"Bank", width:190, sortable:false, align:'center'},
					{display:"Amount", width:100, sortable:false, align:'center'},
					{display:"Status", width:100, sortable:false, align:'center'},
				],
				buttons : [
					{name:'Refresh', bclass:'refresh', onpress:qryTr182800},
					{separator: true}
				]
			};
			$('#r-orderList').flexigrid(args);
			qryTr182800();
		});

		function qryTr182800() {
			$.mobile.loading('show');
			//tr182800('<?=$userId;?>', "0");
			tr710013('<?=$userId;?>', "");
            console.log("refresh");
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
	<tr>
		<td>
        <a id="withBtn" href="#" style="width:10%" data-role="button" data-icon="back" data-mini="true" onclick="goWithdraw()" >Back</a>
        </td>
	</tr>
	</div>
	<? include "page-footer.php" ?>
</div>
</body>
</html>
