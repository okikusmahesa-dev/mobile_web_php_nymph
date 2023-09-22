var timer;
$(window).on("load",function() {
	if (loginId != "") {
		timer = setTimeout(timerPing, 10 * 1000);
	}
});

var tmPing = 0;
var sServerTime = "";
var dtServer = new Date();
var tmServer = 0;

function timerPing() {
	//console.log("Pinging....");
	var dPing = new Date();
    // loginId must be defined in source (inc-common.php)
    var id = loginId;
	tmCur = dPing.getTime();
	dtPing = (tmCur - tmPing) / 1000;
	if ((tmPing != 0) && (dtPing > 90)) {
        trLogout(id);
	}
	
	var sClientTime = "";
	sClientTime = ("0" + dPing.getHours()).slice(-2)
				+ ("0" + dPing.getMinutes()).slice(-2)
				+ ("0" + dPing.getSeconds()).slice(-2);

	clearTimeout(timer);
	
	console.log("sClientTime, id = " + sClientTime + "," + id);
	$.ajax({
		type: "POST",
		url: "trping.php",
		dataType : "json",
		data: sprintf("tr=000101&time=%s&id=%s", sClientTime, id),
		success: function(jsRet){
			if (jsRet && jsRet.status != 1) {
				//alert(jsRet.mesg);
				console.log(jsRet.out);
				sServerTime = jsRet.out[0].time;
				//console.log(sServerTime);
				dtServer.setHours(sServerTime.substring(0,2));
				dtServer.setSeconds(sServerTime.substring(4,6));
				tmServer = dtServer.getTime();

			} else {
				console.log("jsRet is null");
			}
		},
		error: function(data, status, err) {
			console.log("error forward : "+data);
		}
	});
	timer = setTimeout(timerPing, 10 * 1000);
	tmPing = dPing.getTime();
}

function trLogout(id) {
    console.log("trLogout id = " + id);
    $.ajax({
        type: "POST",
        url: "json/trproc.php",
        dataType : "json",
        data: "tr=710010&userId=" + id,
        success: function(jsRet) {
            if (jsRet.status != 1) {
                //console.log(jsRet);
            } else {
                console.log(jsRet);
            }
		    window.location.href = "tlogout.php";
		    alert("Logout..");
        },
        error: function(data, status, error) {
            console.log("logout error");
        }
    });
}
