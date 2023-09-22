<html>i
<meta name="viewPort" content="width=device-width, initial-scale=1.0"/>
<head><title>Latihan Tampil </title>
<? include "inc-common.php" ?>
<style type="text/css">
<? include "css/icon.css"?>
</style>
</head>
<script type="text/javascript">
$(document).ready(function(){
    args={
        dataType : "json",
        singleSelect: false,
        height: "250",
        colModel: [
            {display : "Kode", width:50, sortable:false, align:'center'},
            {display : "Nama Perusahaan", width:200, sortable:false, align:'center'},
            {display : "Kode Group", width:200, sortable:false, align:'center'},],}
            $('#r-table').flexigrid(args);
            });

          
</script>
<body>
<div data-role="page" id="home" data-cache="never">
<? include "page-header.php"?>
<table>
    <tr>
        <td>Stock</td>
        <td><input type="text" id="stock" size="14"></td>
    </tr>
</table>
<table id="r-table">
    <thead>
    </thead>
    <tbody>
    </tbody>
</table>
</html>
