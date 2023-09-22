function tr100009(id) {
	$.ajax({
		type: "POST",
		url: "json/trproc.php",
		dataType : "json",
		data: "tr=100009&userId=" + id,
		success: function(jsRet){
			if (jsRet.status != 1) {
				alert(jsRet.mesg);
			} else {
				//
				console.log(jsRet.out[0]);
				//
			}
		},
		error: function(data, status, err) {
			console.log("error forward : "+data);
		}
	});
}
