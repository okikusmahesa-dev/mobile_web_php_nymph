function tr800017(redirect){
	$.ajax({
    	type : "POST",
        url : "json/trproc.php",
        dataType : "json",
        data : "tr=800017&id=2",
        success : function(jsRet){
			console.log("800017");
			console.log(jsRet);
        	if (jsRet.status !=1){
            	alert("Error: " + jsRet.mesg);
			}else{
				console.log(jsRet.out);
				if (jsRet.out[0].flag=="N"){
					srvOrderStatus = 'N';
					srvOrderMsg = "Can not Order (Server Maintenance)";
					alert(srvOrderMsg);
					location.href = redirect;
				}
			}
   		}
	});
}


function tr080116(loginId, redirect){
	$.ajax({
		type : "POST",
		url : "json/trproc.php",
		dataType : "json",
		data : "tr=080116&loginID=" + loginId,
		success : function(jsRet){
			console.log("080116");
			console.log(jsRet);
            if (jsRet.status != 1){
                alert(jsRet.mesg);
            } else {
                console.log(jsRet.out);
                TimeStart = jsRet.out[0].otStart;
                TimeEnd = jsRet.out[0].otEnd;
				svrTime = jsRet.out[0].svrTime;
				if (svrTime < TimeStart || svrTime > TimeEnd) {
					var s1 = TimeStart.substring(0,2) + ":" + TimeStart.substring(2,4);		
					var s2 = TimeEnd.substring(0,2) + ":" + TimeEnd.substring(2,4);
					mesg = sprintf("Can Order while %s ~ %s", s1, s2);
					alert(mesg);
					window.location.href = redirect;
					}
                }
                $.mobile.loading('hide');
            },
            error : function(data, status, err){
                console.log("error tr080116 : " + data);
                $.mobile.loading('hide');
            }
        });
    }

