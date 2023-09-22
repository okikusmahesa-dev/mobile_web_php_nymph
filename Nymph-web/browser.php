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
	<title>Browser Info</title> 
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
        //var p_out = $('#p_out')b
        var browser = $.browser;
        console.log(browser);
        $( '#p_out').empty();
        var $field_out = jQuery.each (jQuery.browser, function(i,val) {
        //console.log("field out : " + $(j).attr('name'));
        //input = jQuery('<input name="' + $(j).attr('name') + '">');
            $( "<tr><td>" + i + " : </td<td>" + val + "</td>" )
            .appendTo(p_out);
        });
    });
		//
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
		<h1>Info</h1>
	</div>
<!--form method="post"-->

	<div data-role="main" class="ui-content">
		<div data-role="fieldcontain">
			<table border=0>
            <tr><td><label for="browser">Browser</label></td>
			    <td></td>
            </table>
		</div>
        <table border=0>
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
