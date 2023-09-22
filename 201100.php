<html>
<meta name="viewPort" content="width=device-width, initial-scale=1.0"/>
<head><title>Latihan</title>

<? include "inc-common.php" ?>
<style type="text/css">
    <? include "css/icon.css" ?>
</style>
</head>
<script type="text/javascript">
    function tr201100(stock){
        $.ajax({
            type: "POST",
            url: "json/trproc.php",
            dataType: "json",
            data: "tr=201100&namastock=" + stock,
            success: function(jsRet){
                if(jsRet.status != 1){
                    alert("Ada yg error");
                } else{
                    var stockname = jsRet.out[0].stockname;
                    var boardname = jsRet.out[0].boardname;
                  
                    document.getElementById("namestock").value = stockname;
                    document.getElementById("nameboard").value = boardname;
                  
                    var stockname = jsRet.out[0].stockname;
                    var boardname = jsRet.out[0].boardname;

                    var rows = [];
                    var cell_v = [
                        stockname,
                        boardname];
                    row = {id:0, cell:cell_v};
                    rows.push(row);
                    args = {dataType:"json", rows:rows, page:1, total:1};

                    $('#r-quote').flexAddData(args);


                  }
               }
          });
    }
   
 function qryTr201100(){
 var stock = document.getElementById("stock").value;
 tr201100(stock);
 }
 $(document).ready(function(){
    args={
        dataType : "json",
        singleSelect: false,
        height: "250",
        colModel : [
            {display : "Nama Perusahaan", width:220, sortable:false, align:'center'},
            {display : "Kode Group", width:220, sortable:false, align:'center'},],
            }
            $('#r-quote').flexigrid(args);
            });

            

</script>



<body>
<div data-role="page" id="home" data-cache="never">
<? include "page-header.php" ?>
<table>
    <tr>
        <td>Stock</td>
        <td><input type="text" id="stock" size="14"></td>
        <td><input type="button" value="Cek" onclick="qryTr201100()"></td>
    </tr>
    <tr>
        <td>Nama Stock</td>
        <td colspan="2"><input type="text" id="namestock" size="20"></td>
    </tr>
    <tr>
        <td>Board Code</td>
        <td colspan="2"><input type="text" id="nameboard" size="20"></td>
    </tr>
</table>
<table id="r-quote">
    <thead>
    </thead>
    <tbody>
    </tbody>
</table>
</div>
</body>
</html>
