<html>
<head>
<title></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<? include "inc-common.php" ?>
<style type="text/css">
<!--
<? include "css/icon.css" ?>
//-->
</style>
<script type="text/javascript">
//
var stockMap = [];

function initStock() {
	tr090010("AALI", 50);
}

function tr090010(code, cnt) {
	$.ajax({
		type: "POST",
		url: "json/trproc.php",
		dataType : "json",
		data: "tr=090010&code=" + code + "&board=RG&cnt=" + cnt,
		success: function(jsRet){
			if (jsRet.status != 1) {
				alert(jsRet.mesg);
			} else {
				//
				cnt = jsRet.out.length;

				for (i = 0; i < cnt; i++) {
					code = jsRet.out[i].code;
					hc = jsRet.out[i].hairCut;

					stockMap[code] = [];
					stockMap[code]["hc"] = hc;
				}
				lastCode = jsRet.out[cnt-1].code;
				
				cnt == 50 ? tr090010(lastCode, 50) : console.log("he?");
				//
			}
		},
		error: function(data, status, err) {
			console.log("error forward : "+data);
			//alert('error communition with server.');
		}
	});
}

function tes() {
	alert("INI BERHASIL!");
}
//
</script>
</head>
<body>
	<button onClick="initStock()">TES</button>
</body>
</html>
