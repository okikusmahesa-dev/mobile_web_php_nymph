<?
//
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
$loginId = $_SESSION["loginId"];
$pinState = 0;
$pinLogin = 0;

			
$isWork = $_SESSION["isWork"];
if ($_SESSION["pinState"] != 1) {
	echo "<script>alert('Please Input PIN first!');</script>";
	header("refresh:1; url=inPin.php");
	exit(0);
} else {
	$pinState = $_SESSION["pinState"];
	$pinLogin = $_SESSION["pin"];
	//$_SESSION["url"]="https://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];

}

?>
<html>
<head> 
	<title>Right Issue</title> 
	<meta name="viewport" content="width=device-width, initial-scale=1"> 
	<? include "inc-common.php" ?>
	<style type="text/css">
	<? include "css/icon.css" ?>
	.data-row {height:23px;}
	.no-ime {ime-mode:disabled; text-transform:uppercase;}
	.bid-qty {background-color:#feeeee;}
	.bid-price {background-color:#feeeee;}
	.off-qty {background-color:#e4f2fa;}
	.off-price {background-color:#e4f2fa;}
	.buy-panel {background-color:#d6ffc6;}
	.sell-panel {background-color:#d6ffc6;}
	.r-cell {text-align:left;width:25%;}
	.r-qty {text-align:right;width:25%;}
	.myTab {border: 0px outset black;}
	/*.row{
		display: flex;
	}*/
	@media screen and (max-width: 810px) {
		#form-action, .porto-right, .kotak, .tableFixHead{
			width: 100%
			font-size: 80%;
		} 	
	}
	@media screen and (max-width: 640px){
		#form-action, .porto-right, .kotak, .tableFixHead{
			width: 100%;
		} 	
	}
	#form-action{
		border-collapse: collapse;
		background-color:#d6ffc6;
	}
	table tbody tr td{
            padding: 8px 10px;
        }
    .flexigrid tr.erow td{
        background: #ffffff;
    }
    .flexigrid div.bDiv tr.trSelected:hover td,.flexigrid div.bDiv tr.trSelected:hover td.sorted,.flexigrid div.bDiv tr.trOver.trSelected td.sorted,.flexigrid div.bDiv tr.trOver.trSelected td,.flexigrid tr.trSelected td.sorted,.flexigrid tr.trSelected td{
            background: none;
    }
    .flexigrid div.cDrag div:hover,.flexigrid div.cDrag div.dragging{
        background: none;
    }
    .hDiv {
    	background: linear-gradient(0deg, rgba(190,190,190,1) 0%, rgba(255,255,255,1) 100%);
    }
	</style>
	<script type="text/javascript" src="js/portorder.js"></script>
	<script type="text/javascript" src="js/curPriceExt.js?20171016c"></script>
	<script type="text/javascript" src="js/trLibs.js?20200212a"></script>
	<script type="text/javascript">
	var userId = '<?=$userId;?>';
	var loginId = "<?=$loginId;?>";
	// console.log(userId);
	var now = new Date();
	today = now.format("yyyymmdd");
		$(document).ready(function() {
			jQuery('div').live('pagehide', function(event, ui) {
				var page = jQuery(event.target);
				if(page.attr('data-cahce') == 'never') {
					page.remove();
				};
			});
				args = {
				//title : "porto right",
				dataType : "json",
				height : "auto",
				singleselect : true,
				colModel : [
					{display:"S", width:30, sortable:false, align:'center', process:selectStock},
					{display:"Stock", width:100, sortable:false, align:'center'},
					{display:"Price", width:100, sortable:false, align:'center'},
					{display:"Share", width:100, sortable:false, align:'center'},
					{display:"Date Start", width:110, sortable:false, align:'center'},
					{display:"Date End", width:110, sortable:false, align:'center'}
				],
                    buttons : [
                        {name:'Refresh', bclass:'refresh', onpress:refreshAll},
                        {separator: true},		
                    ]
				};
				$('#right').flexigrid(args);
				tr805001("<?=$userId;?>");
				setInput();
				processOrder('Y');
				tr800000('<?=$userId;?>', today);
			});

			function tr805001(id) {	
				console.log(id);
				pHashOrg = "tr=805001&clientID=" + id;
                pHash    = "phash=" + Base64.encode(pHashOrg);
                $.ajax({
					type: "POST",
					url: "json/trproc.php",
					dataType: "json",
					data: pHash,
					success: function(jsRet) {
						console.log(jsRet);
						if(jsRet.status != 1) {
							alert(jsRet.mesg);
						} else {
							 cnt = jsRet.out.length;
                        iCnt = 0;
                        rows = [];
                        for(i=0;i<cnt;i++){
                        	id_v = "id" + i;
								cell_v = [
									"S",
									jsRet.out[i]["code"],
									jsRet.out[i]["price"],
									jsRet.out[i]["share"],
									jsRet.out[i]["trading_start"],
									jsRet.out[i]["trading_end"]
								];
								row = {id:id_v, cell:cell_v};
								rows.push(row);
							}
							args = {dataType: "json", rows: rows, page: 1, total: cnt};
							$('#right').flexAddData(args);

							for (i=0;i<cnt;i++) {
							var r = document.getElementById("right").getElementsByTagName('tr');
							var c = r[i].getElementsByTagName('td');
							jQuery( c[0] ).css("background-color", "#c4e2e2");
						}
						}
					},
					error : function(data, status, err) {
						alert('error cummunication with server.');
					}
				});
			}

			function tr805010(id, stock_code, user_name, amount, price, share, ex_date, date) {
				pHashOrg = "tr=805010&seq=" + "" + "&login_id=" + '<?=$loginId?>' + "&user_id=" + id + "&user_name=" + clientName + "&stock_code=" + stock_code + "&price=" + price + "&qty=" + share + "&amount=" + amount + "&ex_date=" + ex_date + "&apply_date=" + today + "&status=2" + "&operation=INSERT",
	        	pHash    = "phash=" + Base64.encode(pHashOrg);
		      	console.log(pHashOrg);
				$.ajax({
					type: "POST",
					url: "json/trproc.php",
					dataType : "json",
					data: pHash,
					success: function(jsRet){
						if (jsRet.status == 1) {
							// jsRet.out[0].status : "0" Order accepted
							// jsRet.out[0].status : "1" Others
							if (jsRet.out[0].status == "0") {
								alert(jsRet.out[0].mesg);
								window.location.href="right1.php";
							} else {
								alert(jsRet.out[0].mesg);
								location.reload();
							}
						}
						//alert( "jsRet=" + JSON.stringify(jsRet) );
						$.mobile.loading('hide');
					},
					error: function(data, status, err) {
						console.log("error forward : "+data);
						//$.mobile.loading('hide');
						alert('error communition with server.');
						window.location.replace("index.php?uFlag=1");
					}
				});
				//tr800000();
			}

			function selectStock(celDiv, id) {
				$(celDiv).click(function(){
					user_id = '<?=$userId?>';
					row = parseInt(id.substr(3));
					code = $("#row" + id + " td:nth-child(2) div").text();
					share = $("#row" + id + " td:nth-child(4) div").text();
					price = $("#row" + id + " td:nth-child(3) div").text();
					ex_date = $("#row" + id + " td:nth-child(6) div").text();

					console.log(id);
					
					amount = share * price;

					console.log(code);
					console.log($("#share").val());
					$("#stock_code").val(code);
					$("#share").val(share);
					$("#share").attr("max", share);
					$("#price").val(price);
					$("#amount").val(amount);
					$("#amount1").val(addCommas(amount));
					$("#ex_date").val(ex_date);
					processOrder("N");
				});
			}

			jQuery(function($){
				price = $("#price").val()
				$("#share").on('input', function(){
					share = $("#share").val();
					$("#amount").val(share*price);
					$("#amount1").val(addCommas(share*price));
				});
			});	


			function setInput(){
				$("#applyBtn").click(function(){
					
					var id = '<?=$userId?>';
					var stockCode = $("#stock_code").val();
					var share = $("#share").val();
					var userName = $("#name").val();
					var limitShare = $("#right td:nth-child(4) div").text();
					var amount = $("#amount").val();
					var date = $("#apply_date").val();
					var isWork = '<?=$isWork?>';
					var price = $("#price").val();
					var ex_date = $("#ex_date").val();
					var rdi = $("#rdi1").val();

					console.log('rdi' + rdi);
					console.log('amount' + amount);
			
					if(isWork == 0){
						alert("Today is Holiday");
						window.location.href="index.php?uFlag=1";
						return;
					}

					if (parseInt(rdi) < parseInt(amount)) {
						alert("Your RDN balance is not enough");
						return;
					}else{
						if (confirm("Stock Code\t: "+stockCode +"\nShare\t: "+share+"\nAmount\t: "+amount+"\nApply Date\t:"+date+"\n\nAre You Sure?") == true) {
							tr805010(id, stockCode, userName, amount, price, share, ex_date, date);
						} else {
							window.location.replace("right1.php"); 
						}
					}

					
				});
			}
			
			function gotoStatusRight(){
        		location = "right-issue-status.php";        
    		}

    		function processOrder(flag) {
		        if (flag == 'Y') {
				    $.mobile.loading('hide');
		            $('#applyBtn').button('disable');
		        } else if (flag == 'N') {
				    $.mobile.loading('hide');
		            $('#applyBtn').button('enable');
		        } 
		    }

			function qryTr805001() {
				var clientId = "<?=$userId;?>";
				$.mobile.loading('show');
				tr805001(clientId)
				$.mobile.loading('hide');
			}

			function refreshAll() {
				location.reload();
			}

			function tr800000(user, now) {
				//console.log("TESSSSSS : " + today );
             pHashOrg = "tr=800000&userID=" + user + "&clientID=" + user + "&date=" + today;
            pHash    = "phash=" + Base64.encode(pHashOrg);
			$.ajax({
				type: "POST",
				url: "json/trproc.php",
				dataType : "json",
				data: pHash,
				success: function(jsRet){
					if (jsRet.status != 1) {
						alert(jsRet.mesg);
					} else {
						//
						rdn = sprintf("%d", Number(jsRet.out[0].rdn));
						clientName = jsRet.out[0].clientName;
						$('#rdi').val(addCommas(rdn));
						$('#rdi1').val(rdn);
						$('#name').val(clientName);
						//
					}
					$.mobile.loading('hide');
				},
				// console.log()
				error: function(data, status, err) {
					$.mobile.loading('hide');
					console.log("error forward : "+data);
					alert('error communition with server.');
					window.location.replace("portfolio.php");
				}
			});
		}

		function qryTr800000() {
			var userId = "<?=$userId;?>";
			var now = new Date();
			nowStr = now.format("yyyymmdd");
			$.mobile.loading('show');
			tr800000(userId, clientId); 
		}

		</script>
</head> 
<body>
	<div data-role="page" id="home" data-cache="never">
	<? include "page-header.php" ?>
	<div>
	<!-- <b>Buy Order</b> -->
	<div class="row">
		<div class="column" style="background-color: #d6ffc6;">
			<h2 style="margin-top: 0px;margin-bottom: 8px; padding:5;">Right Issue</h2>
			
			<table id="form-action" width="300px" style="display: inline-block;" cellpadding="0" cellspacing="0">
				<tr>
					<td>Stock Code</td>
					<td align="center" width="50%"><input type="text" id="stock_code" name="" disabled="disabled" ></td>
				</tr>
				<tr>
					<td align="center" colspan="2"><input type="text" id="" name="" disabled="disabled"></td>
				</tr>
				<tr>
					<td>Apply Share</td>
					<td align="center" width="50%"><input type="number" id="share" name="" max=""></td>
				</tr>
				<tr>
					<td>Amount</td>
					<td align="center" width="50%"><input type="text" id="amount1" name="" disabled="disabled"></td>
					
				</tr>
				<tr>
					<td>Apply Date</td>
					<td align="center" width="50%"><input type="Date" id="apply_date" name="" value="<?php echo date('Y-m-d'); ?>" disabled="disabled"></td>
				</tr>
				<tr>
					<td align="center" width="50%" hidden="hidden"><input type="text" id="price" name="" disabled="disabled" ></td>
					<td align="center" width="50%" hidden="hidden"><input type="text" id="amount" name="" disabled="disabled" ></td>
					<td align="center" width="50%" hidden="hidden"><input type="text" id="ex_date" name="" disabled="disabled" ></td>
					
				</tr>
				<tr>
					<td align="center" style="padding: 0 10px"><a id="histBtn" href="" style="width:100%" data-role="button" data-icon="go" data-mini="true" onclick="gotoStatusRight()" >Status</a>

						
					</td>
					<td align="center" style="padding: 0 10px"><input type="button" data-icon="check" name="applyBtn" id="applyBtn" value="Apply" data-mini="true">
					</td>

				</tr>
			</tr>
			</table>
		<!--</div>-->
		
		<table  class="ui-responsive ui-shadow" width="385px" style="display: inline-block;" bgcolor="#F5F5F5" cellpadding="0" cellspacing="0">
			<tr style="background-color: 	#4682B4; color: #F0FFFF;">
				<td class="myTab" colspan="7">
					After 11.00 you will be applied at the next day (T+1)
				</td>
			</tr>
			<tr>
				<td colspan="2" >
					Available Cash (Today / T+1)  
				</td>
			</tr>
		</div>
			<tr >
				<td colspan="2">
					<div style="display: flex; width:350px;" >
						<input type="Date" name="" value="<?php echo date('Y-m-d'); ?>" style="width: 50%"  disabled="disabled" >&emsp;
					
					
						<input type="text" id="rdi" name="" style="width: 50%" disabled="disabled">
					</div>
						<div hidden="hidden">
							<input type="text" id="rdi1" name="" style="width: 50%" hidden>
						</div>	
					</div>
					
				</td>

			</tr>
		</table>
		
		<div>
			<table id="right" class="flexigrid">
				<thead>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
		
	</div>
	
	
	</div>
	
	<? include "page-footer-buysell.php" ?>
	</div>
</body>
</html>
