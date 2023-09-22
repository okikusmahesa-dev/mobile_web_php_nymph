<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");

session_start();
if (!isset($_SESSION["isLogin"]) || $_SESSION["isLogin"] != 1) {
	echo "Please login...";
	header("refresh:3; url=login.php");
	exit(0);
}
	//$_SESSION["url"]="https://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];

?>
<html>
<head> 
	<title>Disclaimer</title> 
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<? include "inc-common.php" ?>
	<style type="text/css">
	<? include "css/icon.css" ?>
	<? include "css/marketSummary.css" ?>
	</style>
</head> 
<body style="backgound:#ffffff;">
<div data-role="page" id="home" data-cache="never">
<? include "page-header.php" ?>
	<div>
		<center><h1>Disclaimer</h1></center>
		<ol style = "text-align: justify; margin-right: 30px; ">
			<li style ="margin-bottom: 10px;" >Setiap keputusan investasi yang dibuat setelah membaca atau mendengar informasi yang diberikan kepada Nasabah oleh BCAS, baik melalui website, Aplikasi Online Trading, maupun dari karyawan  adalah sepenuhnya keputusan individu dari Nasabah, karena itu, segala resiko dan akibat dari keputusan investasi tersebut menjadi tanggung jawab Nasabah.  </li>
			<li  style ="margin-bottom: 10px;">Setiap Nasabah dengan ini membebaskan BCAS dari segala bentuk tanggung jawab atas kerugian maupun potensi kerugian yang terjadi, baik secara langsung maupun tidak langsung dari penggunaan Fasilitas Online Trading oleh Nasabah, yang diakibatkan oleh segala bentuk gangguan jaringan, hilangnya data, penundaan pengoperasian sistem Fasilitas Online Trading, virus komputer, kerusakan jaringan komunikasi, pencurian atau pengrusakan data, perubahan atau penggunaan informasi Nasabah oleh pihak lain yang tidak berwenang dan gangguan sistem lainnya yang menyebabkan Fasilitas Online Trading tidak dapat berfungsi sebagaimana mestinya. </li>
			<li style ="margin-bottom: 10px;">Nasabah dengan ini menyatakan bertanggung jawab penuh dan akan menanggung segala biaya, termasuk namun tidak terbatas pada biaya penasihat hukum, klaim, kerusakan (termasuk namun tidak terbatas pada kerusakan apapun yang bersifat tidak langsung, kerusakan yang tidak disengaja atau kerusakan apapun yang bersifat khusus), kerugian (termasuk namun tidak terbatas pada kerugian transaksi atau hilangnya pendapatan) atau kewajiban lainnya karena ketidakmampuan Nasabah dalam menggunakan sistem komputer Nasabah maupun Fasilitas Online Trading, termasuk namun tidak terbatas pada, ketidakmampuan untuk membatalkan order, pembatasan akses atau gangguan sistem. </li>
			<li style ="margin-bottom: 10px;">BCAS tidak bertanggung jawab terhadap segala akibat yang timbul terkait dengan penggunaan Fasilitas Online Trading ini, termasuk namun tidak terbatas pada kegagalan transmisi data, kerusakan data, tidak terkirimnya data baik sebagian maupun seluruhnya dan hal-hal lain yang terjadi diluar kendali BCAS sebagai penyedia Fasilitas Online Trading. Setiap Nasabah mengerti dan menyadari sepenuhnya akan risiko tersebut dan membebaskan BCAS dari segala kerugian yang sudah dan/atau mungkin timbul baik pada saat ataupun setelah dipergunakannya Fasilitas Online Trading. </li>
			<li style ="margin-bottom: 10px;">Setiap Nasabah dan/atau Pengguna Fasilitas Online Trading memahami dan mengetahui akan kemungkinan terjadinya penundaan data dan pesanan Nasabah dan/atau Pengguna Fasilitas Online Trading melalui Automated Order. BCAS tidak bertanggung jawab atas semua klaim atas kerugian yang mungkin muncul sebagai akibat dari penundaan ini. </li>
		</ol>
	</div>
<? include "page-footer.php" ?>
</div>
</body>
</html>
