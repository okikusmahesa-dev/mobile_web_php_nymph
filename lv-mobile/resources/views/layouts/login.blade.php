<?php
if(Session::get('isLogin') == 1){
    $server = $_SERVER["SERVER_NAME"];
    header("Location: http://$server:8000/");
    exit(0);
}
?>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<meta name="viewPort" content="width=device-width, initial-scale=1.0" />
<head>
	<meta charset="utf-8">
	<title>BEST Mobile</title>
    @include('layouts.includes._inc-common')
    <script type="text/javascript">
		///*
		$(document).ready(function() {
			jQuery('body').on('pagehide','div',function(event,ui){
			//jQuery('div').live('pagehide', function(event, ui){
				var page = jQuery(event.target);
				if(page.attr('data-cache') == 'never'){
					page.remove();
				};
			});
		});

		$(function() {
			$('form').submit(function() {
				$user = $('#loginid').val();
				$pass = $('#passwd').val();
				$ip_adr  = $('#ip').val();
				///*
				if ( userFill() && passFill() ) {
					return true;
				}
				//*/
				function userFill() {
					if($('#loginid').val().length == 0) {
						alert('Username cannot be empty');
						return false;
					} else {
						return true;
					}
				}

				function passFill() {
					if($('#passwd').val().length == 0) {
						alert('Password cannot be empty');
						return false;
					} else {
						return true;
					}
				}

				return false;
			})
		});


        window.history.forward();
		function noBack(){ window.history.forward(); }


	</script>
</head>
<body onload="noBack()" onpageshow="if(event.persisted)noBack();" onunload="">

    <form method="post" action="/postlogin">
        <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
		<div class="card text-white bg-info">
			<div class="card-header" style="text-align: center">
				<div class="title" style="margin: -0.6em 30% -0.9em;">
					BEST Mobile <br>
					<span style="font-size: 12px;">Ver 1.0.0</span>
				</div>
			</div>
		</div>
		<div class="container">

			<br>
				<center>
					<img src="{{asset('assets/img/logo-front.png')}}"/>
				</center>
                <br>
                @if (session('status'))
                <div class="alert alert-danger">
                    {{ session('status') }}
                </div>
                @endif
				<div class="card card-default">
					<div class="card-body">
							<div class="form-group">
								<label for="loginId">Login ID</label>
								<input type="text" class="form-control" name="loginid" id="loginid" autocomplete="off" placeholder="Login ID">
							</div>
							<div class="form-group">
								<label for="loginId">Password</label>
								<input type="password" class="form-control" name="passwd" id="passwd" autocomplete="off" placeholder="Password">
								<input type="hidden" name="ip" id="ip" value="{{$_SERVER['REMOTE_ADDR']}}">
							</div>
							<div class="form-group form-check">
									<input type="checkbox" class="form-check-input" id="exampleCheck1">
									<label class="form-check-label" for="exampleCheck1">Remember ID</label>
									<a href="#" style="float: right">Forgot Password</a>
								  </div>
                            <button type="submit" class="btn btn-info btn-block">Login</button>
                            <br>
					</div>
				</div>
				<br>
			<center>
					<span id="siteseal"><script async type="text/javascript" src="https://seal.godaddy.com/getSeal?sealID=MqdtosGXRlwCRWQLEWSMwFH66Ee5zzocEr3448gh0Ax4Skv64F6mygmqAimK"></script></span>
					<br><br>
					<p>
							Dapatkan Fitur yang Lebih Lengkap di <br>New BEST Mobile 2.0
						</p>
						<p>
							Klik icon/link dibawah ini :
						</p>
						<div style="margin-top: -60px;">
								<a href="https://play.google.com/store/apps/details?id=com.bcasekuritas.BestMobile" style="text-decoration:none;">
									<img src="{{asset('assets/img/google-play.png')}}" style="width:150px"/>
								</a>
								<a href="https://itunes.apple.com/us/app/bcas-best-mobile-2-0/id1441407981?mt=8" style="text-decoration:none;">
									<img src="{{asset('assets/img/app-store.png')}}" style="width:150px"/>
								</a>
						</div>
			</center>
		</div>
	</form>
</body>
</html>
