<?
/*
    TR Coding
    ABCDEF
        
----AB
        00 : Setting
        09 : Master
        10 : Quote
        12 : Running
        13 : Ranking
        14 : Broker
        15 : Chart
        18 : Order
        19 : Index
        72 : News
        29 : Smart Strategy
        80 : Account 
 */

// Account
$trmap["000109"] = Array("./Request/Account/000109.xml", "python");    //test disclaimer by andi
$trmap["000419"] = Array("./Request/Account/000419.xml", "python");    //test disclaimer by andi
$trmap["082200"] = Array("./Request/Account/082200.xml", "python");    //cek login id by Toni
$trmap["082600"] = Array("./Request/Account/082600.xml", "python");     // calc python
$trmap["555555"] = Array("./Request/Account/555555.xml", "python");     // calc python
$trmap["612300"] = Array("./Request/Account/612601.xml", "python");     // test new Tr by andi
$trmap["800000"] = Array("./Request/Account/800000.xml", "");
$trmap["800001"] = Array("./Request/Account/800001.xml", "");
$trmap["800011"] = Array("./Request/Account/800011.xml", "python");     // calc python
$trmap["800016"] = Array("./Request/Account/800016.xml", "python");     // calc python
$trmap["800300"] = Array("./Request/Account/800300.xml", "");
//$trmap["800901"] = Array("./Request/Account/800901.xml", "python");     // change password mobile
$trmap["801001"] = Array("./Request/Account/801001.xml", "python"); //new pin coba
$trmap["801700"] = Array("./Request/Account/801700.xml", ""); //withdraw cash by toni
// Broker
$trmap["140101"] = Array("./Request/Broker/140101.xml", "");    //BrokerActivity
// Chart
$trmap["154000"] = Array("./Request/Chart/154000.xml", "");
// Index, Etc
$trmap["190000"] = Array("./Request/Index/190000.xml", ""); //MarketSummary
$trmap["710009"] = Array("./Request/LoginTrace.xml", "python"); //Login Hist
$trmap["090010"] = Array("./Request/Master/090010.xml", "");
// Order
$trmap["082700"] = Array("./Request/Order/082700.xml", "python"); //All GTC List by  Toni
$trmap["082800"] = Array("./Request/Order/082800.xml", "python"); //All Loged In users by Toni
$trmap["168900"] = Array("./Request/Order/168900.xml", "python"); //gtc orderlist baru agung
$trmap["189000"] = Array("./Request/Order/180000.xml", ""); // buy
$trmap["189100"] = Array("./Request/Order/180100.xml", "");
$trmap["189200"] = Array("./Request/Order/180200.xml", ""); //amend
$trmap["189300"] = Array("./Request/Order/180300.xml", ""); //withdraw
$trmap["182600"] = Array("./Request/Order/182600.xml", "");
$trmap["182605"] = Array("./Request/Order/182605.xml", "python"); //All Order List by Toni
$trmap["182800"] = Array("./Request/Order/182800.xml", "");
$trmap["189001"] = Array("./Request/Order/189001.xml", ""); // sell
$trmap["710024"] = Array("./Request/Order/710024.xml", "python");   //check bussines day by Toni
$trmap["800000"] = Array("./Request/Order/800000.xml", ""); //portofolTEST_TONI
$trmap["800017"] = Array("./Request/Order/800017.xml", "python");   //Time Flag by TONI
// Quote
$trmap["100000"] = Array("./Request/Quote/100000.xml", "");
$trmap["100001"] = Array("./Request/Quote/100001.xml", "");
$trmap["100006"] = Array("./Request/Quote/100006.xml", "");     // calc
$trmap["100010"] = Array("./Request/Quote/100010.xml", "python"); //getHairCut
$trmap["100500"] = Array("./Request/Quote/100500.xml", ""); //ForeignSummary
$trmap["100700"] = Array("./Request/Quote/100700.xml", "");    //stock daily
$trmap["100701"] = Array("./Request/Quote/100701.xml", "python");    //stock daily
$trmap["100800"] = Array("./Request/Quote/100800.xml", ""); //BrokerTransbyStock
$trmap["101200"] = Array("./Request/Quote/101200.xml", "");
$trmap["101201"] = Array("./Request/Quote/101201.xml", "");
$trmap["101211"] = Array("./Request/Quote/101211.xml", "python"); // Watch List V2
$trmap["101222"] = Array("./Request/Quote/101222.xml", "python"); //watchlist add update mato
// Ranking
$trmap["130000"] = Array("./Request/Ranking/130000.xml", "");   //Gainers Lossers
$trmap["130500"] = Array("./Request/Ranking/130500.xml", "");   //ForeignTransSummary
// Running
$trmap["120001"] = Array("./Request/Running/120001.xml", "");   // running
$trmap["120200"] = Array("./Request/Running/120200.xml", "");
// Setting
$trmap["000101"] = Array("./Request/Setting/000101.xml", "");   // ping
$trmap["000102"] = Array("./Request/Setting/000102.xml", "");
$trmap["000105"] = Array("./Request/Setting/000105.xml", "python");// login Hist Toni
$trmap["000106"] = Array("./Request/Setting/000106.xml", "python");// Cek Ping Toni
$trmap["000200"] = Array("./Request/Setting/000200.xml", "");
$trmap["000203"] = Array("./Request/Setting/000203.xml", "python"); //new pin coba
$trmap["080010"] = Array("./Request/Setting/000203.xml", "python"); //new pin ijan c
$trmap["000206"] = Array("./Request/Setting/000206.xml", "python"); //try pin (not encrypted but python)
$trmap["000210"] = Array("./Request/Setting/000210.xml", "python"); //cek pin toni
$trmap["000212"] = Array("./Request/Setting/000212.xml", "python"); //cek pin toni
$trmap["000215"] = Array("./Request/Setting/000215.xml", "python");// block hist Toni
$trmap["080011"] = Array("./Request/Setting/080011.xml", "python"); //new pin ijan python
$trmap["080901"] = Array("./Request/Setting/080901.xml", "python");    //cek user status by Toni
$trmap["830000"] = Array("./Request/Setting/830000.xml", "python"); //chgPasswordd mobilee
$trmap["830001"] = Array("./Request/Setting/830001.xml", "python"); //chgPinn mobilee
$trmap["900000"] = Array("./Request/Setting/900000.xml", "python"); //API for Bot
// Stock
$trmap["141000"] = Array("./Request/Stock/Search/141000.xml", "");    //breaking hi / low
$trmap["141500"] = Array("./Request/Stock/Search/141500.xml", "");    //ichimoku
?>
