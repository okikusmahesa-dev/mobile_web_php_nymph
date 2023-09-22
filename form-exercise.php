<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>BCA Sekuritas - Administrator</title>
        <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="Formulir Permohonan Melakukan Exercise Hak Atas Efek">
		<meta name="author" content="PT.BCA SEKURITAS">
		
		<style type='text/css' media='print'>
			#prnBtn {display : none}
			#winBtn {display : none}
		</style>
	</head>
	
	<body>
	<h3>PT.BCA Sekuritas</h3>
	<table border='1px' cellspacing='2px' cellpadding='2px' width='100%'>
		<thead>
			<th colspan='3' align='center' bgcolor='#00a6b6' width='100%'>FORMULIR PERMOHONAN </br>MELAKUKAN EXERCISE HAK ATAS EFEK</th>
		</thead>
		<tbody>
			<tr>
				<td colspan='3'>yang bertanda tangan dibawah ini : </td>
			</tr>
			<tr>
				<td>
				<table width = '100%'>
					<tr>
					<td width='17%'>Nama Nasabah</td>
						<td align='center' width='10%'>:</td>
						<td width='=73%'><?=$_GET['nama']?></td>
					</tr>
					<tr>
						<td>Kode Nasabah</td>
						<td align='center'>:</td>
						<td><?=$_GET['id']?></td>
					</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td colspan='3'> &nbsp;Dengan ini mengajukan permohonan untuk dilakukan exercise terhadap hak efek saya sebagai berikut :
				<table border='0px' width='100%'>
					<tr>
						<td width='17%'>Kode Saham </td>
						<td  align='center' width='10%'>:</td>
						<td width='73%'><?=$_GET['kode']?></td>
					</tr>
					<tr>
						<td>Jumlah Saham </td>
						<td align='center'>:</td>
						<td><?=number_format($_GET['jml'],0,",",".")?> Lot</td>
					</tr>
					<tr>
						<td>Jumlah Rupiah</td>
						<td align='center'>:</td>
						<td><?=number_format($_GET['lbr'],0,",",".")?> lbr/Rp. <?=number_format($_GET['hrg'],2,",",".")?> = Rp. <?=number_format($_GET['tot'],2,",",".")?></td>
					</tr>
					<tr>
						<td>Tanggal Exercise </td>
						<td align='center'>:</td>
						<td><?= substr($_GET['tgl'],0,10)?></td>
					</tr>
					<tr><td colspan='3'>&nbsp;</td></tr>
					<tr><td colspan='3'>&nbsp;</td></tr>
					<tr><td colspan='3'>&nbsp;</td></tr>
					<tr><td colspan='3'>Demikian permohonan ini kami sampaikan mohon untuk dapat dipergunakan sebagaimana mestinya dalam proses pelaksanaan Exercise atas Efek milik saya</td></tr>
					<tr><td colspan='3'>&nbsp;</td></tr>
					<tr><td colspan='3'>&nbsp;</td></tr>
					<tr><td colspan='3'>&nbsp;</td></tr>
				</table>
				</td>
			</tr>
			<tr>
				<td colspan='3'>
					<font size='2'>
					<table border='1px' width='100%'cellpadding='0' cellspacing='0'>
						<tr>
							<td width='20%'>
								<table border='1px' width='100%' cellpadding='0' cellspacing='0'>
									<tr>
										<td align='center'>Yang Mengajukan,</td>
									</tr>
									<tr>
										<td align='center'><br/><br/><?=$_GET['logid']?><br/><br/> <?=$_GET['nama']?> &nbsp;</td>
									</tr>
								</table>
							</td>
							<td width='20%'>&nbsp;</td>
							<td colspan='3' >
								<table border='1px' width='100%'cellpadding='0' cellspacing='0'>
									<tr>
										<td colspan='3' align='center'>Disetujui Oleh/Approval by,</td>
									</tr>
									<tr>
										<td align='center' width='33%'><br/><br/>&nbsp;<br/><br/></td>
										<td align='center' width='33%'><br/><br/>&nbsp;<br/><br/><br/></td>
										<td align='center' width='33%'><br/><br/>&nbsp;<br/><br/></td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td width='20%'>Nasabah</td>
							<td width='20%'>Sales Equity</td>
							<td width='20%'>Risk Managemant</td>
							<td width='20%'><i>Head of Operations</i></td>
							<td width='20%'>Finance</td>
						</tr>
						<tr>
							<td width='20%'>Settlement</br></br>Tanggal:</br>&nbsp</td>
							<td width='20%'></br></br></br>&nbsp;</td>
							<td width='20%'></br></br></br>&nbsp;</td>
							<td width='20%'></br></br></br>&nbsp;</td>
							<td width='20%'></br></br></br>&nbsp;</td>
						</tr>
						
					</table>
					</font>
				</td>
			</tr>
		</tbody>
	</table>
	<button id ='prnBtn' onClick="window.print()">Print this page</button>
	</body>
</html>
