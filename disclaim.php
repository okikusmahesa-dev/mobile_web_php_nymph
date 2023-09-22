<?
header("Cache-Control: no-cache");
header("Pragma: no-cache");

echo '<script type="text/javascript">setTimeout(function(){ window.location.href = "tlogout.php"; }, 15 * 60000);</script>';

session_start();

if(!isset($_SESSION["isLogin"]) || $_SESSION["isLogin"] != 1) {
    echo "Please login...";
    $server = $_SERVER["SERVER_NAME"];
    header("Refresh:3; url=login.php");
    exit(0);
}
$ipChk = "";
if(!isset($_SESSION["loginIp"])){
    $curIp = $_SERVER["REMOTE_ADDR"];
    if(strcmp($curIp, $_SESSION["loginIp"]) != 0){
        $ipChk = "e?";
    }
}

if(!empty($_GET['disclaim']) ){
    $disclaims = htmlentities($_GET['disclaim']);
    
    if($disclaims == "Y"){
        header("Location: index.php?uFlag=1");
    }else if($disclaims == "N" && !isset($_SESSION['disclaim'])){
        $_SESSION['disclaim'] = "disclaim";
    }else if($disclaims == "N" && isset($_SESSION['disclaim']) && $_SESSION['disclaim'] !== "disclaim"){
        header("Location: index.php?uFlag=1");
    }
}else{
    header("Location: index.php?uFlag=1");
}

$userId = $_SESSION["userId"];
$loginId = $_SESSION["loginId"];
?>

<html>
<meta name="viewPort" content="width=device-width, initial-scale=1.0" />
<head>
    <title>BEST Mobile[<?=$loginId;?><?=$ipChk;?>]</title>
    <? include "inc-common.php" ?>
    <style type="text/css">
    <? include "css/icon.css" ?>
    </style>
    <link rel="stylesheet" href="./css/disclaimer.css?disc=<?php echo rand(1,999); ?>" />
    <script type="text/javascript">
        $(document).ready(function(){
            jQuery('div').live('pagehide',function(event, ui){
                var page = jQuery(event.target);
                if(page.attr('data-cache') == 'never'){
                    page.remove();
                }
            });
            
            $("#disclaimer-text").load("notice.txt");
            
            //$("#discCheck").attr("disabled","disabled");
            
            $("#discOk").button("disable")

            $("#loginTxt").val("<? echo $_SESSION["loginId"]; ?>");
            
            $("#discOk").click(function(){
                //$(this).button('disable');
                //$(this).attr("onClick","chgPwd()");
                $("#disclaimer-text").load("disclaimer.txt");
                //$("#discCheck").removeAttr("disabled");
                document.getElementById("discOk").addEventListener("click",chgPwd)
            })

            $("#discCheck").change(function(){
                var check = $(this).attr('value');
                if(check == 'on'){
                    $("#discOk").button('enable');
                    $(this).attr('value','')
                }else if(check == ''){
                    $("#discOk").button('disable');
                    $(this).attr('value','on')
                }
            })
        })
    </script>
    <script type="text/javascript">
        function onLoad(){
            document.addEventListener("deviceready", onDeviceReady, false);
        }
        function onDeviceReady(){
            document.addEventListener("backbutton", noth, false);
        }
        function noth(){
            alert('coba');
        }
        function chgPwd(){
            $(".chgPwd").css("display","block");
            $("#discOk").attr("disabled","disabled");
            $("#discCancel").attr("disabled","disabled");
            $("#discOk").button('enable')
        }
        function closed(){
            $(".chgPwd").css("display","none");
            $("#discOk").removeAttr("disabled");
            $("#discCancel").removeAttr("disabled");
        }
        function cancel(){
            window.open("index.php?uFlag=1", "_self");
        }
		//new change password with 000217
        function changePwdV2(){
			var userId = "<?=$loginId;?>";
			var clientId= "<?=$userId;?>";
			console.log("input 000217 : "+userId+" "+clientId);
			tr000217(userId,clientId,$("#oPassTxt").val(),$("#nPassTxt").val(),$("#cPassTxt").val());
            
		}
        function tr000217(userId,clientId,passCurrent,passNew,passConfirm) {
            $.ajax({
                type: "POST",
                url: "json/trproc.php",
                dataType : "json",
                data: "tr=000217&userId=" + userId + "&clientId=" + clientId + "&passCurrent=" + passCurrent + "&passNew=" + passNew + "&passConfirm=" + passConfirm,
                success: function(jsRet){  
					console.log(jsRet.out[0].status,jsRet.out[0].mesg);
					if (jsRet.out[0].status != 1) {
                        alert(jsRet.out[0].mesg);
                    } else { 
                        alert(jsRet.out[0].mesg); 
                        tr800910("Y");	
					}	
	   			},
                error: function(data, status, err) {
                    console.log("error forward : "+data);
                }
      	
            });
        }
       
        function tr800910(disclaim){
            var loginId = "<? echo $_SESSION['loginId']; ?>";
            console.log(loginId + " " + disclaim)
            $.ajax({
                type: "POST",
                url: "json/trproc.php",
                dataType: "json",
                data: "tr=800910&loginId=" + loginId + "&disclaim=" + disclaim,
                success: function(jsRet){
                    if(jsRet.status != 1){
                        alert(jsRet.mesg);
                    }else{
                        window.location = "tlogout.php"
                    }
                },
                error: function(data, status, err){
                    console.log("error forward: "+data);
                    alert('error communication with server.');
                }
            })
        }
    </script>
</head>
<body>
    <div data-role="page" id="home" data-cache="never">
        <? include "page-header-index.php" ?>
        <div data-role="content">
            <div class="ui-grid-solo">
            <center>
            <img src="img/logo-front.png" />
            </center>
            </div>

            <div id="disclaimer" class="disclaim">
                <div class="disclaim-main">
                    <textarea id="disclaimer-text" style="resize:none;padding:10px;height:305px;max-height:305px;text-align:justify;text-justify: inter-word;" readonly></textarea> 
                    <a style="margin-left:15px;" target="_blank" href="https://www.bcasekuritas.co.id/download/file/fG1hbnVhbC1ib29rLXBkZi1iYWhhc2F8OHxtYW51YWxfYm9va3N8"><span id="download"><img src="css/images/upload.png" height="15" /><span id="download">Download Manual</span></a>
                    <label disabled style="border:none;margin:10px;"><input type="checkbox" id="discCheck" name="checkbox-0" data-mini="true">Saya <span style="color:red;">telah</span> membaca <span style="color:red;">dan memahami</span> Buku Panduan Penggunaan BEST & Disclaimer</label>
                    <button id="discOk" value="Next"></button>
                    <button id="discCancel" onClick="cancel()">Cancel</button> 
                <div class="clear"></div>
                </div>
            </div>

            <div id="chgPassword" class="chgPwd">
                <div class="change-content">
                    <div id="toolbar">
                        <center><span class="toolbarName">Change Password</span></center>
                        <center><span class="close" onClick="closed()">&times;</span></center>
                        <div class="clear"></div>
                    </div>
                    <div class="change-main">
                        <table style="overflow-x:auto;">
                            <tr>
                                <td width="10%">Login ID</td>
                                <td width="25%"><input id="loginTxt" type="text" class="no-ime" data-mini="true" disabled></td>
                            </tr>
                            <tr>
                                <td width="10%">Old Password</td>
                                <td width="25%"><input id="oPassTxt" type="password" data-mini="true" /></td>
                            </tr>
                            <tr>
                                <td width="10%">New Password</td>
                                <td width="25%"><input id="nPassTxt" type="password" data-mini="true" /></td>
                            </tr>
                            <tr>
                                <td width="10%">Confirm New Password</td>
                                <td width="25%"><input id="cPassTxt" type="password" data-mini="true"></td>
                            </tr>
                            <tr>
                                <td colspan="2" align="center">
                                <a onClick="changePwdV2()" href="#" style="width:50%" data-role="button" data-icon="check" data-mini="true">Change</a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</body>
</html>
