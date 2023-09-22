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
		<title>Watchlist Edit</title>
		<link rel="stylesheet" type="text/css" href="./css/favorite.css?20130314a" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<? include "inc-common.php" ?>
		<style type="text/css">
			<? include "css/icon.css" ?>
            input[type="radio"]{
                -webkit-appearance: checkbox;
                -moz-appearance: checkbox;
                -ms-appearance: checkbox;
                width: 15px;
                height: 15px;
            }
            .ui-btn-text{ font-size:70%; }
		</style>
        <link rel="stylesheet" type="text/css" href="./css/modal.css?rand=<?php echo rand(0,99); ?>" />
        <script type="text/javascript">
            var gRows = [];
			$(document).ready(function() {
				jQuery('div').live('pagehide', function(event, ui) {
					var page = jQuery(event.target);
					if(page.attr('data-cache') == 'never') {
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
                        {display:"", width:50, sortable:false, align:'center'},
                        {display:"Code", width:100, sortable:false, align:'center'},
						{display:"Board", width:100, sortable:false, align:'center'},
                    ],
                    singleSelect: true,
                    buttons : [
                        {name:'Back', bclass:'back', onpress:backPage},
                        {separator: true},
                        {name:'Refresh', bclass:'refresh', onpress:refreshAll},
                        {separator: true},
                        {name:'Add', bclass:'add', onpress:addrow},
                        {separator: true},
                        {name:'Delete', bclass:'delete', onpress:deleterow},
                        {separator: true},
                        {name:'up', bclass:'uup', onpress:moveup},
                        {separator: true},
                        {name:'down', bclass:'ddn', onpress:movedown},
                        {separator: true},
                        {name:'Save', bclass:'sve', onpress:savechg},
                    ]
				};
				$('#r-watchlist').flexigrid(args);
                tr101201("<?=$clientId;?>");
                
                document.getElementsByClassName("sve")[0].style.display = "none";
                document.getElementsByClassName("sve")[0].parentElement.style.display = "none";
                document.getElementsByClassName("sve")[0].parentElement.parentElement.style.display = "none"
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
                            // delete gRows
							gRows = [];
							var cnt = jsRet.out.length;
                            
                             
							for (i = 0; i < cnt; i++) {
								id_v = "id" + i;
								
								cell_v = [
                                    "<input type='radio' name='selection'/>",
                                    jsRet.out[i]["code"],
									jsRet.out[i]["board"],
								];

								row = {id:id_v, cell:cell_v};
								gRows.push(row);
							} 
                            
                            console.log(gRows);
                            args = {dataType: "json", rows: gRows, page: 1, total: cnt};
							$('#r-watchlist').flexAddData(args);
                            paintColor();
                           
                            $("#r-watchlist input:first").prop("checked",true);    
						}
						//timerUpd();
					},
					error : function(data, status, err) {
						alert('error cummunication with server.');
					}
				});
			}
            
            function tr101222(id, groupNo, no, code, board){
                $.ajax({
                    type: "POST",
                    url: "json/trproc.php",
                    dataType: "json",
                    data: sprintf("tr=101222&userid=%s&groupno=%s&itemno=%s&code=%s&board=%s", id, groupNo, no, code, board),
                    success : function(jsRet){
                        if (jsRet.status != 1) {
                            alert(jsRet.mesg);
                        } else {
                            console.log(jsRet);
                            console.log("tr101222 affected rows : " + jsRet.out[0].count);
                            alert("Changed saved");
                            $.mobile.loading('hide');
                            //window.location = "https://mobile.bcasekuritas.co.id/watchlist.php";
                            window.location = "<?=$gBaseUrl?>/watchlist.php";
                        }
                    }
                })
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

            function tr101224(id, groupNo, groupName, flag){
                $.ajax({
                    type: "POST",
                    url: "json/trproc.php",
                    dataType: "json",
                    data: "tr=101224&userId=" + id + "&groupId=" + groupNo + "&groupName=" + groupName + "&flag=" + flag,
                    success: function(jsRet){
                        if(jsRet.status != 1){
                            alert(jsRet.mesg);
                        }else{
                            console.log("Affected Row : " + jsRet.out[0].count);
                            alert("Success..");
                            location.reload()
                        }
                    }
                })
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
							var cnt = jsRet.out.length;
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
                            console.log(jsRet);
						}
						$("#r-group")[0].selectedIndex = 0;
						$("#r-group").selectmenu("refresh");
						qryTr101211()
					},
					error: function(data, status, err) {
						alert('error communication with server.');
					}
				});
				//timerUpd();
			}
			function qryTr101211() {
				var clientId = "<?=$clientId;?>";
				var groupNo = $("#r-group").children("option:selected").val();
				$.mobile.loading('show');
				tr101211(clientId, groupNo);
                $.mobile.loading('hide');
			}

            function tr100010(code) {
                $.mobile.loading('show');
                $.ajax({
                    type: "POST",
                    url: "json/trproc.php",
                    dataType: "json",
                    data: sprintf("tr=100010&code=%s", code),
                    success : function(jsRet){
                        if (jsRet.status != 1) {
                            alert(jsRet.mesg);
                        } else {
                            console.log(jsRet.out);
                            if (jsRet.out.length == 0) {
                                alert("Invalid code : " + code);
                                document.getElementById("srcgroup").value = "";
                                document.getElementById("addStockNow").value = "";
                                document.getElementById("addBoardNow").value = "";
                                //$("#saveStock").button('enable')
                           } else {
                                document.getElementById("addStockNow").value = code;
                                var board = jsRet.out[0].StockType;
                                document.getElementById("addBoardNow").value = "RG";
                                $("#saveStock").button('enable')
                            }
                        }
                        $.mobile.loading('hide');
                    }
                });
            }
			
			function timerUpd() {
				setTimeout(qryTr101211, 1000000);
			}
			function refreshAll() {
				location.reload();
			}
			//$("#refreshBtn").click(refreshAll);

			function removeGroupAll() {
				$("#r-group").find('option').each(function() {
					$(this).remove();
				});
			}

            function showStock(){
                var stock = document.getElementById("srcgroup").value.toUpperCase();
                tr100010(stock);
            }
                             
            function saveStock(){
                $.mobile.loading('show')
                var code = document.getElementById("addStockNow").value.toUpperCase();
                var rg = document.getElementById("addBoardNow").value.toUpperCase();
                var isi = ["<input type='radio' name='selection' />",code,rg]; 
                var pos = parseInt(filled().toString()) + 1;
                var id_z = "id"+pos;
                var isiarr = {id:id_z,cell:isi}; 
                
                if (gRows.length > 0) {
                    gRows.splice(pos,0,isiarr);
                } else {
					gRows.push(isiarr);
                    args = {dataType: "json", rows: gRows, page: 1, total: 1};
                }
                console.log(gRows);

                $("#r-watchlist").flexAddData(args);
                closed();
                document.getElementsByClassName("sve")[0].style.display = "block";
                document.getElementsByClassName("sve")[0].parentElement.style.display = "block"
                document.getElementsByClassName("sve")[0].parentElement.parentElement.style.display = "block"
                $("#r-watchlist input:eq("+ pos +")").prop("checked",true);
                $.mobile.loading('hide');
            }

            function filled(){
                var lastChecked = $("#r-watchlist input:checked").closest("tr").index();
                var fill = []
                fill.push(lastChecked)
                return fill;
            }

            function backPage(){
                //window.location.href = "https://mobile.bcasekuritas.co.id/watchlist.php"
                window.location.href = "<?=$gBaseUrl?>/watchlist.php";
            }   
             
            function addrow(){
                var sum = gRows.length;

                if(sum > 49){
                    alert("Maximum stock only 10") //testing 10, max stock 50
                }else{
                    document.getElementById("myModal").style.display = "block";
                    $("div[data-role='header']").hide()
                    document.getElementById("header1").style.display = "none";
                    $(".flexigrid:has(#r-watchlist)").hide()
                    $("div[data-role='footer']").hide() 
                    document.getElementById("srcgroup").focus()
                }
            }
            
            function deleterow(){
                var indx = $("#r-watchlist input:checked").closest("tr").index();

                if(indx < 0){
                    alert("No item Selected");
                }else{
                    gRows.splice(indx,1);
                    $("#r-watchlist").flexAddData(args);
                    document.getElementsByClassName("sve")[0].style.display = "block"
                    document.getElementsByClassName("sve")[0].parentElement.style.display = "block"
                    document.getElementsByClassName("sve")[0].parentElement.parentElement.style.display = "block"
                    $("#r-watchlist input:first").prop("checked",true)
                }
            }

            function moveup(){
                var indx = $("#r-watchlist input:checked").closest("tr").index();

                if(indx > 0){
                    var hit = indx - 1;
                    var tmp_rec = {};
                    tmp_rec = gRows[indx];
                    gRows[indx] = gRows[hit];
                    gRows[hit] = tmp_rec;
                    $("#r-watchlist").flexAddData(args);
                    document.getElementsByClassName("sve")[0].style.display = "block";
                    document.getElementsByClassName("sve")[0].parentElement.style.display = "block"
                    document.getElementsByClassName("sve")[0].parentElement.parentElement.style.display = "block"
                    $("#r-watchlist input:eq( "+ hit +")").prop("checked",true)
                }else if(indx == 0){
                    alert("Already at the top.")
                }else if(indx == -1){
                    alert("No item selected")
                }
            }

            function movedown(){ 
                var indx = $("#r-watchlist input:checked").closest("tr").index();
                var kur = gRows.length - 1;
                
                if(indx == -1){
                    alert("No item selected")
                }else if(indx < kur){
                    var tmp_rec = {};
                    var jum = parseInt(indx) + 1;
                    tmp_rec = gRows[indx];
                    gRows[indx] = gRows[jum];
                    gRows[jum] = tmp_rec;
                    $("#r-watchlist").flexAddData(args);
                    document.getElementsByClassName("sve")[0].style.display = "block";
                    document.getElementsByClassName("sve")[0].parentElement.style.display = "block"
                    document.getElementsByClassName("sve")[0].parentElement.parentElement.style.display = "block"
                    $("#r-watchlist input:eq( "+ jum +")").prop("checked",true)
                }else if(indx == kur){
                    alert("Already at the bottom.")
                }
            }
           
            function savechg(){ 
				var userId = "<?=$clientId;?>";
				var groupNo = $("#r-group").children("option:selected").val();
                var itemNo = "";
                
                for(var j=1;j<=gRows.length;j++){
                    if ( j == 1)
                        itemNo = j + ',';
                    else
                        itemNo += j + ',';
                }
                
                if (gRows.length > 0)
                    itemNo = itemNo.substring(0, itemNo.length - 1);
                else 
                    itemNo = '1';

                var code = gRows.map(function(e){
                    return e.cell[1]
                }).toString();

                var board = gRows.map(function(e){
                    return e.cell[2];
                }).toString(); 
                
                var cnt = itemNo.split(",").length + 1;
                
                for (i = cnt; i <= 50; i++) {
                    itemNo = sprintf("%s,%d", itemNo, i);
                    code = sprintf("%s,", code);
                    board = sprintf("%s,", board);
                }
                console.log("item = " + itemNo);
                console.log("code = " + code);
                console.log("board = " + board);
                
                if(groupNo == null){
                    alert("No group found, please create one to save stock..")
                }else{
                    tr101222(userId, groupNo, itemNo, code, board);
                }
            }

            function newGroup(){ 
				var userId = "<?=$clientId;?>";
                var groupNo = $("#r-group").children("option:selected").val();
                var groupName = prompt("New Group Name");
                var crtFlag = "N"
                if(groupName != null){
                    if(groupName == ""){
                        alert("Please input new group name");
                    }else{
                        if(groupNo == null){
                            groupNo = 1
                            console.log(userId + " " + groupNo + " "  + groupName + " " +crtFlag);
                            tr101224(userId, groupNo, groupName, crtFlag);
                        }else{
                            console.log(userId + " " + groupNo + " "  + groupName + " " +crtFlag);
                            tr101224(userId, groupNo, groupName, crtFlag);
                        }
                    }
                }
            }

            function delGroup(){
                var userId = "<?=$clientId;?>";
                var groupNm = $("#r-group").children("option:selected").text()
                var groupIndx = $("#r-group").children("option:selected").val();
                var delFlag = "D";
                var conf = confirm("Sure want to delete Group " + groupNm + " ?");
                if(conf == true){
                    console.log(userId + " " + groupIndx + " " + groupNm + " " + delFlag)
                    tr101224(userId, groupIndx, groupNm, delFlag);
                }
            }

            function renGroup(){
                var userId = "<?=$clientId;?>";                
                var groupIndx = $("#r-group").children("option:selected").val();
                var renGroup = prompt("Rename Group Name");
                var rnmFlag = "R";
                if(renGroup != null){
                    if(renGroup == ""){
                        alert("Please input new group name");
                    }else{
                        console.log(userId + " " + groupIndx + " "  + " " + renGroup + " " + rnmFlag);
                        tr101224(userId, groupIndx, renGroup, rnmFlag);
                    }
                }
            }
            
            function closed(){
                document.getElementById("myModal").style.display = "none";
                $("div[data-role='header']").show()
                document.getElementById("header1").style.display = "block";
                $(".flexigrid:has(#r-watchlist)").show()
                $("div[data-role='footer']").show()
                document.getElementById("srcgroup").value = "";
                document.getElementById("addStockNow").value = "";
                document.getElementById("addBoardNow").value = ""
                $("#r-watchlist input:eq("+ parseInt(filled().toString()) +")").prop("checked",true)
            }
		</script>   
	</head>
	<body style="background:#ffffff;">

		<div data-role="page" id="home" data-cache="never">
			<? include "page-header.php" ?>
			<div id="header1" class="ui-grid-d" style="margin:0;padding:0;">
                <table>
                    <tr>
                        <td>
                            <select id="r-group" data-mini="true">
	                        </select>
                        </td>
                        <td>
                            <button onClick="newGroup()" data-role="button" data-mini="true"><span style="font-size:70%">New Group</span></button>
                        </td>
                        <td>
                            <button onClick="renGroup()" data-role="button" data-mini="true"><span style='font-size:70%'>Rename Group</span></button>
                        </td>
                        <td>
                            <button onClick="delGroup()" data-role="button" data-mini="true"><span style='font-size:70%'>Delete Group</span></button>
                        </td>
                    </tr>
                </table>
            </div>
            

            <div id="myModal" class="modal">
                <div class="modal-content">
                    <div id='toolbar'>
                        <center><span class="toolbarName">Add Stock</span></center>
                        <center><span class="close" onClick="closed()">&times;</span></center>
                        <div class="clear"></div>
                    </div>
                    <div class="modal-main">
                        <table style="margin-bottom:10px;">    
                            <tr>
                                <td>
                                    <label>Code</label>
                                </td>
                                <td>
                                    <input type="text" style="text-transform:uppercase;" name="group" id="srcgroup" class="no-ime" data-mini="true"  maxlength="7"/>
                                </td>
                                <td>
                                    <input type="button" data-icon="check" data-iconpos="notext" class="no-ime" id='showStock' onClick="showStock()">
                                </td>
                            </tr>
                        </table>
                        <table style="margin-bottom:20px;">
                            <tr>
                                <td>
                                    <label>Stock</label> 
                                </td>
                                <td>
                                    <input type="text" maxlength="7" name="addStock" class="no-ime" data-mini="true" id="addStockNow" disabled>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Board</label>
                                </td>
                                <td>
                                    <input type="text" id="addBoardNow" class="no-ime" data-mini="true" disabled>
                                </td>
                            </tr>
                        </table>
                        <input type="button" data-icon="check" id="saveStock" value="Add" disabled="disabled" onclick="saveStock()" />
                    </div>
                </div>
            </div>

            <table id="r-watchlist" class="flexigrid">
				<thead>
				</thead>
				<tbody>
				</tbody>
			</table>
         <? include "page-footer.php" ?>
		</div>
	</body>
</html>
