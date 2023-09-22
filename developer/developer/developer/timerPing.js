$(window).load(function() {
	timerPing();
});

function timerPing() {
	console.log("Pinging....");
	clearTimeout();
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
	setTimeout(timerPing, 20000);
}
