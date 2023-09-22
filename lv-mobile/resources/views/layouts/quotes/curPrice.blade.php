<?php
if(Session::get('isLogin') != 1){
    $server = $_SERVER["SERVER_NAME"];
    header("Location: http://$server:8000/login");
    exit(0);
}
?>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="viewPort" content="width=device-width, initial-scale=1.0" />
<head>
    <meta charset="utf-8">
	<title>BEST Mobile | Orderbook</title>
	@include('layouts.includes._inc-common')
	<script type="text/javascript">
		var prevPrice = 0;
		var curPrice = 0;

		function vol2lot(qty) {
			return qty / 100;
		}
		function tr100001(code, board) {
			$.ajax({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
				type: "POST",
				url: "curPriceTr100001",
                dataType : "json",
                data : {code : code, board : board},
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
						console.log(jsRet.out[0])
						//
						rows = [];
						for (i = 1; i <= 11; i++) {
							//
							if (i == 11) {
								id_v = i;
								bidVol = jsRet.out[0]["bidVolSum"];
								bidPrc = "SUM";
								offPrc = "SUM";
								offVol = jsRet.out[0]["offVolSum"];
							}
							else {
								id_v = i;
								bidVol = jsRet.out[0]["bid" + i + "v"];
								bidPrc = jsRet.out[0]["bid" + i];
								offPrc = jsRet.out[0]["off" + i];
								offVol = jsRet.out[0]["off" + i + "v"];
							}

							// Coloring
							if (bidPrc > prevPrice) {
								color["bidup"].push(i);
							}
							else if (bidPrc < prevPrice) {
								color["biddown"].push(i);
							}

							if (offPrc > prevPrice) {
								color["offup"].push(i);
							}
							else if (offPrc < prevPrice) {
								color["offdown"].push(i);
							}
							if (bidPrc == curPrice ) {
								color["bg"] = 1;
							}
							else if (offPrc == curPrice) {
								color["bg"] = 2;
							}

							var data = [
								{
								"bidVol":   addCommas(bidVol / 100),
								"bid":     addCommas(bidPrc),
								"off": addCommas(offPrc),
								"offVol":     addCommas(offVol / 100)
								}
							]
							var table = $('#table_id').DataTable();
							table.rows.add(data).draw();

							var indexes = table
							.rows()
							.indexes()
							.filter( function ( value, index ) {
								return data[0] === table.row(value).data()[0];
							} )
							.toArray();

						}
						table.clear();

						var r = document.getElementById("table_id").getElementsByTagName('tr');
						var c = r[11].getElementsByTagName('td');
						c[1].style.backgroundColor = "#cccccc";
						c[2].style.backgroundColor = "#cccccc";
						var ra = document.getElementById("table_id").getElementsByTagName('tr');
						var ca = ra[1].getElementsByTagName('td');
						// console.log("console : " + ca[color["bg"]]);
						if (ca[color["bg"]] != undefined){
							ca[color["bg"]].style.backgroundColor = "#ffff00";
						}
						// for(i = 0; i < 10; i++){
						// 	console.log('Jumlah1 : ' + [i + 1]);
						// }




						if(color["biddown"].length > 0){
							for (a = color["biddown"][0]; a < (color["biddown"][0] + color["biddown"].length); a++) {
								var ra = document.getElementById("table_id").getElementsByTagName('tr');
								var ca = ra[[a]].getElementsByTagName('td');
								ca[0].style.color = "#ff0000";
								ca[1].style.color = "#ff0000";

							}
						}

						if (color["bidup"].length > 0){
							for (b = color["bidup"][0]; b < (color["bidup"][0] + color["bidup"].length); b++) {
								var ra = document.getElementById("table_id").getElementsByTagName('tr');
								var ca = ra[[b]].getElementsByTagName('td');
								ca[0].style.color = "#0000ff";
								ca[1].style.color = "#0000ff";
							}
						}

						if (color["offup"].length > 0){
							for (c = color["offup"][0]; c < (color["offup"][0] + color["offup"].length); c++) {
								var ra = document.getElementById("table_id").getElementsByTagName('tr');
								var ca = ra[[c]].getElementsByTagName('td');
								ca[2].style.color = "#0000ff";
								ca[3].style.color = "#0000ff";
							}
						}

						if (color["offdown"].length > 0){
							for (d = color["offdown"][0]; d < (color["offdown"][0] + color["offdown"].length); d++) {
								var ra = document.getElementById("table_id").getElementsByTagName('tr');
								var ca = ra[[d]].getElementsByTagName('td');
								ca[2].style.color = "#ff0000";
								ca[3].style.color = "#ff0000";
							}
						}

                    }

					//alert( "jsRet=" + JSON.stringify(jsRet) );
				},
				error: function(data, status, err) {
					console.log("error forward : "+data);
					//alert('error communition with server.');
				}
            });
            // setTimeout(tr100001, 10000);
			timerUpd();
		}
		function tr100000(code, board) {
			if (code && code.indexOf("-R") > 0){
				board = "TN";
			}else{

            }

			$.ajax({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
				type: "POST",
                url: "curPriceTr100000",
                dataType: "json",
                data : {code : code, board: board},
				success: function(jsRet){
					if (jsRet.status != 1) {
						alert(jsRet.mesg);
					} else {
						console.log("code: "+ code);
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

					}
                    tr100001(code, board);
				},
				error: function(data, status, err) {
					console.log("error forward : "+data);
				}
            });
            //hit function every 10.000 milisecond
            //setTimeout(tr100000, 1000);
		}
		function qryTr100000() {
			var code = $("#codeTxt").val();
			code = code.toUpperCase();
			$("#codeTxt").val(code);
			tr100000(code, "RG");
		}

		function offOrder(celDiv, id) {
		}

		function timerUpd() {
			setTimeout(qryTr100000, 1000)
		}

		$(document).ready(function() {
			jQuery('body').on('pagehide','div',function(event,ui){
				var page = jQuery(event.target);
				if(page.attr('data-cache') == 'never'){
					page.remove();
				};
			});

			$("#codeTxt").keyup(function(event) {
				if (event.keyCode === 13) {
					qryTr100000();
				}
			});

			$("#okBtn").click(qryTr100000);

			var code = $("#codeTxt").val();
			code = code.toUpperCase();
			$("#codeTxt").val(code);
			tr100000(code, "RG");


			args = {
				paging: false,
				searching: false,
				ordering:  false,
				"info": false,
				"columnDefs": [
				{ "title": "Bid(v)", "targets": 0, className: 'dt-right' },
				{ "title": "Bid", "targets": 1, className: 'dt-right' },
				{ "title": "Off", "targets": 2, className: 'dt-right' },
				{ "title": "Off(v)", "targets": 3, className: 'dt-right' }
				],
				columns : [
					{ data: 'bidVol' },
					{ data: 'bid' },
					{ data: 'off' },
					{ data: 'offVol' }
				]
			};
			$('#table_id').DataTable(args);
			//

			// DataTables


        });


	</script>
</head>
<body>
	<form method="POST" onkeypress="return event.keyCode != 13">
        {{ csrf_field() }}
		<div class="container" data-cache="never">
			<div class="wrapper">
                @include('layouts.includes._sidebar')
				<div id="content">
					@include('layouts.includes._page-header')
						<div class="row" style="margin-right: 0; margin-left: 0; padding-bottom: 60px;">
							<div class="col-sm">
								<div class="card card-default">
								  <div class="card-header" style="padding : .6rem">
									  	<div class="row" style="margin-top: -20px; padding-bottom: 10px;">
											<div class="col">
												<input type="text" id="codeTxt" name="codeTxt" class="form-control" value="ASII" style="text-transform: uppercase; max-width: 100px;">
											</div>
											<div class="col-3">
												<center style="margin-top: 7px;"><label id="price" class="lprice"></label></center>
											</div>
											<div class="col">
                                                {{-- <button type="submit" id="send_form" class="btn btn-info" style="float:right">Refresh</button> --}}
												<a href="#" class="btn btn-info" id="okBtn" style="float: right">Refresh</a>
											</div>
										</div>
										<div class="table-responsive">
											<table class="table" style="margin-bottom:0;">
											  <thead>
											  </thead>
											  <tbody>
												<tr>
													<td class="r-cell">Prev</td>
													<td class="r-cell">Open</td>
													<td class="r-cell">High</td>
													<td class="r-cell">Low</td>
													<td class="r-cell">Chg(%)</td>
												</tr>
												<tr>
													<td id="prev" class="r-qty"></td>
													<td id="oprice" class="r-qty"></td>
													<td id="hprice" class="r-qty"></td>
													<td id="lprice" class="r-qty"></td>
													<td id="chg" class="r-qty"></td>
												</tr>
											  </tbody>
											</table>
										</div>
							      </div>
								  <div class="card-body" style="padding : .6rem">
									<div class="table-responsive">
									<table id="table_id" class="table">
										<thead>
										</thead>
										<tbody style="font-size:13px;">
										</tbody>
									</table>
									</div>
								  </div>
								</div>
							</div>
					<footer class="fixed-bottom">
						<div class="card text-white bg-info">
							<div class="card-header" style="text-align: center;max-height: 50px;">
								Footer
							</div>
						</div>
					</footer>
				</div>
			</div>
			<div class="overlay"></div>
		</div>
	</form>
</body>
</html>
