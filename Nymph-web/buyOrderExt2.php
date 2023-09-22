<?
//
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");
//
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
$isWork = $_SESSION["isWork"];
	echo '<script type="text/javascript">setTimeout(function() { window.location.href = "https://mobile.bcasekuritas.co.id/tlogout.php"; }, 15 * 600000);</script>';
if ($_SESSION["pinState"] != 1) {
	//echo "<script>alert('Please Input PIN first!');</script>";
	echo "<script>alert('Please Input PIN first.');</script>";
	header("refresh:1; url=inPin.php");
	exit(0);
} else {
	$pinState = $_SESSION["pinState"];
	//$_SESSION["url"]="https://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
	$pinLogin = $_SESSION["pin"];
}

$in_code = isset($_GET["code"]) ? $_GET["code"] : "";
$in_price = isset($_GET["price"]) ? $_GET["price"] : "";
$in_qty = isset($_GET["qty"]) ? $_GET["qty"] : "";

?>
<html>
<head> 
	<title>Buy Order</title> 
	<meta name="viewport" content="width=device-width, initial-scale=1"> 
	<? include "inc-common.php";?>
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
	.sell-panel {background-color:#d6ffc6;}
	.r-cell {text-align:left;width:25%;}
	.r-qty {text-align:right;width:25%;}
	//-->
	</style>
	<script type="text/javascript" src="js/curPriceExt-dev.js?20171016c"></script>
	<script type="text/javascript">
	var userId = "<?=$userId;?>";
	var pinLogin = "<?=$pinLogin;?>";
	var now = new Date();
	today = now.format("yyyymmdd");

	var price = 0;
    var lot = 0;
    var qty = 0;
	var tot = 0;
	var flagB = 0;	
	//auto calculate total 20150930
    function calcTotal() {
        price = Number(document.getElementById('priceTxt').value);
	    lot   = Number(document.getElementById('lotTxt').value);
		commission = Number(document.getElementById('commissionTxt').value);
        total = price * lot * 100;
        total = total + total * commission/100;
        total = sprintf("%d", total);
        $('#amountTxt').val(addCommas(total));
        okBtn_enable_disable(); 
    }


    function processOrder(flag) {
    // flag = Y : create
    // flag = N : close (success or failed)
        if (flag == 'Y') {
		    $.mobile.loading('show');
            $('#okBtn').button('disable');
        } else if (flag == 'N') {
		    $.mobile.loading('hide');
            $('#okBtn').button('enable');
        } 
    }

	// Initialize
	$(document).ready(function() {
		tr800017();
		tr800300(userId, pinLogin);
		flagB = 0;
		jQuery('div').live('pagehide', function(event, ui){
			var page = jQuery(event.target);
			if(page.attr('data-cache') == 'never'){
				page.remove();
			};
		});

		$("#codeTxt").val('<?=$in_code;?>');
		$("#priceTxt").val('<?=$in_price;?>');
		$("#lotTxt").val('<?=$in_qty;?>');
		document.getElementById('priceTxt').disabled = true;
		document.getElementById('lotTxt').disabled = true;
		setInput();
        // set Cash Available
        tr800011(userId, "SRIL", "", "", "", "", "Y");
		confirmation("hello<br>hellp");
	});

	// priceTxt Handler
	function checkOrderTime() {
	    <?php 
	    $s1 = "07:15:00";
		$s2 = "07:25:00";
		$s3 = "16:15:00";
		if(time()>strtotime($s1) && time()<strtotime($s2) ){?>
		    return "Tidak bisa melakukan order di jam 07.15~07.25";
        <?php }elseif(time()>strtotime($s3)){?>
		    return "Tidak bisa melakukan order, Jam trading sudah berakhir";
		<?php }else{?>
		    return "Order mulai Jam 07.25~16.15";
		<?php }?>
	}

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
					if (jsRet.out[0].flag=="N"){
						alert(checkOrderTime());
						location.href = "index.php?uFlag=1";
					}
				}
			}
		});
	}

	function tr800300(id, pin) {
		$.mobile.loading('show');
		$.ajax({
			type: "POST",
			url: "json/trproc.php",
			dataType : "json",
			data: "tr=800300&userId=" + id + "&pin=" + pin,
			success: function(jsRet){
				if (jsRet.status != 1) {
					alert(jsRet.mesg);
				} else {
                    console.log(jsRet.out);
					commission = jsRet.out[0].Commission;
					$('#commissionTxt').val(commission);
				}
		        $.mobile.loading('hide');
			},
			error: function(data, status, err) {
				console.log("error tr800300 : "+data);
		        $.mobile.loading('hide');
			}
		});
	}	

	//close 20150930
    function setInput_priceTxt() {
        var typingTimer;
        var doneTypingInterval = 1000;
		$("#priceTxt").keyup(function(e){
            if (this.value.length == 0) {
                $('#okBtn').button('disable');
                return;
            }

		    if (!isNaN(parseInt(this.value,10))) {
			    this.value = parseInt(this.value);
		    } else {
			    this.value = 1;
		    }
		    this.value = this.value.replace(/[^0-9]/g, '');

		    if (parseInt(this.value,10) <= 0) {
			    this.value = 1;
		    } else if (parseInt(this.value,10) > 1000000) {
			    this.value =999999;
		    }

			if (e.keyCode == 13) {
                getMaxQtyFromServer($('#codeTxt').val(), $('#priceTxt').val());
			}
            console.log("keyup : " + this.value);

            clearTimeout(typingTimer);
            if ($('#priceTxt').val) {
                typingTimer = setTimeout(function() {
                    console.log("fire : " + $('#priceTxt').val());
				    //priceCheck_onClick();
                    getMaxQtyFromServer($('#codeTxt').val(), $('#priceTxt').val());
                }, doneTypingInterval);
            }
            //priceCheck_enable_disable();
            $('#okBtn').button('disable');

			return;
        });
    }

    function getMaxQtyFromServer(code, price) { 
        $.mobile.loading('show');
        //tr100006(userId, code, price);
        tr800011(userId, code, price, "", "", "");
    }

    function getTotalAmountFromServer(code, price, lot) { 
        $.mobile.loading('show');
        //tr800011(userId, code, price, lot, oldOrdNo, pin, "");
        tr800011(userId, code, price, lot, "", "", "");
    }

    function priceCheck_enable_disable() {
        code = document.getElementById('codeTxt').value;
        price = document.getElementById('priceTxt').value.replace(',', '');
        lot = document.getElementById('lotTxt').value.replace(',', '');
        if  ((parseInt(price) > 0) && (parseInt(lot) > 0)) {
            $('#priceCheck').button('enable');
        } else {
            $('#priceCheck').button('disable');
        }
    }

    function okBtn_enable_disable() {
        total = Number($('#amountTxt').val().split(',').join(''));
        potRatio = Number($('#potRat').val());
        
        if (total > 0 && potRatio <= 60.0) {
            $('#okBtn').button('enable');
        } else {
            $('#okBtn').button('disable');
        }
    }

    function setInput_lotTxt() {
        var typingTimer;
        var doneTypingInterval = 1000;
		$("#lotTxt").keyup(function(e){
			if (this.value.length == 0) {
                $('#okBtn').button('disable');
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
			} else if (parseInt(this.value,10) > 50000) {
				this.value = 50000;
			}
			if (e.keyCode == 13) {
				priceCheck_onClick();
			}
            console.log("keyup lot : " + this.value);

            clearTimeout(typingTimer);
            if ($('#lotTxt').val) {
                typingTimer = setTimeout(function() {
                    console.log("fire : " + $('#lotTxt').val());
				    //priceCheck_onClick();
                    getTotalAmountFromServer($('#codeTxt').val(), $('#priceTxt').val(), $('#lotTxt').val());
                }, doneTypingInterval);
            }
            //priceCheck_enable_disable();
            $('#okBtn').button('disable');
            return;
		});
    }

    function setInput_okBtn() {
		$("#okBtn").click(function(){
            var code = $('#codeTxt').val();
            var price = $('#priceTxt').val().replace(',', '');
            var lot = $('#lotTxt').val().replace(',', '');
			var totalAmt = $("#amountTxt").val().replace(',', '');
            var totalAmt2 = $('#amountTxt').val();
            var high = $('#hprice').text().replace(',', '');
            var low = $('#lprice').text().replace(',', '');
			var isWork = '<?=$isWork?>';
			
			if(isWork == 0){
				alert("Holiday");
				window.location.href="index.php?uFlag=1";
				return;
			}

			if (code.indexOf("-R") != -1) {
				alert("To complete this transaction please contact our helpdesk(021-2358 7222)");
				location.reload();
				return;
			}

			if (code.length < 4 || price.length == 0 || lot.length == 0 ) {
				alert("Error Input. Check Again!");
				return;
			}
			
            if (price > uLimit) {
				alert("Cannot Order. Price cannot be more than " + uLimit + "!");
				return;
			}
				
            if (price < lLimit) {
				alert("Cannot Order. Price cannot be less than " + lLimit + "!");
				return;
			}

            // Check Fraction
			//ret = chkFraction();
			ret = checkFraction(code, price, Gprev, uLimit, lLimit);
			if (ret != 1) {
				alert("Cannot Order. Price is not valid!");
				return;
			}
			
			//check haircut
			//console.log("haircut = "+$('#haircut').val());
			if (Number($("#haircut").val()) == 100) {
				if (Number($("#netAc").val()) < Number(totalAmt)) {
					alert("Total Amount exceed your Net Account. Can not order. cancel (3).");
					return;
				}
			}
 			
			// Cek LOt > maxQty
            if (lot > Number($("#mQty").val())) {
			    alert("You Have Exceeded you Max Quantity, Please Reduce Quantity or Cancel Order");
				return;
			}

			console.log("Flag = "+flagB);
            // for avoiding too many times order
			if (flagB==0) {
				console.log("confirmation");
				confirmation("hello<br>hello");
				/*
				if ( confirm("UserId\t: "+userId +"\nCode\t: "+$("#codeTxt").val()+"\nPrice\t: "+$("#priceTxt").val()+"\nLot\t: "+$("#lotTxt").val()+"\nAmt\t: "+totalAmt2+"\n\nAre You Sure?"  ) ) {
					//$.mobile.loading('show');
					flagB++;
                    processOrder('Y');
                    tr189000(userId, $("#codeTxt").val(), "RG", $("#priceTxt").val(), $("#lotTxt").val());
				}
				*/
			} else {
				$.mobile.loading('show');
				if(flagB>10){
					window.location.href="orderList.php";
				}
				flagB++;
			}
		});

	}
	function confirmation(question) {
    	var defer = $.Deferred();
    	$("<div id='buy-confirm' style='z-index:1;'></div>")
        	.html(question)
        	.dialog({
            	autoOpen: true,
            	modal: true,
            	title: 'Confirmation',
            	buttons: {
                	"OK": function () {
                    	defer.resolve("true");//this text 'true' can be anything. But for this usage, it should be true or false.
                    	$(this).dialog("close");
                	},
                	"Cancel": function () {
                    	defer.resolve("false");//this text 'false' can be anything. But for this usage, it should be true or false.
                    	$(this).dialog("close");
                	}
            	},
            	open: function () {
                	$(this).siblings('.ui-dialog-buttonpane').find('button:eq(1)').focus();
            	},
            	close: function () {
                	$(this).remove();
            	}
        	});
    	return defer.promise();
	}
	
		function tr100011(code){
			$.ajax({
				type: "POST",
				url: "json/trproc.php",
				dataType: "json",
				data: "tr=100011&stock=" + code,
				success: function(jsRet){
					if (jsRet.status != 1){
						alert('There is an error with our server');
					} else {
						console.log(jsRet.out);
						if (jsRet.out[0]['code'] == 'A') {
						alert('Stock: ' + code + ' is not valid');
						location.reload();
						return;
						}
					}
				}
		});
	}

	function setInput() {
		$("#codeTxt").keyup(function(e){
			this.value = this.value.toUpperCase();
			if (e.keyCode == 13) {
				stockCheck_onClick();
			}
		    document.getElementById('priceTxt').disabled = true;
		    document.getElementById('lotTxt').disabled = true;
		});
		$('#stockCheck').click(function() {
			stockCheck_onClick();
		});

        $('#priceCheck').click(function() {
            priceCheck_onClick();
        });

        setInput_priceTxt();
        setInput_lotTxt();
        setInput_okBtn();
	}

    function checkFraction(code, price, prev, upper, lower) {
        var interval = 1; 
        var reks = false;
        var upper2 = upper;
        var lower2 = lower;

		if (code.substring(0,1) == 'X' || code.substring(0,2) == 'R-') {
            /*
			interval = 1;
			lower2 = lLimit;
			uLimit_baru = uLimit;
			reks = true;
            */
 		} else {
            /*
             if (prev < 200) {
                
		        interval = 1;
				lower2 = lLimit;
				uLimit_baru = uLimit;
                
		     }
			 else if ((prev >= 200) && (prev < 500)) {
			 	if(lower%2!=0){
					lower2 = lower-(lower%2)+2;
					upper2 = upper-(upper%2)-2;
				}
				else{
					lower2 = lower;
					upper2 = upper;
				}
				interval = 2;
			 }
			 else if ((prev >= 500) && (prev < 2000)) {
			 	if(lower%5!=0){
					lower2 = lower-(lower%5)+5;
					upper2 = upper-(upper%5)-5;
				}
				else{
					lower2 = lower;
					upper2 = upper;
				}
				interval = 5;
			 }

		     else if ((prev >= 2000) && (prev < 5000)) {
		         if(lower%10!=0){
		            lower2 = lower-(lower%10)+10;
				    upper2 = upper-(upper%10)-10;
			     }
				 else{
				 	lower2 = lower;
					upper2 = upper;
				 }
		         interval = 10;
		     }
		     else if (prev >= 5000) {
		         if(lower%25!=0){
		            lower2= lower-(lower%25)+25;
		            upper2= upper-(upper%25)-25;
			     }
				 else{
			        lower2 = lower;
			        upper2 = upper;
			     }
			     interval = 25;
			 }
             */
	     }
		fract = [];
		x = 0;
		for (i=Number(lower2); i<=Number(upper2); i++) {
			fract.push(x.toString());
			if (reks) {
				i = (i + 1) - 1;
				x = i;
			} else {
				if (prev < 200) {
					i = (i + 1) - 1;
					x = i;
                } else if ((prev >= 200) && (prev < 500)) {
				    i = (i + 2) - 1;
					x = (i + 1);
                } else if ((prev >= 500) && (prev < 2000)) {
				    i = (i + 5) - 1;
					x = (i + 1);
				} else if ((prev >= 2000) && (prev < 5000)) {
					i = (i + 10) - 1;
					x = (i + 1);
				} else if (prev >= 5000) {
					i = (i + 25) - 1;
					x = (i + 1);
				}
			}
		}
		if((price >= lower2) && (price <= upper2)) {
			if (code.substring(0,1) == 'X' || code.substring(0,2) == 'R-')
				ret = price % 1;
			else if(price > 5000)
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

            if (ret > 0) {
                return 0;
            } else {
                return 1;
            }
		} else {
			return 0;
		}
    }

	function chkFraction() {
		price = document.getElementById('priceTxt').value;
        
		interval = 1;

		//console.log("prevPrice: " + Gprev);
	
	    //alert("DISINI!!! : " + lLimit + " --- "+uLimit);
					
		var reks = false;

		if (code.substring(0,1) == 'X' || code.substring(0,2) == 'R-') {
			interval = 1;
			lLimit_baru = lLimit;
			uLimit_baru = uLimit;
			reks = true;
 		} else {
             if (Gprev < 200) {
		        interval = 1;
				lLimit_baru = lLimit;
				uLimit_baru = uLimit;
		     }
			 else if ((Gprev >= 200) && (Gprev < 500)) {
			 	if(lLimit%2!=0){
					lLimit_baru = lLimit-(lLimit%2)+2;
					uLimit_baru = uLimit-(uLimit%2)-2;
				}
				else{
					lLimit_baru = lLimit;
					uLimit_baru = uLimit;
				}
				interval = 2;
			 }
			 else if ((Gprev >= 500) && (Gprev < 2000)) {
			 	if(lLimit%5!=0){
					lLimit_baru = lLimit-(lLimit%5)+5;
					uLimit_baru = uLimit-(uLimit%5)-5;
				}
				else{
					lLimit_baru = lLimit;
					uLimit_baru = uLimit;
				}
				interval = 5;
			 }

		     else if ((Gprev >= 2000) && (Gprev < 5000)) {
		         if(lLimit%10!=0){
		            lLimit_baru = lLimit-(lLimit%10)+10;
				    uLimit_baru = uLimit-(uLimit%10)-10;
			     }
				 else{
				 	lLimit_baru = lLimit;
					uLimit_baru = uLimit;
				 }
		         interval = 10;
		     }
		     else if (Gprev >= 5000) {
		         if(lLimit%25!=0){
		            lLimit_baru= lLimit-(lLimit%25)+25;
		            uLimit_baru= uLimit-(uLimit%25)-25;
			     }
				 else{
			        lLimit_baru = lLimit;
			        uLimit_baru = uLimit;
			     }
			     interval = 25;
			 }
	     }

		//console.log("Interval: " + interval);

		// uLimit - lLimit
		fract = [];
		x = 0;
		for (i=Number(lLimit_baru); i<=Number(uLimit_baru); i++) {
			fract.push(x.toString());
			if (reks) {
				i = (i + 1) - 1;
				x = i;
			} else {
				if (Gprev < 200) {
					i = (i + 1) - 1;
					x = i;
                } else if ((Gprev >= 200) && (Gprev < 500)) {
				    i = (i + 2) - 1;
					x = (i + 1);
                } else if ((Gprev >= 500) && (Gprev < 2000)) {
				    i = (i + 5) - 1;
					x = (i + 1);
				} else if ((Gprev >= 2000) && (Gprev < 5000)) {
					i = (i + 10) - 1;
					x = (i + 1);
				} else if (Gprev >= 5000) {
					i = (i + 25) - 1;
					x = (i + 1);
				}/* else {
					i = (i + Number(interval)) - 1;
					x = (x + Number(interval)) - 1;
				}*/

			}
			//fract.push(x.toString());

		}
		 //alert("DISINI!!! : " + Gprev +" price: "+ price + " code: "+code+" -- llimit baru "+lLimit_baru+" ulimit baru "+uLimit_baru);
	 //alert("DISINI!!! : " +fract);
	 
		//console.log(fract);
/*
		for (i=0; i<fract.length; i++) {
			if (price == fract[i]) {
				return 1;
			}
		}
*/
		if((price >= lLimit_baru) && (price <= uLimit_baru)){
			if (code.substring(0,1) == 'X' || code.substring(0,2) == 'R-')
				ret = price % 1;
			else if(price > 5000)
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

	function tr189000(id, code, board, price, lot) {
		$.ajax({
			type: "POST",
			url: "json/trproc.php",
			dataType : "json",
			data: "tr=189000&userId=" + id + "&dealerId=" + id + "&pin=&code=" + code + "&mkt=" + board + "&price=" + price + "&qty=" + lot + "&lot=100" + "&type=0",
			success: function(jsRet){
				if (jsRet.status != 1) {
					alert(jsRet.mesg);
				} else {
                    console.log(jsRet);
					mesgFld = jsRet.out[0].mesg;

					alert(mesgFld);
					window.location.href="orderList.php";
					clearInput('Y');
				}
				//alert( "jsRet=" + JSON.stringify(jsRet) );
				$.mobile.loading('hide');
                processOrder('N');
			},
			error: function(data, status, err) {
				//console.log("error forward : "+data);
				$.mobile.loading('hide');
				alert('error communition with server.');
				window.location.replace("index.php?uFlag=1");
			}
		});
	}

	function clearInput(includeCode) {
        if (includeCode == 'Y')
		    document.getElementById('codeTxt').value = "";
		document.getElementById('priceTxt').value = "";
		document.getElementById('lotTxt').value = "";
		document.getElementById('mQty').value = "";
	}

    function tr800011(id, code, price, lot, oldOrdNo, pin, cash) {
		$.mobile.loading('show');
        $.ajax({
            type: "POST",
            url: "json/trproc.php", 
            dataType : "json", 
            data: "tr=800011&userId=" + id + "&code=" + code + "&price=" + price + "&lot=" + lot + "&oldOrdNo=" + oldOrdNo + "&pin=" + pin, 
            success: function(jsRet) {
                if (jsRet.status != 1) {
                    alert(jsRet.mesg);
                } else { 
                    console.log("800011 : code : " + code + " price : " + price + " lot : " + lot);
                    console.log(jsRet.out);
                    
                    potRatio = sprintf("%0.2f", Number(jsRet.out[0].potRatio) * 100);
                    $('#potRat').val(potRatio);

                    if (cash == 'Y') {
                        cash = sprintf("%d", Number(jsRet.out[0].cash)); 
                        $('#cashTxt').val(addCommas(cash));
                    } else {
                        NumMaxOrder = Number(jsRet.out[0].MaxOrderAmt);
                        NumFee = Number($('#commissionTxt').val());
                        NumMaxQty = Number(jsRet.out[0].maxLotQty);

                        /*
                        if (price == "" || price == "0") {
                            price = "1";
                            maxQty = 0;
                        } else {
                            NumPrice = Number(price);
                            maxQty = Math.ceil(NumMaxOrder / (NumPrice + (NumPrice * (NumFee/100))) / 100 - 1);
                        }
                        */

                        $('#mQty').val(addCommas(NumMaxQty));
                        calcTotal();
                    }
                }
		        $.mobile.loading('hide');
            },
            error: function(data, status, err) {
                console.log("error tr800011 : "+data);
		        $.mobile.loading('hide');
            }
        });
    }

    function tr100006(id, code, price) {
        $.ajax({
            type: "POST",
            url: "json/trproc.php",
            dataType : "json", 
            data: "tr=100006&userId=" + id + "&code=" + code + "&price=" + price,
            success: function(jsRet){
                if (jsRet.status != 1) {
                    alert(jsRet.mesg);
                } else {
                    console.log("100006: code : " + code + " price : " + price);
                    console.log(jsRet.out);
                    if (jsRet.out.length == 0)  {
		                $.mobile.loading('hide');
                        alert("Can not get Max Qty and Total Amount");
                        
                        $('#netAc').val("");
                        $('#maxQty').val("");
                        return;
                    } 
                    //maxAmt = Number(jsRet.out[0].maxAmt);
                    stockCommission = Number(jsRet.out[0].commission);
                    
                    //maxQty = sprintf("%.0f", Number(jsRet.out[0].maxQty));
                    //maxQty = (maxAmt / (Number(price) * (1.0 + stockCommission/100))) / 100 - 1.5;
                    
                    haircut = Number(jsRet.out[0].haircut) * 100;
                    netAc = Number(jsRet.out[0].netAc);
                    $('#netAc').val(netAc);
                    $('#haircut').val(haircut);
                    calcTotal();
                }
		        //$.mobile.loading('hide');
            }, 
            error: function(data, status, err) {
                console.log("error forward : "+data);i
		        //$.mobile.loading('hide');
            }
        });
    }

	function stockCheck_onClick() {
		code = document.getElementById('codeTxt').value;
		price = document.getElementById('priceTxt').value = "";
		amountTxt = document.getElementById('amountTxt').value = "";
		lotTxt = document.getElementById('lotTxt').value = "";
        $('#okBtn').button('disable');

		if (code.indexOf("-R") != -1){
			alert("To complete this transaction please contact our helpdesk(021-2358 7222)");
			location.reload();
		} else {
			tr100011(code);
			qryTr100000();
            tr100006(userId, code, "1");        // for getting haircut, fee only
            clearInput('');
		}
	}

    function priceCheck_onClick() {
        code = document.getElementById('codeTxt').value;
        price = document.getElementById('priceTxt').value.replace(',', '');
        lot = document.getElementById('lotTxt').value.replace(',', '');
        oldOrdNo = "";
        pin = "";
        if (price != "" && lot != '') 
            tr800011(userId, code, price, lot, oldOrdNo, pin, "");
    }

</script>
</head> 
<body>
	<div data-role="page" id="home" data-cache="never">
	<? include "page-header.php" ?>
	<div class="buy-panel">
	<!-- <b>Buy Order</b> -->
	<table width=320px border=0>
		<tr><td colspan="3"><h2 style="margin-bottom: 0px;">Buy Order</h2>
			<input id="commissionTxt" type="hidden" class="no-ime" data-mini="true"></td>
		</tr>
		<tr>
			<td width="15%">Code</td>
			<td width="25%" colspan="1"><input id="codeTxt" type="text" class="no-ime" data-mini="true" maxlength="7" /></td>
            <td witdh="5%">
                <input type="button" data-icon="check" data-iconpos="notext" name="stockCheck" id="stockCheck" ></td>
			<td width="45%">
                <table border=0><tr><td>HC(%)</td><td><input id="haircut" type="text" disabled="disabled" data-mini="true" maxlength="5"/></td></td></tr></table>
                    
                </td>
		</tr>
		<tr>
			<td width="">Price</td>
			<td width=""><input id="priceTxt" type="text" class="no-ime" data-mini="true" maxlength="6" /></td>
            <td></td>
			<td width="">
                <table><tr><td>P.Rat(%)</td><td><input id="potRat" type="text" disabled="disabled" data-mini="true" /><input id="netAc" type="hidden" data-mini="true" /></td></tr></table>
                </td>
		</tr>
		<tr>
			<td width="" id="lotMaxQty">Lot</td>
			<td width=""><input id="lotTxt" type="text" class="no-ime" data-mini="true" /></td>
			<!--td width=""><input type="button" data-icon="check" data-iconpos="notext" name="priceCheck" id="priceCheck" disabled="disabled" /></td-->
			<td width=""></td>
			<td width="">
                <table>
                <tr><td>Max.Qty</td><td><input id="mQty" type="text" disabled="disabled" maxlength="6" data-mini="true" /></td></tr></table>
                </td>
		</tr>
		<tr>
			<td width="" id="cashAvailable">Cash Avail</td>
			<td width="" colspan="3"><input id="cashTxt" type="text" disabled="disabled" class="no-ime" data-mini="true" /></td>
		</tr>
		<tr>
			<td width="" id="total">Total</td>
			<td width="" colspan="3"><input id="amountTxt" type="text" disabled="disabled" class="no-ime" data-mini="true" /></td>
		</tr>
		
		<!-- close -->
		<tr>
			<td colspan="4">
				<input type="button" data-icon="check" name="okBtn" id="okBtn" value="Order" data-mini="true" disabled="disabled">
			</td>
		</tr>
	</table>
	<table border=0 id="curPrice" width=320px>
		<thead>
			<th colspan="4" id="price" class="lprice"></th>
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
</body>
</html>
