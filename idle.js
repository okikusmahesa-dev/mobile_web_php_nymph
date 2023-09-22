$(window).load(function() {
	if(pin == "" || pinState == 0){
		idle({
		 onIdle: function(){
			alert('Test');
		 },
		 idle: 2000,
		 keepTracking: true
		}).start();
	}
});

