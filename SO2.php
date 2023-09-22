<!--
Created By   : Faddi
Design By    : Faddi
Date Created : 11 May 2011
-->

<?php
session_start();
if ($_SESSION["isLogin"] != 1) {
    echo "Please login...";
    header("refresh:3; url=login.php");
    exit(0);
}

$userId = $_SESSION["userId"];
$clientId = $_SESSION["loginId"];
$pinState = 0;
$pinLogin = 0;
?>
<html>
<head>
<title>Soal 2</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<? include "inc-common.php" ?>
<script type="text/javascript">
$(document).ready(function(){
    qryTr777711();
    args={
        dataType : "json",
        singleSelect : true,
        height: "250",
        colModel : [
            {display: "No", width:75, sortable:false, align:'center'},
            {display : "Name", width:258, sortable:false, align:'center'}
        ],
        buttons: [
            {name:'Refresh', bclass: 'refresh'},
            {separator: true},
            {name:'Up', bclass: 'up'},
            {separator: true},
            {name:'Down', bclass: 'down'}
        ]
    }
    $("#r-flexi").flexigrid(args);
    
    function tr777711(loginid){
        $.ajax({
            type: "POST",
            url: "json/trproc.php",
            dataType: "json",
            data: "tr=777711&loginId=" + loginid,
            success: function(jsRet){
                if(jsRet.status != 1){
                    alert("Error...");
                }else{
                    Rows = []
                    var cnt = jsRet.out.length;

                    for(i=0;i<cnt;i++){
                        id_v = "id"+i;

                        cell_v = [
                            "<input type='radio' name='selection' />",
                            jsRet.out[i]["userName"],
                        ];
                        row = {id:id_v, cell:cell_v};
                        Rows.push(row);
                    }
                    args = {dataType:"json", rows: Rows, page: 1, total: cnt};
                    $("#r-flexi").flexAddData(args);
                }
            }
        })
    }
    
    function qryTr777711(){
        var i = ""
        $("#searchit").click(function(){
            i = $("#searchval").val();
            tr777711(i);
        });
        tr777711(i);
    }

    $(".refresh").click(function(){
        window.location = "http://192.168.22.16/SO2.php";
    });

    $(".up").click(function(){
        alert("OK");
    });

    $(".down").click(function(){
        alert("OK");
    });
})
</script>
</head>

<body style="background:#ffffff;">
<div data-role="page" id="home" data-cache="never">
    <? include "page-header.php" ?>
    
    <input id="searchval" type="text" name="stockName" data-mini="true">
    <button id="searchit" data-role="button" data-mini="true">Search</button>
    
    <br><br>
    
    <table id="r-flexi">
    </table>
    
    <? include "page-footer.php" ?>
</div>

</body>


</html>
