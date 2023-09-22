<? 
	session_start();
	if ($_SESSION["isLogin"] != 1) {
		echo "Please login...";
		header("refresh:3; url=login.php");
		exit(0);
	}
	$userId = $_SESSION["userId"];
	$clientId = $_SESSION["loginId"];
	$pinState = 0;
	$pinLogin = 0;

	echo '<script type="text/javascript">setTimeout(function() { window.location.href = "https://mobile.bcasekuritas.co.id/tlogout.php"; }, 15 * 60000);</script>';
	if ($_SESSION["pinState"] != 1) {
		echo "<script>alert('Your trading has Expired. \\nPlease re-Input PIN first.')</script>";
		header("refresh:1; url=inPin.php");
		exit(0);
	} else {
		$pinState = $_SESSION["pinState"];
		$pinLogin = $_SESSION["pin"];
	}
?>

<html>
	<head>
		<title>watch list</title>
		<link rel="stylesheet" type="text/css" href="./css/favorite.css?20130314a" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<? include "inc-common.php" ?>
		<style type="text/css">
			<? include "css/icon.css" ?>
		</style>
		<script type="text/javascript">
			
			$(document).ready(function() {
				jQuery('div').live('pagehide', function(event, ui) {
					var page = jQuery(event.target);
					if(page.attr('data-cahce') == 'never') {
						page.remove();
					};
				});
				$("#r-group").change(function() {
					qryTr101211();
				})
				args = {
					//title : "watchlist",
					dataType : "json",
					height : "auto",
					colModel : [
						{display:"", width:30, sortable:false, align:'center', process:tr101221},
						{display:"Code", width:60, sortable:false, align:'center'},
						{display:"Price", width:50, sortable:false, align:'right'},
						{display:"Chg", width:50, sortable:false, align:'right'},
						{display:"(%)", width:50, sortable:false, align:'right'},
						{display:"Prev", width:50, sortable:false, align:'right'},
						{display:"Open", width:50, sortable:false, align:'right'},
						{display:"High", width:50, sortable:false, align:'right'},
						{display:"Low", width:50, sortable:false, align:'right'}
					]
				};
				$('#r-watchlist').flexigrid(args);
				tr101201("<?=$clientId;?>");

			});

			function tr101211(id, groupNo) {
				$.ajax({
					type: "POST",
					url: "json/trproc.php",
					dataType: "json",
					data: "tr=101211&id=" + id + "&groupNo=" + groupNo,
					success: function(jsRet) {
						if(jsRet.status != 1) {
							alert(jsRet.mesg);
						} else {
							rows = [];
							cnt = jsRet.out.length;

							for (i = 0; i < cnt; i++) {
								id_v = "id" + i;
								
								cell_v = [
									["-"],
									jsRet.out[i]["code"],
									addCommas(jsRet.out[i]["price"]),
									jsRet.out[i]["chgP"],
									jsRet.out[i]["chg"],
									addCommas(jsRet.out[i]["pprice"]),
									addCommas(jsRet.out[i]["oprice"]),
									addCommas(jsRet.out[i]["hprice"]),
									addCommas(jsRet.out[i]["lprice"])
								];

								row = {id:id_v, cell:cell_v};
								rows.push(row);
							}
							args = {dataType: "json", rows: rows, page: 1, total: cnt};
							$('#r-watchlist').flexAddData(args);
							paintColor();
						}
						timerUpd();
					},
					error : function(data, status, err) {
						alert('error cummunication with server.');
					}
				});
			}
			
			function tr101221(id, groupNo, no, code, board) {
				//var maxitems = 50;
				$.ajax({
					type: "POST",
					url: "json/trproc.php",
					dataType: "json",
					data: "tr=101221&id=" + id + "&groupNo=" + groupNo + "&no=" + no + "&code=" + code + "&board=" + board,
					success: function(jsRet) {
						if(jsRet.status != 1) {
							alert(jsRet.mesg);
						} else {
							delCode2();
							//jsRet.out[i]["cnt"];
							//alert("success");
							console.log("id" + id);
							console.log("success");
							console.log("code" + code);
							console.log("board" + board);
						}
					},
					error : function(data, status, err) {
						alert('error cummunication with server.');
					}
				});
			}

			function paintColor() {
				$('#r-watchlist tr').each( function() {
					var cel_price = $('td:nth-child(3) > div', this);
					var cel_chg = $('td:nth-child(4) > div', this);
					var cel_per = $('td:nth-child(5) > div', this);
					var cel_prev = $('td:nth-child(6) > div', this);
					var cel_open = $('td:nth-child(7) > div', this);
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
					var prev = Number(cel_prev.text().replace(',',''));
					var open = Number(cel_open.text().replace(',',''));
					var high = Number(cel_high.text().replace(',',''));
					var low	 = Number(cel_low.text().replace(',',''));
					open > prev ? cel_open.css("color", "blue") : 
						(open < prev ? cel_open.css("color", "red") : cel_open.css("color", "black"));
					high > prev ? cel_high.css("color", "blue") : 
						(high < prev ? cel_high.css("color", "red") : cel_high.css("color", "black"));
					low > prev ? cel_low.css("color", "blue") : 
						(low < prev ? cel_low.css("color", "red") : cel_low.css("color", "black"));

				});
			}

			function tr101201(id) {
				$.ajax({
					type: "POST",
					url: "json/trproc.php",
					dataType: "json",
					data: "tr=101201&id=" + id + "&nop=0",
					success: function(jsRet) {
						if (jsRet.status != 1) {
							alert(jsRet.mesg);
						}else {
							cnt = jsRet.out.length;
							iCnt = 0;
							for (i = 0; i < cnt; i++){
								if (jsRet.out[i]["title"].length > 0) {
									if (iCnt == 0) {
										item = sprintf("<option value='%s' selected='selected'>%s</option>", jsRet.out[i]["no"], jsRet.out[i]["title"]);
									} else {
										item = sprintf("<option value='%s'>%s</option>", jsRet.out[i]["no"], jsRet.out[i]["title"]);
									}
									$("#r-group").append(item);
									iCnt++;
								}
							}
						}
						$("#r-group")[0].selectedIndex = 0;
						$("#r-group").selectmenu("refresh");
						qryTr101211()
					},
					error: function(data, status, err) {
						alert('error communication with server.');
					}
				});
				timerUpd();
			}

			function qryTr101211() {
				var clientId = "<?=$clientId;?>";
				var groupNo = $("#r-group").children("option:selected").val();
				$.mobile.loading('show');
				tr101211(clientId, $("#r-group").children("option:selected").val());
				$.mobile.loading('hide');
			}
			function qryTr101221() {
				var clientId="<?=$clientId;?>";
			}
			
			function timerUpd() {
				setTimeout(qryTr101211, 10000);
			}
			function refreshAll() {
				location.reload();
			}
			//$("#refreshBtn").click(qryTr101211);

			function removeGroupAll() {
				$("#r-group").find('option').each(function() {
					$(this).remove();
				});
			}
			/*function delCode(celDiv, id) {
				$(celDiv).click(function() {
					//alert("success");
					delCode2();
				});
			}*/
			function delCode2() {
				$("#r-watchlist td").on("click", function() {
					var tr=$(this).closest('tr');
					tr.css("background-color","#FF3700");
					tr.fadeOut(400, function() {
						tr101221(id, groupNo, no, code, board);
						console.log("testing" + code);
						tr.remove();
					});
					return false();
					//event.preventDefault();
				});
			}

		</script>
	</head>
	<body style="background:#ffffff;">
		<div data-role="page" id="home" data-cache="never">
			<? include "page-header.php" ?>
			<div id="header1" class="ui-grid-d" style="margin:0;padding:0;">
				<div class="ui-block-a" style="float:left;width:110;padding:4;font-size=0.5;">
					<select id="r-group" data-mini="true">
					</select>
				</div>
				<div class="ui-block-b" style="float:left;width:110;padding:4;"><a id="refreshBtn" href="#" data-mini="true" data-role="button" data-icon="refresh" onclick="refreshAll()">Refresh</a></div>
			<div class="ui-block-b" style="float:right;width:100;padding:4;"><a id="saveBtn" href="#" data-mini="true" data-role="button" data-icon="save" onclick="tr101221()">Save</a></div>
			</div>
			<table id="r-watchlist" class="flexigrid">
				<tr>
				</tr>
				<tr>
				</tr>
			</table>
			<div class="ui-block-b" style="float:left;width:110;padding:4;"><a id="addBtn" href="#" data-mini="false" data-role="button" data-icon="add">Add Item</a></div>
			<? include "page-footer.php" ?>
		</div>
	</body>
</html>
