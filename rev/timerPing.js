var timer;
$(window).load(function() {
	timer = setTimeout(timerPing, 10 * 1000);
});

var tmPing = 0;

function timerPing() {
	//console.log("Pinging....");
	var dPing = new Date();
    // loginId must be defined in source (inc-common.php)
    var id = loginId;
    
	tmCur = dPing.getTime();
	dtPing = (tmCur - tmPing) / 1000;
	if ((tmPing != 0) && (dtPing > 30)) {
        trLogout(id);
	}
	clearTimeout(timer);
    if (id.length != 0) {
	    $.ajax({
		    type: "POST",
		    url: "trping.php",
		    dataType : "json",
		    data: "tr=000101&time=12:34:56&id=abcde",
		    success: function(jsRet){
			    if (jsRet && jsRet.status != 1) {
				    //alert(jsRet.mesg);
				    console.log(jsRet.out);
			    } else {
				    //
				    console.log("jsRet is null");
				    //
			    }
		    },
		    error: function(data, status, err) {
			    console.log("error forward : "+data);
		    }
	    });
    }
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
