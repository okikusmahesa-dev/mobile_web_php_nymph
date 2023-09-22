<?php

function request_url($method)
{
    //global $TOKEN;
	return "https://api.telegram.org/bot393514063:AAE3IJWl2xH3varnhtGYYtgMSrDgKs89hHI/". $method;
//	return "https://api.telegram.org/bot338799918:AAGGDsZBI4rX3zXyQncv8-18Y4f4yo8Y3Ts/". $method;
}

function send_reply($chatid, $msgid, $text)
{
    $data = array(
        'chat_id' => $chatid,
        'parse_mode' => 'HTML',
        'text'  => $text,
        'reply_to_message_id' => $msgid
    );
    // use key 'http' even if you send the request to https://...
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        ),
    );
    $context  = stream_context_create($options);
    $result = file_get_contents(request_url('sendMessage'), false, $context);
}
function makedir()
{
   // $data = array(
    //    'chat_id' => $chatid,
    //    'parse_mode' => 'HTML',
    //    'text'  => $text,
    //    'reply_to_message_id' => $msgid
   // );
    // use key 'http' even if you send the request to https://...
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            //'content' => http_build_query($data),
        ),
    );
    $context  = stream_context_create($options);
    $result = file_get_contents('https://mobile.bcasekuritas.co.id/command.php', false, $context);
}



function process_message($message)
{
   // $db = new mysqli("localhost","u6224442_panitia","panitiaseminar2017","u6224442_seminar;
    $updateid = $message["update_id"];
    $message_data = $message["message"];
    $pesan = $message_data["text"];

    if (isset($message_data["text"])) {
        $chatid = $message_data["chat"]["id"];
        $message_id = $message_data["message_id"];

//bikin respon disini berdasar message
        $command = explode("#", $pesan);
        switch (strtolower(str_replace("/", "", $command[0]))) {
          
            case 'info':
                $response = info();
				makedir();
               break;
            case 'daftar':
                $response = 'daaftar';//daftar($db,$command[1],$command[2],$command[3],$command[4]);
               break;
            case 'list':
                $response = 'list';//listt($db,$command[1]);
               break;
            case 'regis':
                $response = 'regis';//regis($db,$command[1]);
               break;
            case 'search':
                $response = 'search';//search($db,$command[1]);
               break;
            case 'update':
                $response = 'update';//update($db,$command[1],$command[2],$command[3],$command[4]);
               break;
            case 'start' :
                $response = start();
                break;
            case 'kuota' :
                $response = kuota();
                break;
            default:
                $response = "Perintah tidak ditemukan ";
                break;
        }
        
        send_reply($chatid, $message_id, $response);
    }
    return $updateid;
}

function start(){
    return "Monitoring server activated";
	return "Selamat datang di fitur pemandu pendaftaran seminar umum 20 mei 2017\nsilahkan ketik garis miring ('/') untuk melihat daftar perintah yang bisa digunakan di fitur ini";
}

function info(){
    return "
Tema : \n<b>Sukses dengan ilmu komputer kenapa tidak !</b>\nPembicara : \n<b>1. Ir. Budi Rahardjo, M.Sc., Ph.D </b> \n(Praktisi IT dan Ahli Keamanan Informasi / Dosen ITB) \n<b>2. Dr. Arya Adhyaksa Waskita, S.SI., M.SI</b> \n(Dosen Pasca Sarjana ERESHA)\nHTM : \n<b>Rp 75,000</b>" ;
}
/*
function daftar($db,$nim, $nama, $kelompok, $hadir){
        
    if ($db->connect_errno) {
        return "Ada Kesalahan".$db->connect_errno;
    }

    if (strlen($nim)<=0 ||strlen($kelompok)<=0 ||strlen($hadir)!=1 ) {
        return "Format input salah!\n\ngunakan format sebagai berikut:\ndaftar#[nim]#[nama]#[kelompok]#kehadiran[H/T]\n\ncontoh:\n daftar#2013140001#Mahasiswa Teladan#04TPLP002#H";
    }

    $sql = "select * from bankdata where nim = '$nim'";
    $hasil = $db->query($sql);
    if ($hasil->num_rows) {
        $row = $hasil->fetch_assoc();
        $nama = $row['nama'];
    }else{
         $db->close();
        return "Nim $nim tidak terdaftar sebagai mahasiswa aktif di ERESHA ataupun di TI UNPAM";
    }

    $sql = "insert into listed(nim,nama,kelompok,hadir,remark,tanggal) values('$nim','".strtoupper($nama)."','".strtoupper(trim($kelompok))."','".strtoupper($hadir)."','L',now())";
    if ($db->query($sql)===TRUE) {
        $db->close();
        return "pendaftaran sukses:\n\nNim: $nim\nNama: $nama\nKelompok: $kelompok\nKehadiran: $hadir\n\nSilahkan melakukan pembayaran ke stand seminar di depan lift lt.4 Gd Viktor dengan dikoordinir oleh ketua kelompok\nSegera lakukan pembayaran, karena data yang tersimpan disini hanya bertahan selama 2 hari, setelah 2 hari silahkan ulangi proses pendaftaran";
    }else{
         $db->close();
        if($db->errno == 1062){
            return "Nim $nim sudah pernah di daftarkan\nGunakan fitur search untuk melihat detil-nya\natau gunakan perintah list#$kelompok untuk melihat list data yang sudah masuk ke panitia sebagai calon peserta seminar\natau gunakan perintah regis#$kelompok untuk melihat list data yang sudah masuk ke panitia sebagai peserta seminar";
        }
        return "Error no: ".$db->errno;
    }
}

function listt($db,$kelas){
    if ($db->connect_errno) {
        return "Ada Kesalahan".$db->connect_errno;
    }
    if (strlen($kelas)<=0) {
        return "format input salah!!\ngunakan format sebagai berikut:\nlist#[kelas]";
    }
    $sql = "SELECT * FROM listed WHERE kelompok = '$kelas' and remark = 'L'";
    $hasil = $db->query($sql);
    if ($hasil->num_rows<=0) {
         $db->close();
        return "Tidak calon peserta dari kelompok $kelas";
    }
    while ($rows = $hasil->fetch_assoc()){
        $hsl[] = array('nim' => $rows['nim'],
        'nama' => $rows['nama'],
        'kelompok' => $rows['kelompok'],
        'hadir' => $rows['hadir']);
    }
    $out="List pendaftar kelompok $kelas:\n";$i=1;
    foreach ($hsl as $row ) {
        $out .= $i++.".| ".$row['nim']." -- ".$row['nama']." -- ".$row['hadir']." \n";
    }
     $db->close();
    return $out;

}
function regis($db,$kelas){
    if ($db->connect_errno) {
        return "Ada Kesalahan".$db->connect_errno;
    }
    if (strlen($kelas)<=0) {
        return "format input salah!!\ngunakan format sebagai berikut:\nregis#[kelas]";
    }
    $sql = "SELECT * FROM listed WHERE kelompok = '$kelas' and remark = 'R'";
    $hasil = $db->query($sql);
    if ($hasil->num_rows<=0) {
         $db->close();
        return "Tidak ada peserta dari kelompok $kelas";
    }
    while ($rows = $hasil->fetch_assoc()){
        $hsl[] = array('nim' => $rows['nim'],
        'nama' => $rows['nama'],
        'kelompok' => $rows['kelompok'],
        'hadir' => $rows['hadir']);
    }
    $out="List pendaftar kelompok $kelas:\n";$i=1;
    foreach ($hsl as $row ) {
        $out .= $i++.".| ".$row['nim']." -- ".$row['nama']." -- ".$row['hadir']." \n";
    }
     $db->close();
    return $out;

}

function search($db,$nim){
    if ($db->connect_errno) {
        return "Ada Kesalahan".$db->connect_errno;
    }
    if (strlen($nim)<=0) {
        return "format input salah!!\ngunakan format sebagai berikut:\nsearch#[nim]";
    }
    $sql = "SELECT * FROM listed WHERE nim = '$nim'";
    $hasil = $db->query($sql);
    if ($hasil->num_rows<=0) {
         $db->close();
        return "nim $nim belum ada di dalam daftar";
    }
    while ($rows = $hasil->fetch_assoc()){
        $hsl[] = array('nim' => $rows['nim'],
        'nama' => $rows['nama'],
        'kelompok' => $rows['kelompok'],
        'hadir' => $rows['hadir'],
        'remark' => $rows['remark']);
    }
    $out="detail search:";$i=1;
    foreach ($hsl as $row ) {
        $out .="\nNim: ". $row['nim'];
        $out .="\nNama: ".$row['nama'];
        $out .="\nKelompok: ".$row['kelompok'];
        $out .="\nKehadiran: ";
        if($row['hadir']=='H'){
            $out .= "Hadir";
        }else{
            $out .= "Tidak Hadir";
        }
        $out .="\nStatus: ";
        if($row['remark'] == 'L'){
            $out .= "Belum Melakukan Pembayaran";
        }else{
            $out .= "Sudah Melakukan Pembayaran";
        }
    }
     $db->close();
    return $out;
}
function update($db,$nim, $nama, $kelompok, $hadir){
        
    if ($db->connect_errno) {
        return "Ada Kesalahan".$db->connect_errno;
    }
    if ( strlen($nama)<=0 ||strlen($kelompok)<=0 ||strlen($hadir)!=1 ) {
        return "Format input salah!\n\ngunakan format sebagai berikut:\nupdate#[nim]#[nama]#[kelompok]#kehadiran[H/T]\n\ncontoh:\n update#2013140001#Mahasiswa Baik Baik#04TPLP002#T";
    }
    $sql = "update listed set kelompok = '".strtoupper($kelompok)."',hadir = '".strtoupper($hadir)."' where nim = '$nim'";
    if ($db->query($sql)===TRUE) {
        $db->close();
        return "sukses update data";
    }else{
        if($db->errno == 1062){
             $db->close();
            return "Nim yang dimasukan sudah pernah di daftarkan\nGunakan fitur search untuk melihat detil-nya";
        }
         $db->close();
        return "Error no: ".$db->errno;
    }
}
*/
function kuota(){
    return "fitur belum tersedia";
}
//daftar('125','toni','lkajsd','T');
$entityBody = file_get_contents('php://input');
$message = json_decode($entityBody, true);
process_message($message);

?>
