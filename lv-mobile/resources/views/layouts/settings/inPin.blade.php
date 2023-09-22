<?php
if(Session::get('isLogin') != 1){
    $server = $_SERVER["SERVER_NAME"];
    header("Location: http://$server:8000/login");
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
    <script>
        let retTr800000 = 0;
        let retTr800001 = 0;
        $(document).ready(function() {
            tr800000();
            tr800001();
        });
        function checkRetTr() {
        if (retTr800000 == 1 && retTr800001 == 1) {
            document.getElementById("okBtn").disabled = false;
            return 1;
        } else
            return 0;
        }

        function tr800000(){
            $.ajax({
                type: "GET",
                url: "checkTr800000",
                success: function(jsRet){
                    if(jsRet.status !=1){
                        alert(jsRet.mesg);
                    }else{
                        retTr800000 = 1;
                        checkRetTr();
                        console.log('800000 Successed!!!');
                    }
                }
            });
        }
        function tr800001(){
            $.ajax({
                type: "GET",
                url: "checkTr800001",
                success: function(jsRet){
                    if(jsRet.status !=1){
                        alert(jsRet.mesg);
                    }else{
                        retTr800001 = 1;
                        checkRetTr();
                        console.log('800001 Successed!!!');
                    }
                }
            });
        }

    </script>
</head>
<body>
    <form method="post" action="/pinDo">
        {{ csrf_field() }}
		<div class="container">
            <div class="wrapper">
                @include('layouts.includes._sidebar')
                <div id="content">
                    @include('layouts.includes._page-header')
                    @if (session('status'))
                    <br>
                    <div class="alert alert-danger">
                        {{ session('status') }}
                    </div>
                    @endif
                    <div class="card card-default">
                        <div class="card-body">
                            <form>
                                <div class="form-group">
                                    <label for="ClientID">Client ID</label>
                                <input type="text" class="form-control" id="id" name="id" value="{{ Session::get('userId') }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="InputPIN">Input PIN</label>
                                    <input type="password" class="form-control" id="pin" name="pin" placeholder="Input Your PIN" required>
                                </div>
                                    <a href="#" class="btn btn-info" style="width:49%" onclick="goIndex();return false;">Cancel</a>
                                    <button type="submit" class="btn btn-outline-info" style="width:49%; float:right;" id="okBtn" disabled="true">Confirm</button>
                                    <br>
                                    <br>
                            </form>
                        </div>
                    </div>
                    <footer class="fixed-bottom">
                            <div class="card text-white bg-info">
                                    <div class="card-header" style="text-align: center;max-height: 50px;">
                                        Footer
                                    </div>
                            </div>
                    </footer>
                </div>
            </div>
            <div class="overlay"></div>
		</div>
	</form>
</body>
</html>
