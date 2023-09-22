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
	echo '<script type="text/javascript">setTimeout(function() { window.location.href = "https://mobile.bcasekuritas.co.id/tlogout.php"; }, 15 * 60000);</script>';
if ($_SESSION["pinState"] != 1) {
	echo "<script>alert('Please Input PIN first!');</script>";
	echo "<script>alert('Please Input PIN first.');</script>";
	header("refresh:1; url=inPin.php");
	exit(0);
} else {
	$pinState = $_SESSION["pinState"];
	$pinLogin = $_SESSION["pin"];
	//$_SESSION["url"]="https://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];

}
//
?>
<html>
<head> 
	<title>Sell Order</title> 
	<meta name="viewport" content="width=device-width, initial-scale=1"> 
	<? include "inc-common.php" ?>
	<style type="text/css">
	<!--
	<? include "css/icon.css" ?>
	.data-row {height:23px;}
	.no-ime {ime-mode:disabled; text-transform:uppercase;}
	.bid-qty {background-color:#feeeee;}
	.bid-price {background-color:#feeeee;}
	.off-qty {background-color:#e4f2fa;}
	.off-price {background-color:#e4f2fa;}
	.buy-panel {background-color:#d6ffc6;}
	.sell-panel {background-color:#ffeaea;}
	body {background-color:#d6ffc6;}
	//-->
	</style>
	<script type="text/javascript" src="js/portorder.js"></script>
	<script type="text/javascript" src="js/curPriceExt.js?20170919b"></script>
	<script type="text/javascript">
	var userId = '<?=$userId;?>';
	var loginId = "<?=$loginId;?>";
	var isWork = '<?=$isWork?>';
	var now = new Date();
	var time = now.toTimeString();
	today = now.format("yyyymmdd");

	var flagB = 0;
	var TimeStart = 0;
	var TimeEnd = 0;
	var srvOrderStatus = "";
	var srvOrderMsg = "";
	
	function setInput() {
		$("#codeTxt").keyup(function(e){
			this.value = this.value.toUpperCase();
			if (event.keyCode == 13) {
				code = document.getElementById('codeTxt').value;
				price = document.getElementById('priceTxt').value
				$.mobile.loading('show');
				// hichang modified 2017.04.10
				//if(code.includes("-R")){
				if (code.indexOf("-R") != -1){
				 	alert("To complete this transaction please contact our helpdesk(021-2358 7222)");
				 	location.reload();
				}else{
					qryTr100000();
					$("#balQty").val(arr800001["lot"][i]);
				}
				$.mobile.loading('hide');
			}
		});
		$("#priceTxt").keyup(function(e){
			if (this.value.length == 0) {
				return;
			}
			if (!isNaN(parseInt(this.value,10))) {
				this.value = parseInt(this.value);
			} else {
				this.value = 0;
			}
			this.value = this.value.replace(/[^0-9]/g, '');
			if (parseInt(this.value,10) <= 0) {
				this.value = 1;
				return;
			}
			if (parseInt(this.value,10) > 1000000) {
				this.value = 999999;
				return;
			}
		});
		$("#lotTxt").keyup(function(e){
			if (this.value.length == 0) {
				return;
			}
			if (!isNaN(parseInt(this.value,10))) {
				this.value = parseInt(this.value);
			} else {
				this.value = 0;
			}
			this.value = this.value.replace(/[^0-9]/g, '');
			if (parseInt(this.value,10) <= 0) {
				this.value = 1;
				return;
			}
			if (parseInt(this.value,10) > 50000) {
				this.value = 50000;
				return;
			}
		});
	
		$("#okBtn").click(function(){
			lotTxt = document.getElementById('lotTxt').value
//			var isWork = '<?=$isWork?>';

			var bal = checkBalance($("#codeTxt").val());

			if ( document.getElementById('codeTxt').value.length < 4 || document.getElementById('priceTxt').value.length == 0 || document.getElementById('lotTxt').value.length == 0 ) {
				alert("Error Input. Check Again!");
				return;
			}

			console.log("uLimit: " + uLimit + " lLimit: " + lLimit);

			ret = chkFraction();
			//alert("lLimit_lama/lLimit_baru = " +lLimit+"/"+lLimit_baru+ " uLimit_lama/uLimit_baru = " +uLimit+"/"+uLimit_baru);
			//alert("Fract = "+fract);

			//ret = 1;
//
			if (Number($("#priceTxt").val()) > uLimit) {
				alert("Cannot Order. Price cannot be more than " + uLimit + "!");
				return;
			}

			if (Number($("#priceTxt").val()) < lLimit) {
				alert("Cannot Order. Price cannot be less than " + lLimit + "!");
				return;
			}

            //mato 23-oktober-2017
            if (Number($("#lotTxt").val()) % 1 !== 0){
                alert("Cannot Order. Lot is not valid!");
                return;
            }

			if (ret != 1 ) {
				alert("Cannot Order. Price is not valid!");
				return;
			}

			if (bal == 0) {
				alert("Cannot Order, Balance = 0");
				return;
			}

			if (bal < Number($('#lotTxt').val())) {
				alert("Cannot Order, Balance = " + bal);
				return;
			}
			//added by Toni 20170309
			console.log("flag = "+flagB);
			if (flagB==0){
				$("#userIdW").text(userId);
				$("#codeW").text(code);
				$("#priceW").text(price);
				$("#lotW").text(lotTxt);
				$.mobile.changePage("#confirmDialog", {role: "dialog" } );
				$("#okConfirmBtn").one("click",function(){
					flagB++;
					tr189000(userId, code, "RG", price, lotTxt);
				});
				$("#cancelBtn").one("click",function(){
					$('#confirmDialog').dialog( "close" );
					window.location.href="sellOrderExt.php";
				});
//				if( confirm("UserId\t: "+userId +"\nCode\t: "+$("#codeTxt").val()+"\nPrice\t: "+$("#priceTxt").val()+"\nLot\t: "+$("#lotTxt").val()+"\n\nAre You Sure?") ){
//					$.mobile.loading('show');
//					flagB++;
//	                tr189000(userId, $("#codeTxt").val(), "RG", $("#priceTxt").val(), $("#lotTxt").val());
//	                $.mobile.loading('hide');
//				}
			}else{
			    $.mobile.loading('show');
			    if(flagB>10){
			       window.location.href="orderList.php";
			    }
			    flagB++;
			}


/*			if ( confirm("Are you sure?") ) {
				$.mobile.loading('show');
				tr189000(userId, $("#codeTxt").val(), "RG", $("#priceTxt").val(), $("#lotTxt").val());
				$.mobile.loading('hide');
			}
*/			
		});

		$("#stockBtn").click(function(){
			$.mobile.loading('show');
			qryTr100000();

			// Visible Balance
			code = document.getElementById('codeTxt').value;
			// hichang modified 2017.04.10
			//if(code.includes("-R")){
			if (code.indexOf("-R") != -1){
				alert("To complete this transaction please contact our helpdesk(021-2358 7222)");
				location.reload();
			}
			for (i=0;i<arr800001["code"].length; i++) {
				if (code == arr800001["code"][i]) {
					$("#balQty").val(arr800001["lot"][i]);
				}
			}
			//
			$.mobile.loading('hide');
		});
	}

	function chkFraction() {
		price = document.getElementById('priceTxt').value;

		interval = 1;

		var reks = false;

		if (code.substring(0,1) == 'X' || code.substring(0,2) == 'R-') {
			interval = 1;
			lLimit_baru = lLimit;
			uLimit_baru = uLimit;
			reks = true;
		} else {
			if (Gprev < 200) {
				interval = 1;
				lLimit_baru= lLimit;
				uLimit_baru= uLimit;
			}
			if ((Gprev >= 200) && (Gprev < 500)) {
				if(lLimit%2!=0){
                    lLimit_baru= lLimit-(lLimit%2)+2;
				    uLimit_baru= uLimit-(uLimit%2);//-2;
				}
				else{
					lLimit_baru = lLimit;
					uLimit_baru = uLimit;
				}
				interval = 2;
			}
			if ((Gprev >= 500) && (Gprev < 2000)) {
				if(lLimit%5!=0){
				    lLimit_baru= lLimit-(lLimit%5)+5;
				    uLimit_baru= uLimit-(uLimit%5);//-5;
				}
				else{
					lLimit_baru = lLimit;
					uLimit_baru = uLimit;
				}
				interval = 5;
			}
			if ((Gprev >= 2000) && (Gprev < 5000)) {
				if(lLimit%10!=0){
					lLimit_baru= lLimit-(lLimit%10)+10;
					uLimit_baru= uLimit-(uLimit%10);//-10;
				}
				else{
			 		lLimit_baru = lLimit;
					uLimit_baru = uLimit;
			   	}
				interval = 10;
			}
			if (Gprev >= 5000) {
				if(lLimit%25!=0){
					lLimit_baru= lLimit-(lLimit%25)+25;
					uLimit_baru= uLimit-(uLimit%25);//-25;
				}
				else{
					lLimit_baru = lLimit;
					uLimit_baru = uLimit;
				}
				interval = 25;
			}
		}

		// uLimit - lLimit
		fract = [];
		x = 0;
		for (i=Number(lLimit_baru); i<=Number(uLimit_baru); i++) {
			fract.push(x.toString());
			if (reks) {
				i = (i + 1) - 1;
				x = i;
			} else {
				if (i < 200) {
					i = (i + 1) - 1;
					x = i + 1;
				} else if ((i >= 200) && (i < 500)) {
					i = (i + 2) - 1;
					x = (i + 1);
				} else if ((i >= 500) && (i < 2000)) {
				    i = (i + 5) - 1;
				    x = (i + 1);
			    } else if ((i >= 2000) && (i < 5000)) {
			        i = (i + 10) - 1;
			        x = (i + 1);
				} else if (i >= 5000) {
					i = (i + 25) - 1;
					x = (i + 1);
				} else {
					i = (i + Number(interval)) - 1;
					x = (i + Number(interval));// - 1;
				}

			}
		}

   
	//alert("DISINI!!! : " + Gprev +" price: "+ price + " code: "+code+" -- llimit baru "+lLimit_baru+" ulimit baru "+uLimit_baru);
		     //alert("DISINI!!! : " +fract);

/*
		for (i=0; i<=fract.length; i++) {
			if (price == fract[i]) {
				return 1;
			}
		}
*/

		if((price >= lLimit_baru) && (price <= uLimit_baru)){
			if (code.substring(0,1) == 'X' || code.substring(0,2) == 'R-') {
				ret= price % 1;
			}if(price > 5000)
				ret = price % 25;
			else if((price > 2000) && (price <= 5000))
				ret = price % 10;
			else if((price > 500) && (price <= 2000))
				ret = price % 5;
			else if((price > 200) && (price <= 500))
				ret = price % 2;
			else if((price >= 50) && (price <= 200))
				ret = price % 1;
			else if(price < 50)
				ret = price % 1;
		
		}
		else{
			return 0;
		}
			
		if(ret > 0){
			return 0;}
		else{
			return 1;}
			
		//return 0;
	}

	function vol2lot(qty) {
		return qty / 100;
	}

	function tr189000(id, code, board, price, lot) {
		$.ajax({
			type: "POST",
			url: "json/trproc.php",
			dataType : "json",
			//data: "tr=189100&userId=" + id + "&dealerId=" + id + "&pin=&code=" + code + "&price=" + price + "&qty=" + lot + "&lot=100" + "&mkt=" + board + "&type=0",
			data: "tr=189100&userId=" + id + "&dealerId=" + id + "&pin=&code=" + code + "&mkt=" + board + "&price=" + price + "&qty=" + lot + "&lot=100" + "&type=0",
			success: function(jsRet){
				console.log(jsRet);
				if (jsRet.status != 1) {
					alert(jsRet.mesg);
				} else {
					alert("Order accepted.");
					window.location.href="orderList.php";
					clearInput();
				}
				//alert( "jsRet=" + JSON.stringify(jsRet) );
			},
			error: function(data, status, err) {
				console.log("error forward : "+data);
				alert('error communition with server.');
			}
		});
	}

	function clearInput() {
		document.getElementById('codeTxt').value = "";
		document.getElementById('priceTxt').value = "";
		document.getElementById('lotTxt').value = "";
		document.getElementById('balQty').value = "";
	}
	
	$(document).ready(function() {
		tr080116();
		tr800017();
		flagB = 0;
		jQuery('div').live('pagehide', function(event, ui){
			var page = jQuery(event.target);

			if(page.attr('data-cache') == 'never'){
			page.remove();
			};
		});
		setInput();
		
		qryTr800001();
	});
	//trTimeFlag added by Toni 20161128

	function tr800017(){
		$.ajax({
			type : "POST",
			url : "json/trproc.php",
			dataType : "json",
			data : "tr=800017&id=2",
			success : function(jsRet){
				if (jsRet.status !=1){
                    alert("Error: " + jsRet.mesg);
                }else{
                    console.log(jsRet.out);
                    if (jsRet.out[0].flag=="N"){
                        srvOrderStatus = 'N';
                        srvOrderMsg = "Can not Order (Server Maintenance)";
//                      alert(checkOrderTime());
    //                  location.href = "index.php?uFlag=1"; Fahri
                    }else{
                        if(isWork == 1){
                            if(time > TimeStart && time < TimeEnd){

                            }else{
                                var s1 = TimeStart.substring(0,2) + ":" + TimeStart.substring(2,4);
                                var s2 = TimeEnd.substring(0,2) + ":" + TimeEnd.substring(2,4);
                                alert("Can Order While " + s1 + "~" + s2);
                                window.location.href="index.php?uFlag=1";
                                return;
                            }
                        }else{
                            alert("Today is Holiday");
                            window.location.href="index.php?uFlag=1";
                            return;
                        }
                    }
                }
            }
        });
    }

	function tr080116(){
        $.ajax({
            type : "POST",
            url : "json/trproc.php",
            dataType : "json",
            data : "tr=080116&loginID=" + loginId,
            success : function(jsRet){
                if (jsRet.status != 1){
                    alert(jsRet.mesg);
                }else{
                    console.log(jsRet.out);
                    TimeStart = jsRet.out[0].otStart;
                    TimeEnd = jsRet.out[0].otEnd;
                }
                $.mobile.loading('hide');
            },
            error : function(data, status, err){
                console.log("error tr080116 : " + data);
                $.mobile.loading('hide');
            }
        });
    }


	function qryTr800001() {
		var userId = "<?=$userId;?>";
		var now = new Date();
		nowStr = now.format("yyyymmdd");
		tr800001(userId, nowStr);
	}
	//

	var arr800001 = [];
	arr800001["code"] = [];
	arr800001["lot"] = [];

	//tr800001
	function tr800001(id, today) {
		$.ajax({
			type: "POST",
			url: "json/trproc.php",
			dataType : "json",
			data: "tr=800001&clientID=" + id + "&date=" + today,
			success: function(jsRet){
				if (jsRet.status != 1) {
					alert(jsRet.mesg);
				} else {
					rows = [];
					cnt = jsRet.out.length;
					for (i = 0; i < cnt; i ++) {
						id_v = i;
						//
						jsRet.out[i].lot = vol2lot(jsRet.out[i].balance);
						//
						code = jsRet.out[i].stkID;
						lot = jsRet.out[i].lot;
						shares = jsRet.out[i].balance;
						//
						arr800001["code"][i] = code;
						arr800001["lot"][i] = lot;
					}
				}
			}
		});
	}
	//

	//
	function checkBalance(code) {
		qryTr800001();
		
		//
		for (i = 0;i < arr800001["code"].length;i++) {
			if (arr800001["code"][i] == code) {
				lot = Number(arr800001["lot"][i]);
				return lot;
			}
		}
		//

		return 0;
	}
	//
	</script>
</head> 
<body>
	<div data-role="page" id="home" data-cache="never">
	<? include "page-header.php" ?>
	<div class="sell-panel">
	<!-- <b>	Sell Order	</b> -->
	<table width=320px border=0>
		<tr><td colspan="3"><h2 style="margin-bottom: 0px;">Sell Order</h2></td></tr>
		<tr>
			<td width="25%">Code</td>
			<td width="25%"><input id="codeTxt" type="text" class="no-ime" data-mini="true" /></td>
			<td width="5%"><a id="stockBtn" href="#" data-role="button" data-icon="check" data-mini="true" data-inline="true">&nbsp</a></td>
		</tr>
		<tr>
			<td width="25%">Price</td><td width="25%" colspan="2"><input id="priceTxt" type="text" class="no-ime" data-mini="true" maxlength="6"/></td>
		</tr>
		<tr>
			<td width="25%" id="lotMaxQty">Lot</td>
			<td width="25%"><input id="lotTxt" type="text" class="no-ime" data-mini="true" /></td>
			<td width="5%"><input id="balQty" type="text" disabled="disabled" data-mini="true" /></td>
		</tr>
		<tr>
			<td colspan=3>
				<a id="okBtn" href="#" data-role="button" data-icon="check" data-mini="true">Order</a>
			</td>
		</tr>
	</table>
	<table border=0 id="curPrice" width=320px>
		<thead>
			<th colspan="4" id="price" class="lprice" style="font-size:20px"></th>
		</thead>
		<tbody>
		<tr>
			<td class="r-cell">Prev</td><td id="prev" class="r-qty"></td>
			<td class="r-cell">Chg(%)</td><td id="chg" class="r-qty"></td>
		</tr>
		<tr>
			<td class="r-cell">Open</td><td id="oprice" class="r-qty"></td>
			<td class="r-cell">Value(M)</td><td id="value" class="r-qty"></td>
		</tr>
		<tr>
			<td class="r-cell">High</td><td id="hprice" class="r-qty"></td>
			<td class="r-cell">Volume</td><td id="volume" class="r-qty"></td>
		</tr>
		<tr>
			<td class="r-cell">Low</td><td id="lprice" class="r-qty"></td>
			<td class="r-cell">Freq</td><td id="freq" class="r-qty"></td>
		</tr>
		</tbody>
	</table>
	<table width=320px id="r-quote" class="flexigrid">
		<thead>
		</thead>
		<tbody>
		</tbody>
	</table>
	</div>
	<? include "page-footer-buysell.php" ?>
	</div>
<div id="confirmDialog" data-role="page">
    <div data-role="header" data-theme="b">
        <h2>Sell Order</h2>
    </div>
    <div data-role="main" class="ui-content" style="background-color:#c4e2e2">
        <table border=0 width=100%>
            <tr>
                <td width="25%" style="color:#000000">User Id</td><td id="userIdW" width="25%" style="color:#000000" align="right"></td>
            </tr>
            <tr>
                <td width="25%" style="color:#000000">Code</td><td id="codeW" width="25%" style="color:#000000" align="right"></td>
            </tr>
            <tr>
                <td width="25%" style="color:#000000">Price</td><td id="priceW" width="25%" style="color:#000000" align="right"></td>
            </tr>
            <tr>
                <td width="25%" style="color:#000000">Lot</td><td id="lotW" width="25%" style="color:#000000" align="right"></td>
            </tr>
            <tr>
                <td>
                    <a id="okConfirmBtn" href="#" data-role="button">OK</a>
                </td>
                <td>
                    <a id="cancelBtn" autofocus href="#" data-role="button">Cancel</a>
                </td>
            </tr>
        </table>
    </div>
</div>

</body>
</html>
