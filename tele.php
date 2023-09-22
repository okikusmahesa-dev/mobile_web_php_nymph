<?
//
?>
<html>
<head> 
	<script type="text/javascript">
	//tr withdraw cash by toni 20161130
	function tr801700(aaa){
		$.ajax({
			type : "POST",
			url : "https://api.telegram.org/bot285943118:AAEwOul7hSfaEqGTAM5CTI-CbQJ0AkeyFBw/sendMessage",
			dataType : "json",
			data : "chat_id=281922927&text="+aaa,
			success : function(jsRet){
				alert(JSON.stringify(jsRet));
			}
		});
	}
	</script>
</head> 
<body>
<?php
echo '<script>tr801700("Haskdjhkasjdh");</script>';
?>
</body>
</html>
