<?

$cDate = getdate();
$cDay = $cDate["weekday"];
$cTime = $cDate["hours"].$cDate["minutes"].$cDate["seconds"];

//echo $cTime;

$cDate2 = date('His');
echo $cDate2;

if($cDate2 < 101000){
	echo "in";
}

?>
