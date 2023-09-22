var prevPrice = 0;
var curPrice = 0;

function vol2lot(qty) {
	return qty / 100;
}

var bidFract = [];
var offFract = [];

function tr100001(code, board) {
	$.ajax({
		type: "POST",
		url: "json/trproc.php",
		dataType : "json",
		data: "tr=100001&code=" + code + "&board=" + board,
		success: function(jsRet){
			var color = [];
			color["bidup"] = [];
			color["biddown"] = [];
			color["offup"] = [];
			color["offdown"] = [];
			color["bg"] = [];

			if (jsRet.status != 1) {
				alert(jsRet.mesg);
			} else {
				//console.log(jsRet.out[0])
				//
				var bidAdd = "";
				var offAdd = "";
				rows = [];
				bidFract.length = 0;
				offFract.length = 0;
				for (i = 1; i <= 11; i++) {
				// global fraction array
					if (i < 11) {
						bidF = jsRet.out[0]["bid" + i];
						offF = jsRet.out[0]["off" + i];
						bidFract.push(bidF);
						offFract.push(offF);
					}
				//
					if (i > 5) {
						continue;
					}
					//
					if (i == 11) {
						id_v = i;
						bidAdd = "";
						bidVol = jsRet.out[0]["bidVolSum"];
						bidPrc = "SUM";
						offPrc = "SUM";
						offVol = jsRet.out[0]["offVolSum"];
						offAdd = "";
					}
					else {
						id_v = i;
						bidAdd = jsRet.out[0]["bid" + i + "a"];
						bidVol = jsRet.out[0]["bid" + i + "v"];
						bidPrc = jsRet.out[0]["bid" + i];
						offPrc = jsRet.out[0]["off" + i];
						offVol = jsRet.out[0]["off" + i + "v"];
						offAdd = jsRet.out[0]["off" + i + "a"];
					}
					
					if (bidAdd == undefined) {
						bidAdd = "";
					}
					if (offAdd == undefined) {
						offAdd = "";
					}

					// Coloring
					if (bidPrc > prevPrice) {
						color["bidup"].push(i-1);
					}
					else if (bidPrc < prevPrice) {
						color["biddown"].push(i-1);
					}

					if (offPrc > prevPrice) {
						color["offup"].push(i-1);
					}
					else if (offPrc < prevPrice) {
						color["offdown"].push(i-1);
					}
					if (bidPrc == curPrice ) {
						color["bg"] = 2;
					}
					else if (offPrc == curPrice) {
						color["bg"] = 3;
					}
					//

					//
					cell_v = [
						bidAdd,
						bidVol / 100,
						bidPrc,
						offPrc,
						offVol / 100,
						offAdd
						];
					row = {id:id_v, cell:cell_v};
					rows.push(row);
				}
				args = {dataType:"json", rows:rows, page:1, total:11};
				$('#r-quote').flexAddData(args);
				/*
				var r = document.getElementById("r-quote").getElementsByTagName('tr');
				var c = r[10].getElementsByTagName('td');
				jQuery( c[2] ).css("background-color", "#cccccc");
				jQuery( c[3] ).css("background-color", "#cccccc");
				
				for (i = 0; i < 10; i++) {
					var clm = r[i].getElementsByTagName('td');
					jQuery( clm[1] ).css("background-color", "#ffb6c1");
					jQuery( clm[4] ).css("background-color", "#afeeee");
				}
				*/

				//
				var r = document.getElementById("r-quote").getElementsByTagName('tr');
				var c = r[0].getElementsByTagName('td');
				jQuery( c[color["bg"]] ).css("background-color", "#ffff00");

				for (i = 0; i < color["bidup"].length; i++) {
					var r = document.getElementById("r-quote").getElementsByTagName('tr');
					var c = r[color["bidup"][i]].getElementsByTagName('td');
					jQuery( c[1] ).css("color", "#0000ff");
					jQuery( c[2] ).css("color", "#0000ff");
				}
				for (i = 0; i < color["biddown"].length; i++) {
					var r = document.getElementById("r-quote").getElementsByTagName('tr');
					var c = r[color["biddown"][i]].getElementsByTagName('td');
					jQuery( c[1] ).css("color", "#ff0000");
					jQuery( c[2] ).css("color", "#ff0000");
				}
				for (i = 0; i < color["offup"].length; i++) {
					var r = document.getElementById("r-quote").getElementsByTagName('tr');
					var c = r[color["offup"][i]].getElementsByTagName('td');
					jQuery( c[3] ).css("color", "#0000ff");
					jQuery( c[4] ).css("color", "#0000ff");
				}
				for (i = 0; i < color["offdown"].length; i++) {
					var r = document.getElementById("r-quote").getElementsByTagName('tr');
					var c = r[color["offdown"][i]].getElementsByTagName('td');
					jQuery( c[3] ).css("color", "#ff0000");
					jQuery( c[4] ).css("color", "#ff0000");
				}
				//

			}
			//alert( "jsRet=" + JSON.stringify(jsRet) );
		},
		error: function(data, status, err) {
			console.log("error forward : "+data);
			//alert('error communition with server.');
		}
	});
}

var uLimit = 0;
var lLimit = 0;
var Gprev = 0;

function tr100000(code, board) {
	$.ajax({
		type: "POST",
		url: "json/trproc.php",
		dataType : "json",
		data: "tr=100000&code=" + code + "&board=" + board,
		success: function(jsRet){
			if (jsRet.status != 1) {
				alert(jsRet.mesg);
			} else {
				//console.log(jsRet.out[0]);
				// ulimit - llimit
				uLimit = Number(jsRet.out[0].ulimit);
				lLimit = Number(jsRet.out[0].llimit);
				Gprev = Number(jsRet.out[0].prev);
				//
				//console.log("Code: " + jsRet.out[0].code + " | Price: " + jsRet.out[0].price);
				if (jsRet.out.length < 1) return;
				$("#price").text(addCommas(jsRet.out[0].price));
				var chgV = getChgV(jsRet.out[0].chgP, jsRet.out[0].chg);
				$("#chg").text(chgV + "(" + jsRet.out[0].chgR + "%)");
				//
				$("#value").text(addCommas(sprintf("%.0f", Number(jsRet.out[0].val) / 1000000.0)));
				$("#volume").text(addCommas(vol2lot(jsRet.out[0].vol)));
				$("#freq").text(addCommas(jsRet.out[0].freq));
				//
				$("#oprice").text(addCommas(jsRet.out[0].open));
				$("#hprice").text(addCommas(jsRet.out[0].high));
				$("#lprice").text(addCommas(jsRet.out[0].low));
				$("#prev").text(addCommas(jsRet.out[0].prev));
				//
				//console.log("haircut = "+jsRet.out[0].haircut);

				prevPrice = Number(jsRet.out[0].prev);
				curPrice = Number(jsRet.out[0].price);
				chg = Number(getChgV(jsRet.out[0].chgP, jsRet.out[0].chg));
				chgR = Number(jsRet.out[0].chgR);
				open = Number(jsRet.out[0].open);
				value = Number(sprintf("%.0f", Number(jsRet.out[0].val) / 1000000.0));
				high = Number(jsRet.out[0].high);
				low = Number(jsRet.out[0].low);
				volume = Number(vol2lot(jsRet.out[0].vol));
				freq = Number(jsRet.out[0].freq);
                        
				if(curPrice > prevPrice ){
				   $('#price').css('color','#0000ff');
				}else if(curPrice < prevPrice) {
				   $('#price').css('color','#ff0000');
				}else{
				   $('#price').css('color','#000000');
				}

			//	curPrice > 0 ? $('#price').css('color', '#0000ff') : $('#price').css('color', '#ff0000');
				chgR > 0 ? $('#chg').css('color', '#0000ff') : $('#chg').css('color', '#ff0000');
				open > prevPrice ? $('#oprice').css('color', '#0000ff') : $('#oprice').css('color', '#ff0000');
				high > prevPrice ? $('#hprice').css('color', '#0000ff') : $('#hprice').css('color', '#ff0000');
				low > prevPrice ? $('#lprice').css('color', '#0000ff') : $('#lprice').css('color', '#ff0000');
				value > prevPrice ? $('#value').css('color', '#0000ff') : $('#value').css('color', '#ff0000');
				volume > prevPrice ? $('#volume').css('color', '#0000ff') : $('#volume').css('color', '#ff0000');
				freq > prevPrice ? $('#freq').css('color', '#0000ff') : $('#freq').css('color', '#ff0000');

				// Last Price
				//$("#priceTxt").val(curPrice);
				tr800011(userId, code, curPrice, "", "", "");
				tr100006(userId, code, curPrice);
				// alert( "HAIRCUT= " + $('#haircut').val() ); 
			}
			tr100001(code, board);
			//alert( "jsRet=" + JSON.stringify(jsRet) );
		},
		error: function(data, status, err) {
			console.log("error forward : "+data);
			//alert('error communition with server.');
		}
	});
	//clearTimeout();
	//timerUpd();
}
function qryTr100000() {
	var code = $("#codeTxt").val();
	code = code.toUpperCase();
	$("#codeTxt").val(code);
	tr100000(code, "RG");
	tr100010(code);
}
function tr100010(code) {
	$.ajax({
		type: "POST",
		url: "json/trproc.php",
		dataType: "json",
		data: "tr=100010&code="+code,
		success:function(jsRet){
			if(jsRet.status !=1){
				alert(jsRet.mesg);
			}else{
				//console.log("Haircut tr baru = "+jsRet.out[0].haircut);
				//console.log("Haircut tr baru = "+jsRet.out[0].StockName);
				$("#haircut").val(addCommas(jsRet.out[0].haircut));
				$("#company").text(jsRet.out[0].StockName);
			}
		},
		error:function(data,status,err){
			console.log("error forward : "+data);

		}
	});	
}
function bidOrder(celDiv, id) {
}

function offOrder(celDiv, id) {
}

function timerUpd() {
	setTimeout(qryTr100000, 10000)
}

$(document).ready(function() {
	jQuery('div').live('pagehide', function(event, ui){
		var page = jQuery(event.target);
		if(page.attr('data-cache') == 'never'){
			page.remove();
		};
	});
	//
	$("#codeTxt").keyup(function(event) {
		if (event.keyCode == 13) {
			qryTr100000();
		}
	});
	// flexigrid
	args = {
		//title : "Order Book",
		dataType : "json",
		height : "auto",
		singleSelect : false,
		colModel : [
			{display:"Add", width:30, sortable:false, align:'center'},
			{display:"Bid(v)", width:50, sortable:false, align:'right'},
			{display:"Bid", width:40, sortable:false, align:'center', process:bidOrder},
			{display:"Off", width:40, sortable:false, align:'center', process:offOrder},
			{display:"Off(v)", width:50, sortable:false, align:'left'},
			{display:"Add", width:30, sortable:false, align:'center'},
		],
	};
	$('#r-quote').flexigrid(args);
});

var GmaxAmt = 0;
var Gcommission = 0;
// Calc
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
				//console.log("100006");
				//console.log(jsRet.out);
				//GmaxAmt = Number(jsRet.out[0].maxAmt);
				Gcommission = Number(jsRet.out[0].commission);
				GhairCut = (100*Number(jsRet.out[0].haircut));
				GnetAc = sprintf("%.0f",Number(jsRet.out[0].netAc));
				//maxQty = sprintf("%.0f", Number(jsRet.out[0].maxQty));
				$("#haircut").val(GhairCut);
				//$("#mQty").val(maxQty);
				$("#netAc").val(GnetAc);
			}
		},
		error: function(data, status, err) {
			console.log("error forward : "+data);
		}
	});
}

var lot = 1;
var oldOrdNo = 0;
var pin = '1223';
function tr800011(id, code, price, lot, oldOrdNo,pin) {
	$.ajax({
		type: "POST",
		url: "json/trproc.php",
		dataType : "json",
		data: "tr=800011&userId=" + id + "&code=" + code + "&price=" + price + "&lot=" + lot + "&oldOrdNo=" + oldOrdNo + "&pin=" + pin,
		success: function(jsRet){
			if (jsRet.status != 1) {
				alert(jsRet.mesg);
			} else {
				//console.log("tr800011 out=" + jsRet.out);
				GmaxAmt = Number(jsRet.out[0].MaxOrderAmt);
				//Gcommission = Number(jsRet.out[0].commission);
				//GhairCut = (100*Number(jsRet.out[0].haircut));
				//GnetAc = sprintf("%.0f",Number(jsRet.out[0].netAc));
				maxQty = sprintf("%.0f", Number(jsRet.out[0].MaxOrderAmt) / (Number(price)*100));
				//$("#haircut").val(GhairCut);
				$("#mQty").val(maxQty);
				//$("#netAc").val(GnetAc);
			}
		},
		error: function(data, status, err) {
			console.log("error forward : "+data);
		}
	});
}
