var pageTimer;

$(window).load(function() {
	pageTimer =	setTimeout(timerPing, 10 * 1000);
});

var tmPing = 0;

function timerPing() {
	//console.log("Pinging....");
	var dPing = new Date();
	tmCur = dPing.getTime();
	dtPing = (tmCur - tmPing) / 1000;
	if ((tmPing != 0) && (dtPing > 30)){
		window.location.href = "tlogout.php";
		alert("Logout..");
	}
	clearTimeout(pageTimer);
	$.ajax({
		type: "POST",
		url: "trping.php",
		dataType : "json",
		data: "tr=000101&time=12:34:56&id=abcde",
		success: function(jsRet){
			if (jsRet.status != 1) {
				//alert(jsRet.mesg);
			} else {
				//
				console.log(jsRet.out);
				//
			}
		},
		error: function(data, status, err) {
			console.log("error forward : "+data);
		}
	});
	pageTimer = setTimeout(timerPing, 10 * 1000);
	tmPing = dPing.getTime();
}
