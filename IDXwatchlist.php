<? 
	session_start();
	if ($_SESSION["isLogin"] != 1) {
		echo "Please login...";
		header("refresh:3; url=login.php");
		exit(0);
	}
	$userId = $_SESSION["userId"];
	$clientId = $_SESSION["loginId"];
	$userId   = "";
	$clientId = "";
	$pinState = 0;
	$pinLogin = 0;
/*
	if ($_SESSION["pinState"] != 1) {
		echo "<script>alert('Your trading has Expired. \\nPlease re-Input PIN first.')</script>";
		header("refresh:1; url=inPin.php");
		exit(0);
	} else {
		$pinState = $_SESSION["pinState"];
		$pinLogin = $_SESSION["pin"];
	}
*/
?>

<html>
	<head>
		<title>IDX Watchlist</title>
		<link rel="stylesheet" type="text/css" href="./css/favorite.css?20130314a" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<? include "inc-common.php" ?>
		<style type="text/css">
			<? include "css/icon.css" ?>
            .refresh { font-size:16px; margin-top:3px; }
            .flexigrid div.btnseparator{ height:32px; }
            .edit{ background: url("./css/images/edit.png") no-repeat; margin-top:3px; background-position:left center;background-size:16px 16px; font-size:17px;}
		</style>
		<script type="text/javascript">
			
			$(document).ready(function() {
				jQuery('div').live('pagehide', function(event, ui) {
					var page = jQuery(event.target);
					if(page.attr('data-cahce') == 'never') {
						page.remove();
					};
				});
				$('input:radio[name="radio-choice-1"]').change(function() {
				if($(this).val() == 'WATCHLIST') {
					qryTr103100("WATCHLIST");
				} else if($(this).val() == 'ECONOMI') {
					qryTr103100("ECONOMI");
				}
			});

				args = {
					//title : "watchlist",
					dataType : "json",
					height : "auto",
					colModel : [
						{display:"Code", width:60, sortable:false, align:'center'},
						{display:"Notation", width:60, sortable:false, align:'center'},
						{display:"Price", width:50, sortable:false, align:'right'},
						{display:"Chg", width:50, sortable:false, align:'right'},
						{display:"(%)", width:50, sortable:false, align:'right'},
						{display:"High", width:50, sortable:false, align:'right'},
						{display:"Low", width:50, sortable:false, align:'right'},
						{display:"Bid Vol", width:70, sortable:false, align:'right'},
						{display:"Bid", width:50, sortable:false, align:'right'},
						{display:"Offer", width:50, sortable:false, align:'right'},
						{display:"Offer Vol", width:70, sortable:false, align:'right'},
						{display:"Volume(L)", width:85, sortable:false, align:'right'},
						{display:"Value(L)", width:80, sortable:false, align:'right'},
						{display:"Freq", width:80, sortable:false, align:'right'}
					],
                    buttons : [
                        {name:'Refresh', bclass:'refresh', onpress:refreshAll},
                        {separator: true},
                        // {name:'Create Group', bclass:'edit', onpress:editPage},
                    ]
				};
				$('#IDX-watchlist').flexigrid(args);
				// tr101201("<?=$clientId;?>");
				qryTr103100("WATCHLIST");

			});

			function refreshAll() {
				if (document.getElementById("radio-choice-1").checked) {
					qryTr103100("WATCHLIST");
				} else if (document.getElementById("radio-choice-2").checked) {
					qryTr103100("ECONOMI");
				} else {
					qryTr103100("ECONOMI");
				}
			}

			function qryTr103100(type) {
				tr103100(type);
			}

			function tr103100(type) {
				pHashOrg = "tr=103100&remarks=" + type;
                pHash    = "phash=" + Base64.encode(pHashOrg);
                $.ajax({
					type: "POST",
					url: "json/trproc.php",
					dataType: "json",
					data: pHash,

					success: function(jsRet) {
						if(jsRet.status != 1) {
							alert(jsRet.mesg);
						} else {
							rows = [];
							cnt = jsRet.out.length;

							for (i = 0; i < cnt; i++) {
								
								cell_v = [
									jsRet.out[i]["code"],
									jsRet.out[i]["notation"],
									addCommas(jsRet.out[i]["price"]),
									jsRet.out[i]["chg"],
									jsRet.out[i]["chgR"],
									addCommas(jsRet.out[i]["hprice"]),
									addCommas(jsRet.out[i]["lprice"]),
									addCommas(jsRet.out[i]["bidvol"] / 100),
									addCommas(jsRet.out[i]["bidprice"]),
									addCommas(jsRet.out[i]["offprice"]),
									addCommas(jsRet.out[i]["offvol"] / 100),
									addCommas(jsRet.out[i]["vol"] / 100),
									addCommas(jsRet.out[i]["val"] / 1000),
									addCommas(jsRet.out[i]["freq"])
								];

								row = {cell:cell_v};
								rows.push(row);
							}
							args = {dataType: "json", rows: rows, page: 1, total: cnt};
							$('#IDX-watchlist').flexAddData(args);
							paintColor();
						}
						timerUpd(type);
					},
					error : function(data, status, err) {
						alert('error cummunication with server.');
					}
				});
			}

			function paintColor() {
				$('#IDX-watchlist tr').each( function() {
					var cel_price = $('td:nth-child(3) > div', this);
					var cel_chg = $('td:nth-child(4) > div', this);
					var cel_per = $('td:nth-child(5) > div', this);
					var cel_bid = $('td:nth-child(6) > div', this);
					var cel_offer = $('td:nth-child(7) > div', this);
					var cel_high = $('td:nth-child(8) > div', this);
					var cel_low = $('td:nth-child(9) > div', this);

					var chg_per = parseFloat(cel_per.text());
					if (chg_per > 0) {
						cel_price.css("color", "blue");
						cel_chg.css("color", "blue");
						cel_per.css("color", "blue");
					} else if (chg_per < 0) {
						cel_price.css("color", "red");
						cel_chg.css("color", "red");
						cel_per.css("color", "red");
					}
					// var prev = Number(cel_prev.text().replace(',',''));
					var price = Number(cel_price.text().replace(',',''));
					var bid = Number(cel_bid.text().replace(',',''));
					var offer = Number(cel_offer.text().replace(',',''));
					var high = Number(cel_high.text().replace(',',''));
					var low	 = Number(cel_low.text().replace(',',''));
					
					bid > price ? cel_bid.css("color", "blue") :
						(bid < price ? cel_bid.css("color", "red") : cel_bid.css("color", "black"));
					offer > price ? cel_offer.css("color", "blue") :
						(offer < price ? cel_offer.css("color", "red") : cel_offer.css("color", "black"));
					high > price ? cel_high.css("color", "blue") : 
						(high < price ? cel_high.css("color", "red") : cel_high.css("color", "black"));
					low > price ? cel_low.css("color", "blue") : 
						(low < price ? cel_low.css("color", "red") : cel_low.css("color", "black"));
				});
			}

			
			function timerUpd() {
				setTimeout(refreshAll, 10000);
			}
			// $(document).ready(function() {
   //      		setInterval('refreshAll()', 10000);
   //  		});

			
		</script>
	</head>
	<body style="background:#ffffff;">
		<div data-role="page" id="home" data-cache="never">
			<? include "page-header.php" ?>
			<h2 style="margin-top: 8px;margin-bottom: 8px;padding:5;">IDX WatchList</h2>
			<fieldset data-role="controlgroup">
				<input type="radio" name="radio-choice-1" id="radio-choice-1" value="WATCHLIST" checked="checked" />
					<label for="radio-choice-1">Watchlist</label>
				<input type="radio" name="radio-choice-1" id="radio-choice-2" value="ECONOMI"  />
					<label for="radio-choice-2">New Economy</label>
			</fieldset>
			<table id="IDX-watchlist" class="flexigrid">
				<thead>
				</thead>
				<tbody>
				</tbody>
			</table>

			<? include "page-footer.php" ?>
		</div>
	</body>
</html>
