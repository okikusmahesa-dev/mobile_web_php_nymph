<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");

session_start();
//print_r($_SESSION);
/*
if ($_SESSION["isLogin"] != 1) {
	echo "Please login...";
	header("refresh:3; url=login.php");
	exit(0);
}
*/
	#echo '<script type="text/javascript">setTimeout(function() { window.location.href = "https://mobile.bcasekuritas.co.id/tlogout.php"; }, 15 * 60000);</script>';
//$userId = $_SESSION["userId"];
//$clientId = $_SESSION["loginId"];
?>
<html>
<head>
	<title>tr dev</title> 
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="js/css/flexigrid.pack.css" />
    <link rel="stylesheet" href="css/jquery.mobile-1.2.0.min.css" />
    <script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
    <script src="js/jquery-ui.js"></script>
    <script type="text/javascript" src="js/flexigrid.pack.js"></script>
    <script type="text/javascript" src="js/jquery.mobile-1.2.0.min.js"></script>
    <script src="js/common.js?20170921a"></script>
    <script src="js/sprintf.js"></script>
    <script src="js/date.format.js"></script>
	<style type="text/css">
	<!--
	//-->
	</style>
	<script type="text/javascript">
    //var userId = "<?=$userId?>";
    //var clientId = "<?=$clientId?>";
    var now = new Date();
    var nowStr = now.format("yyyymmdd");

    // Initialize
    $(document).ready(function() {
        //tr800001(userId, nowStr);
        //tr182600(userId,"0","0");
        setInput();
    });
		//
    function setInput() {
        /*
        $('#trcodeTxt').keypress(function(e) {
            if (e.which == 13) {
                chkTr_onClick(); 
            }  
        });
        */

        $('#chkTr').click(function() {
            chkTr_onClick();
        });
    }

    function chkTr_onClick() {
        var stock = $('#stockTxt').val();
        var board = $('#boardTxt').val();
        //var trcode = $('#trcodeTxt').val();
        var trcode = $('#trcodeSel').val();
        var trcode_list = [
            { code : '100000', fname : 'Quote/100000.xml', data : sprintf('tr=%s&code=%s&board=%s', trcode, stock, board) },
            { code : '100001', fname : 'Quote/100001.xml', data : sprintf('tr=%s&code=%s&board=%s', trcode, stock, board) },
            ];
        
        var fname = "";
        var datastr = "";
        for (var i = 0; i < trcode_list.length; i++) {
            if (trcode == trcode_list[i]['code']) {
                fname = trcode_list[i]['fname'];
                datastr = trcode_list[i]['data'];
            }
        }
        var urlstr = "/json/trproc.php"

        if (fname == "") {
            alert("unknown trcode : " + trcode);
        } else {
            console.log("trcode : ", trcode, "fname : ", fname, "data : ", datastr);
            $.ajax({
                type: 'POST',
                url: urlstr,
                dataType: 'json',
                data: datastr,
                success: function(jsRet){
                    if (jsRet.status != 1) {
                        alert(jsRet.mesg);
                    } else {
                        console.log(trcode);
                        console.log(jsRet.out);
                        console.log(JSON.stringify(jsRet));
                        $( '#link_out').empty();
                        $( '<p>URL</p>' ).appendTo('#link_out');
                        $( '<p>' + urlstr + '?' + datastr + '</p>' ).appendTo('#link_out');
                        displayResult(fname, jsRet.out);
                    }
                }
            });
        }
    }


    function sample_tr() {
        $.ajax({
            type: 'POST',
            url: '/json/trproc.php',
            dataType: 'json',
            data: 'tr=100000&code=TLKM&board=RG',
            success: function(jsRet){
                if (jsRet.status != 1) {
                    alert(jsRet.mesg);
                } else {
                    console.log(jsRet.out[0]);
                    console.log(JSON.stringify(jsRet));
                    /* ...... */ 
                }
            }
        });
    }
    

    function displayResult(fname, out) {
        var path = '/json/Request/' + fname;
        $.ajax({
            url: path,
            type: 'GET',
            dataType: 'text',

            success: function(xml){
                var xmlDoc = $.parseXML( xml );
                var $xml = $( xmlDoc );
                var $rec_out = $xml.find('record[name="out"]');
                var p_out = $('#p_out');
                var output = '';

                /*
                for (var key in out[0]) {
                    if (out[0].hasOwnProperty(key)) {
                        console.log(key, out[0][key]);
                    }
                }
                */


                $( '#p_out').empty();
                $( '<tr><td colspan="3"><a href="' + path + '" target="_blank">out definition</a></td></tr>' ).appendTo( $('#p_out') );
                var $field_out = $rec_out.find('field').each(function(i,j) {
                    //console.log("field out : " + $(j).attr('name'));
                    var field_name = $(j).attr('name');
                        //input = jQuery('<input name="' + $(j).attr('name') + '">');
                    var str = sprintf(
                        "<tr><td><label>%s</label></td><td><label>%s</td></tr>",
                        field_name, out[0][field_name]);
                    $( str ).appendTo(p_out);
                });
            }
        });
        
    }

    $(function() {
/*
        $('form').submit(function() {
            console.log('ok');
            var tr = $('#tr').val();
            var in1 = $('#in1').val();
            var in2 = $('#in2').val();
            var in3 = $('#in3').val();
            var in4 = $('#in4').val();
            var in5 = $('#in5').val();
            var in6 = $('#in6').val();

            //qrytr(tr);
            function idFill() {

                if($('#tr').val().length == 0) {
                    alert('Client ID cannot be empty');
                    return false;
                } else {
                    return true;
                }
            }
				
            function pinFill() {
                if($('#pin').val().length == 0) {
                    alert('Pin cannot be empty');
                    return false;
                } else {
                    return true;
                }
            }
				
            return false;
        });
*/
    });

    function qrytr(tr) {
	    $.ajax({
	        type: "POST",
			url: "json/trproc.php",
			dataType : "json",
			data: sprintf("tr=%s",tr),
			success: function(jsRet){
			    if (jsRet.status != 1) {
				   alert(jsRet.mesg);
				} else {
				    console.log(jsRet);
						//
                }
			},
			error: function(data, status, err) {
				console.log("error forward : "+data);
			}
        });

    }
	
		//
	</script>
</head>
<body>
<div data-role="page" id="inPin">
	<div data-role="header" data-theme="b">
		<h1>TR Test(100000)</h1>
	</div>
<!--form method="post"-->

	<div data-role="main" class="ui-content">
		<div data-role="fieldcontain">
			<table border=0>
            <tr><td><label for="code">TR</label></td>
			    <td><select type="text" name="trcode" id="trcodeSel">
                    <option value='100000' selected='selected'>100000</option>
                    <option value='100001'>100001</option>    
                    </select>
                    </td>
                <td></td>
            </tr>
            <tr><td><label for="code">Code</label></td>
			    <td><input type="text" name="stock" id="stockTxt" data-mini="true" value="TLKM"></td>
                <td></td>
            </tr>
            <tr><td><label for="board">Board</label></td>
			    <td><input type="text" name="board" id="boardTxt" data-mini="true" value="RG"></td>
                <td><input type="button" data-icon="check" data-iconpos="notext" name="chkTr" id="chkTr" ></td> 
            </tr>
            </table>

		</div>
        <div id="link_out">

        </div>
        <table border=0>
            <div data-role="fieldcontain">
            </div>
            <div id="p_out" data-role="fieldcontain">
            <!--
            <p>
                <label for="fields_in"><input type="text" id="field_in" size="20" name="field_in" value=""/></label>
            </p>
            -->
            <!--tr><td><label>in1</label></td>
			    <td><input type="text" name="in1" id="in1" data-mini="true" value="">
                </td></tr>
            <tr><td><label>in2</label></td>
			    <td><input type="text" name="in2" id="in2" data-mini="true" value="">
                </td></tr>
            <tr><td><label>in3</label></td>
			    <td><input type="text" name="in3" id="in3" data-mini="true" value="">
                </td></tr>
            <tr><td><label>in4</label></td>
			    <td><input type="text" name="in4" id="in4" data-mini="true" value="">
                </td></tr>
            <tr><td><label>in5</label></td>
			    <td><input type="text" name="in5" id="in5" data-mini="true" value="">
                </td></tr>
            <tr><td><label>in6</label></td>
			    <td><input type="text" name="in6" id="in6" data-mini="true" value="">
                </td></tr-->
            </div>
        </table>

        <!--
		<button id="okBtn" type="" data-inline="true" data-theme="b" data-mini="true" > Ok </button>
		<a href="index.php?uFlag=1" data-inline="true" data-role="button" data-theme="b" data-mini="true">Cancel</a>
        -->
	</div>

<!--/form-->
	<div data-role="footer" data-theme="b">
		<h1>&nbsp</h1>
	</div>
</div>
<pre>
<?
?>
</pre>
</body>
</html>
