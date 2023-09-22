<?
require_once("trmap.php");

print("hello world.");
$fp1 = fopen("php://stdout", "w+");
$fp2 = fopen("php://output", "w+");
$fp3 = fopen("php://stderr", "w+");
fwrite($fp1, "hello world1.\n");
fwrite($fp2, "hello world2.\n");
fwrite($fp3, "hello world3.\n");

print_r($trmap);
printf("===============================\n");
$trmap = Array();
$trmap["000011"] = Array( "filename", "service");
print_r($trmap);

fprintf(STDERR, "hello...");

$p = pack("A8", "1234");
printf("pack=(%s)", $p);
?>
