var prevPrice = 0;
var curPrice = 0;

function vol2lot(qty) {
	return qty / 100;
}
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
				for (i = 1; i <= 11; i++) {
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
				//console.log(args);
				var r = document.getElementById("r-quote").getElementsByTagName('tr');
				var c = r[10].getElementsByTagName('td');
				jQuery( c[2] ).css("background-color", "#cccccc");
				jQuery( c[3] ).css("background-color", "#cccccc");
				/*
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

				curPrice > 0 ? $('#price').css('color', '#0000ff') : $('#price').css('color', '#ff0000');
				chgR > 0 ? $('#chg').css('color', '#0000ff') : $('#chg').css('color', '#ff0000');
				open > prevPrice ? $('#oprice').css('color', '#0000ff') : $('#oprice').css('color', '#ff0000');
				high > prevPrice ? $('#hprice').css('color', '#0000ff') : $('#hprice').css('color', '#ff0000');
				low > prevPrice ? $('#lprice').css('color', '#0000ff') : $('#lprice').css('color', '#ff0000');
				value > prevPrice ? $('#value').css('color', '#0000ff') : $('#value').css('color', '#ff0000');
				volume > prevPrice ? $('#volume').css('color', '#0000ff') : $('#volume').css('color', '#ff0000');
				freq > prevPrice ? $('#freq').css('color', '#0000ff') : $('#freq').css('color', '#ff0000');

			}
			tr100001(code, board);
			//alert( "jsRet=" + JSON.stringify(jsRet) );
		},
		error: function(data, status, err) {
			console.log("error forward : "+data);
			//alert('error communition with server.');
		}
	});
	timerUpd();
}
function qryTr100000() {
	var code = $("#codeTxt").val();
	code = code.toUpperCase();
	$("#codeTxt").val(code);
	tr100000(code, "RG");
}

function bidOrder(celDiv, id) {
	/*
	$(celDiv).click(function() {
		idNum = parseInt(id.substr(3));
		if (idNum >= 11) {
			//alert(celDiv + ":" + id.substr(3));
			code = $("#codeTxt").val();
			price = $("#row" + id + " td:nth-child(5) div").html();
			qty = 1;
			//alert("buy order=" + sprintf("%s,%s,%d", code, price, qty));
			goBuy(code, parseInt(price), qty);
		}
	});
	*/
}

function offOrder(celDiv, id) {
}

function timerUpd() {
	setTimeout(qryTr100000, 5000)
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
	//
	$("#okBtn").click(qryTr100000);
	//
	var code = $("#codeTxt").val();
	code = code.toUpperCase();
	$("#codeTxt").val(code);
	tr100000(code, "RG");
	// flexigrid
	args = {
		title : "Order Book",
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
		/*
		buttons : [
			{name:'Refresh', bclass:'refresh', onpress:qryTr100000},
			{separator: true}
		]
		*/
	};
	$('#r-quote').flexigrid(args);
});

