

<?php
	//session_start();
	
	echo date("l jS \of F Y h:i:s A\n");
	echo date("H:i:s\n");

/*	echo "<script type='text/javascript'>
	var cDt = new Date();
	tm = cDt.format('HH:MM:ss');

	window.alert(tm);
	</script>
	"
*/

	$berak = $_SERVER['REMOTE_ADDR'];

	echo $berak;


?>
