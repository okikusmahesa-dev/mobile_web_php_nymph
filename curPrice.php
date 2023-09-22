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
//$_SESSION["url"]="https://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
$userId = $_SESSION["userId"];
?>

<html>
<head> 
	<title>Order Book</title> 
	<meta name="viewport" content="width=device-width, initial-scale=1"> 
	<? include "inc-common.php" ?>
	<style type="text/css">
	<? include "css/icon.css" ?>
	<? include "css/curPrice.css" ?>
	</style>
	<script type="text/javascript">
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
						//console.log("tr100001 code: "+ code);
						console.log("tr100001 code: "+ code);
						console.log(jsRet.out[0]);
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
							//console.log(cell_v);
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
			timerUpd();
		}
		function tr100000(code, board) {
			// hichang modified 2017.04.08
			//if (code.includes("-R")){
			if (code.indexOf("-R") != -1){
			//	console.log("RIGHT");
				board = "TN";
			}else{
			//	console.log("FAILED");
			}
			$.ajax({
				type: "POST",
				url: "json/trproc.php",
				dataType : "json",
				data: "tr=100000&code=" + code + "&board=" + board,
				success: function(jsRet){
					if (jsRet.status != 1) {
						alert(jsRet.mesg);
					} else {
						console.log("tr100000 code: "+ code);
						console.log(jsRet.out);
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
                        //IEP IEV
                        $("#ie_price").text(addCommas(jsRet.out[0].iePrice));
                        $("#ie_volume").text(addCommas(Number(jsRet.out[0].ieVolume)/ 100));

						// hichang
						$("#haircut").text(addCommas(jsRet.out[0].haircut));

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
                        iePrice = Number(jsRet.out[0].iePrice);
                        ieVolume = Number(jsRet.out[0].ieVolume );

						if(curPrice > prevPrice ){
							$('#price').css('color','#0000ff');
						}else if(curPrice < prevPrice) {
							$('#price').css('color','#ff0000');
						}else{
							$('#price').css('color','#000000');
						}
						//curPrice > prevPrice ? $('#price').css('color', '#0000ff') : $('#price').css('color', '#ff0000');
						chgR > 0 ? $('#chg').css('color', '#0000ff') : $('#chg').css('color', '#ff0000');
						open > prevPrice ? $('#oprice').css('color', '#0000ff') : $('#oprice').css('color', '#ff0000');
						high > prevPrice ? $('#hprice').css('color', '#0000ff') : $('#hprice').css('color', '#ff0000');
						low > prevPrice ? $('#lprice').css('color', '#0000ff') : $('#lprice').css('color', '#ff0000');
						value > prevPrice ? $('#value').css('color', '#0000ff') : $('#value').css('color', '#ff0000');
						volume > prevPrice ? $('#volume').css('color', '#0000ff') : $('#volume').css('color', '#ff0000');
						freq > prevPrice ? $('#freq').css('color', '#0000ff') : $('#freq').css('color', '#ff0000');
                        iePrice > prevPrice ? $('#ie_price').css('color', '#0000ff') : $('#ie_price').css('color', '#ff0000');
                        ieVolume > prevPrice ? $('#ie_volume').css('color', '#0000ff') : $('#ie_volume').css('color', '#ff0000');

					}
					tr100001(code, board);
					//alert( "jsRet=" + JSON.stringify(jsRet) );
				},
				error: function(data, status, err) {
					console.log("error forward : "+data);
					//alert('error communition with server.');
				}
			});
			//timerUpd();
		}
		function qryTr100000() {
			var code = $("#codeTxt").val();
			code = code.toUpperCase();
			$("#codeTxt").val(code);
			$.mobile.loading('show');
			tr100000(code, "RG");
			//tr100011(code);
			$.mobile.loading('hide');
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
			setTimeout(qryTr100000, 5 * 1000)      // 10 sec
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
						console.log(jsRet.out2);
						$("#remarks2").text(jsRet.out2[0].remark);
						cnt = jsRet.out.length;
						var list = document.getElementById("listview");
						while(list.hasChildNodes()){
							list.removeChild(list.firstChild);
						}

						for (i = 0; i < cnt; i++){
							var node = document.createElement("LI");
							node.className = "ui-li ui-li-static ui-btn-up-d";
							var textnode = document.createTextNode(jsRet.out[i].code + " : "+ jsRet.out[i].desc);
							node.appendChild(textnode);
							document.getElementById("listview").appendChild(node);

						}

					}
				}
			});
		}

		function qryTr100011(){
			var code = $("#codeTxt").val();
            code = code.toUpperCase();
            $("#codeTxt").val(code);
			tr100011(code);
		}
		
		function qryStock(){
			qryTr100000();
			qryTr100011();
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
					qryStock();
					//qryTr100000();
					//qryTr100011();
				}
			});
			//
			//$("#okBtn").click(qryTr100000);
			$("#okBtn").click(qryStock);
			//
			var code = $("#codeTxt").val();
			code = code.toUpperCase();
			$("#codeTxt").val(code);
			tr100000(code, "RG");
			tr100011(code);
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
	</script>
</head> 
<body style="background:#ffffff;">
<div data-role="page" id="home" data-cache="never">
<? include "page-header.php" ?>
	<div>
		<input id="codeTxt" type="text" value="BBCA" maxlength="7" style="float:left;width:100;text-align:center;ime-mode:disabled;text-transform:uppercase;" data-mini="true"/>
		<a id="okBtn" href="#" data-role="button" data-icon="refresh" data-mini="true" style="float:right;width:100;">Refresh</a>
		<center style="padding-top :10px">
		<label id="price" class="lprice" style="font-size:20px"></label>
		</center>
	</div>
	<br><br><table >
		<tr>
			<td>
				<p style="font-size : 11; min-height:10px">Info Remarks : 
					<a href="#remarks" data-rel="popup" data-inline="true" data-transition="fade" data-theme="b"><span id="remarks2">[]</span></a>
				</p>
				<div data-role="popup" id="remarks" data-theme="b" style="top:80px;font-size:10px;">
					<ul data-role="listview" data-inset="true" style="min-width:210px;" data-theme="d" id="listview">
					</ul>
				</div>
			</td>
			<td>
				<a href="#note" style="font-size : 11; min-height:10px" data-rel="popup" data-inline="true" data-transition="fade" data-theme="b">
						<span id="note2">Note Description</span></a>
				<div data-role="popup" id="note" data-theme="b" style="top:80px;font-size:10px;width:40%;">
						<table border="1" data-role="table" class="ui-responsive grid-def"  width="100%">
							<thead>
								<tr>
			                		<th style="font-size : 11">Note</th>
			                		<th style="font-size : 11">Description </th>
			                	</tr>
							</thead>
						<tbody>
						<tr>
							<td align="center" width="1%" style="font-size : 11">B</td>
							<td width="50%" style="font-size : 11">Adanya permohonan Pernyataan Pailit</td>
						</tr>
						<tr>
							<td align="center" width="1%" style="font-size : 11">M</td>
							<td width="50%" style="font-size : 11">Adanya permohonan Penundaan Kewajiban Pembayaran Utang (PKPU)</td>
						</tr>
						<tr>
							<td align="center" width="1%" style="font-size : 11">E</td>
							<td width="50%" style="font-size : 11">Laporan keuangan terakhir menunjukkan ekuitas negatif</td>
						</tr>
						<tr>
							<td align="center" width="1%" style="font-size : 11">A</td>
							<td width="50%" style="font-size : 11">Adanya Opini Tidak Wajar (Adverse) dari Akuntan Publik</td>
						</tr>
						<tr>
							<td align="center" width="1%" style="font-size : 11">D</td>
							<td width="50%" style="font-size : 11">Adanya Opini Tidak Menyatakan Pendapat (Disclaimer) dari Akuntan Publik</td>
						</tr>
						<tr>
							<td align="center" width="1%" style="font-size : 11">L</td>
							<td width="50%" style="font-size : 11">Perusahaan Tercatat belum menyampaikan laporan keuangan</td>
						</tr>
						<tr>
							<td align="center" width="1%" style="font-size : 11">S</td>
							<td width="50%" style="font-size : 11">Laporan keuangan terakhir menunjukkan tidak ada pendapatan usaha</td>
						</tr>
						<tr>
							<td align="center" width="1%" style="font-size : 11">C</td>
							<td width="50%" style="font-size : 11">Kejadian perkara hukum terhadap Perusahaan Tercatat, Anak Perusahaan Tercatat dan/atau anggota Direksi dan anggota Dewan Komisaris Perusahaan Tercatat yang berdampak Material</td>
						</tr>
						<tr>
							<td align="center" width="1%" style="font-size : 11">Q</td>
							<td width="50%" style="font-size : 11">Pembatasan kegiatan usaha Perusahaan Tercatat dan/atau Anak Perusahaan Tercatat oleh regulator</td>
						</tr>
						<tr>
							<td align="center" width="1%" style="font-size : 11">Y</td>
							<td width="50%" style="font-size : 11">Perusahaan Tercatat yang belum menyelenggarakan Rapat Umum Pemegang Saham Tahunan (RUPST) sampai dengan 6 (enam) bulan setelah tahun buku berakhir</td>
						</tr>
						<tr>
							<td align="center" width="1%" style="font-size : 11">F</td>
							<td width="50%" style="font-size : 11">Sanksi Administratif dan/atau Perintah Tertulis dari OJK yang dikenakan terhadap Perusahaan Tercatat karena pelanggaran peraturan di bidang Pasar Modal dengan kategori Pelanggaran Ringan</td>
						</tr>
						<tr>
							<td align="center" width="1%" style="font-size : 11">G</td>
							<td width="50%" style="font-size : 11">Sanksi Administratif dan/atau Perintah Tertulis dari OJK yang dikenakan terhadap Perusahaan Tercatat karena pelanggaran peraturan di bidang Pasar Modal dengan kategori Pelanggaran Sedang</td>
						</tr>
						<tr>
							<td align="center" width="1%" style="font-size : 11">V</td>
							<td width="50%" style="font-size : 11">Sanksi Administratif dan/atau Perintah Tertulis dari OJK yang dikenakan terhadap
							Perusahaan Tercatat karena pelanggaran peraturan di bidang Pasar Modal dengan kategori Pelanggaran Berat</td>
						</tr>
						<tr>
							<td align="center" width="1%" style="font-size : 11">X</td>
							<td width="50%" style="font-size : 11">Sanksi Administratif dan/atau Perintah Tertulis dari OJK yang dikenakan terhadap Perusahaan Tercatat karena pelanggaran peraturan di bidang Pasar Modal dengan kategori Pelanggaran Berat</td>
						</tr>
						<tr>
							<td align="center" width="1%" style="font-size : 11">N</td>
							<td width="50%" style="font-size : 11">Perusahaan tercatat merupakan Emiten yang menerapkan Saham dengan Hak Suara Mutipel</td>
						</tr>

					</tbody>
					</table>
				</div>
			</td>
	</table>
		

	<div data-role="main">
	<table border="1" data-role="table" class="ui-responsive grid-def" id="curPrice" width="100%">
		<thead>
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
		<tr>
			<td class="r-cell">IEP</td><td id="ie_price" class="r-qty"></td>
			<td class="r-cell">IEV</td><td id="ie_volume" class="r-qty"></td>
		</tr>
		</tbody>
	</table>
	</div>
	<table id="r-quote" class="flexigrid">
		<thead>
		</thead>
		<tbody>
		</tbody>
	</table>
<? include "page-footer.php" ?>
</div>
</body>
</html>
