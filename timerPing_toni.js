$(window).load(function() {
	//timerPing();
	setTimeout(timerPing, 10 * 1000);
});

function timerPing() {
	//console.log("Pinging....");
	clearTimeout();
	$.ajax({
		type: "POST",
		url: "json/cekping.php",
		dataType : "json",
		data: "tr=000106&loginId=jku3083",
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
	setTimeout(timerPing, 10 * 1000);
}
