var totLq = 0;
var ordList = 0;
var gHc = 0;
var netAc = 0;
var maxAmt = 0;
var feeB = 0.0015;
var feeS = 0.0025;

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
				/*
				console.log(jsRet.out);
				console.log(jsRet.out2);
				*/
				rows = [];
				cnt = jsRet.out.length;
				var ops_exist = 0;
				//
				for (i = 0; i < cnt; i ++) {
					//
					jsRet.out[i].lqVal = Number(jsRet.out[i].mktVal) * (1.0 - Number(jsRet.out[i].hairCut));
					jsRet.out[i].hairCut = Number(jsRet.out[i].hairCut) * 100.0;
					//
					lprc = jsRet.out[i].mktPrice;
					hc = jsRet.out[i].hairCut;
					sval = sprintf("%.2f", Number(jsRet.out[i].lqVal));
					ops = sprintf("%.0f", Number(jsRet.out[i].offer));
					//console.log(lprc+"|"+hc+"|"+sval+"|"+ops);
					//
					if (ops != "0") {
						ops_exist = Number(ops) * 100 * Number(lprc) * (1.0 - Number(hc/100));
					} else {
						ops_exist = 0;
					}
					totLq = totLq + Number(sval) + ops_exist;
					gHc = hc/100;
				}
				console.log("totLq/lvPort: " + totLq + "| haircut: " + gHc + "| netAc: " + netAc);
			}
		}
	});
}


function tr182600(id, date) {
	$.ajax({
		type: "POST",
		url: "json/trproc.php",
		dataType: "json",
		data: sprintf("tr=182600&userId=%s&cliId=%s&pin=&code=&board=&bsFlag=%s&status=%s&from=%s&to=%s&cnt=500&keyDate=&keyOrderNo=999999999&price=",id, id, 0, status, date, date),
		success: function(jsRet) {
			if (jsRet.status != 1) {
				alert(jsRet.mesg);
			} else {
				//console.log(jsRet.out);
				//console.log(jsRet.out2);
				//
				cnt = jsRet.out.length;

				for (i = 0; i < cnt; i++) {
					if (jsRet.out[i].status == "O" && jsRet.out[i].type == "B") {
						price = jsRet.out[i].price;
						qty = jsRet.out[i].qty;
						code = jsRet.out[i].code;
						hc = jsRet.out[i].hairCut;
						lqRate = 1.00 - Number(hc);

						ordList = ordList + Number(price) * Number(qty) * Number(lqRate);
						//console.log(ordList + " = > " + price + " * " + qty + " * " + hc);
						//
					}
				}
				console.log("ordList/lvOrder: " + ordList);
				//
			}
		},
		error: function(data, status, err) {
			console.log("error : "+data);
		}
	});
}

function tr800000(user, client, date) {
	$.ajax({
		type: "POST",
		url: "json/trproc.php",
		dataType: "json",
		data: "tr=800000&userID=" + user + "&clientID=" + client + "&date=" + date,
		success: function(jsRet) {
			if (jsRet.status != 1) {
				alert(jsRet.mesg);
			} else {
				//console.log(jsRet.out[0]);
				openbuy = sprintf("%.0f", Number(jsRet.out[0].bid));
				cash = sprintf("%d", Number(jsRet.out[0].cashBalance) - Number(openbuy));

				netAc = Number(cash);
				ratio = 0.6;
				lqRate = 1.0 - gHc;
				maxAmt = ((ratio * totLq) + (ratio * ordList) + netAc) / (1.0 - (ratio * lqRate));
				console.log("netAc: " + netAc);
				console.log("maxAmt: " + maxAmt);
			}
		},
		error: function(data, status, err) {
			console.log("error forward : "+data);
		}
	});
}

function tr800300(id, pin) {
	$.ajax({
		type: "POST",
		url: "json/trproc.php",
		dataType : "json",
		data: "tr=800300&userId=" + id + "&pin=" + pin,
		success: function(jsRet){
			if (jsRet.status != 1) {
				alert(jsRet.mesg);
			} else {
				//
				cms = jsRet.out[0].Commission;

				feeB = Number(cms);
				feeB == 0 ? feeB = 0.0015 : feeB;
				feeS = feeB + 0.0010;
				console.log("feeB: " + feeB + "| feeS: " + feeS);
				//
			}
		},
		error: function(data, status, err) {
			console.log("error forward : "+data);
			//alert('error communition with server.');
		}
	});
}
