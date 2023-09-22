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
$trmap["800020"] = Array("./Request/Account/800020.xml", "python");     // Portfolio for MobileWeb
$trmap["805001"] = Array("./Request/Account/805001.xml", "python");     // Right Issue Web
$trmap["805002"] = Array("./Request/Account/805002.xml", "python");     // View Apply Right Issue 
$trmap["805010"] = Array("./Request/Account/805010.xml", "python");     // Insert Right Issue


$trmap["800005"] = Array("./Request/Account/800005.xml", "python");     // settlement breakdown
$trmap["800011"] = Array("./Request/Account/800011.xml", "python");     // calc python
$trmap["800016"] = Array("./Request/Account/800016.xml", "python");     // calc python
$trmap["800300"] = Array("./Request/Account/800300.xml", "");
$trmap["800910"] = Array("./Request/Account/800910.xml", "python");     // insert/update disclaimer
$trmap["802700"] = Array("./Request/Account/802700.xml", "python");     // List Withdraw user
$trmap["802800"] = Array("./Request/Account/802800.xml", "python");     // Report (pdf-download)
//$trmap["800901"] = Array("./Request/Account/800901.xml", "python");     // change password mobile
$trmap["801001"] = Array("./Request/Account/801001.xml", "python"); //new pin coba
//$trmap["801700"] = Array("./Request/Account/801700.xml", ""); //withdraw cash by toni
$trmap["801707"] = Array("./Request/Account/801700.xml", "python"); //withdraw cash for automatic
$trmap["801708"] = Array("./Request/Account/801708.xml", "python"); // Withdraw date
$trmap["801701"] = Array("./Request/Account/801701.xml", "python"); // Withdraw cash Form 
$trmap["710010"] = Array("./Request/Logout.xml", "python"); // logout
$trmap["710013"] = Array("./Request/Account/710013.xml", "python"); //withdraw cash by toni
// Broker
// Broker
$trmap["140101"] = Array("./Request/Broker/140101.xml", "");    //BrokerActivity
// Chart
$trmap["154000"] = Array("./Request/Chart/154000.xml", ""); // Chart Stock Daily
$trmap["154002"] = Array("./Request/Chart/154002.xml", ""); // Chart Stock Weekly
$trmap["154004"] = Array("./Request/Chart/154004.xml", ""); // Chart Stock Monthly
$trmap["154011"] = Array("./Request/Chart/154011.xml", ""); // Chart Stock Minute
$trmap["154012"] = Array("./Request/Chart/154012.xml", "python"); // Chart Stock Minute Thumbnail

$trmap["154021"] = Array("./Request/Chart/154021.xml", ""); // Chart Stock Tick
$trmap["150000"] = Array("./Request/Chart/150000.xml", ""); // Index Stock Daily
$trmap["150002"] = Array("./Request/Chart/150002.xml", ""); // Index Stock Weekly
$trmap["150004"] = Array("./Request/Chart/150004.xml", ""); // Index Stock Monthly
$trmap["150011"] = Array("./Request/Chart/150011.xml", ""); // Index Stock Minute
$trmap["150021"] = Array("./Request/Chart/150021.xml", ""); // Index Stock Tick
$trmap["150012"] = Array("./Request/Chart/150012.xml", "python"); // Chart Index Minute Thumbnail

// Index, Etc
$trmap["190000"] = Array("./Request/Index/190000.xml", ""); // Index Summary
$trmap["190001"] = Array("./Request/Index/190001.xml", ""); // Index Detail
$trmap["190013"] = Array("./Request/Index/190013.xml", "python"); // Index Detail Transaction(Stock) List
$trmap["190014"] = Array("./Request/Index/190014.xml", "python"); // HOME WEB

// Global Index, Currency, Commodities
$trmap["191000"] = Array("./Request/Index/191000.xml", "python"); // Global Index
$trmap["191100"] = Array("./Request/Index/191100.xml", "python"); // Global Currency
$trmap["191200"] = Array("./Request/Index/191200.xml", "python"); // Global Commodities
$trmap["191101"] = Array("./Request/Index/191101.xml", "python"); // Global Commodities

$trmap["710009"] = Array("./Request/LoginTrace.xml", "python"); //Login Hist
$trmap["090001"] = Array("./Request/Master/090001.xml", "");  // Index Master
$trmap["090010"] = Array("./Request/Master/090010.xml", "");    // Stock Master
$trmap["090014"] = Array("./Request/Master/090014.xml", "python");  // Stock Master for MobileWeb
// Order
$trmap["082700"] = Array("./Request/Stock/Order/082700.xml", "python"); //All GTC List by  Toni
$trmap["082800"] = Array("./Request/Order/082800.xml", "python"); //All Loged In users by Toni
$trmap["168900"] = Array("./Request/Order/168900.xml", "python"); //gtc orderlist baru agung
$trmap["189000"] = Array("./Request/Order/180000.xml", ""); // buy
$trmap["189100"] = Array("./Request/Order/180100.xml", "");
$trmap["189200"] = Array("./Request/Order/180200.xml", ""); //amend
$trmap["189300"] = Array("./Request/Order/180300.xml", ""); //withdraw
$trmap["189090"] = Array("./Request/Order/189090.xml", "python"); // Check Order Valid
$trmap["186000"] = Array("./Request/Order/186000.xml", ""); //GTC
$trmap["186001"] = Array("./Request/Order/186001.xml", "python"); //GTC v2
$trmap["186801"] = Array("./Request/Stock/Order/186801.xml", ""); //GTC delete
$trmap["184520"] = Array("./Request/Stock/Order/184520.xml", "python"); // New for 184500 for web only
$trmap["184504"] = Array("./Request/Stock/Order/184504.xml", ""); // Auto Order set seq preseq preseqw
$trmap["184501"] = Array("./Request/Stock/Order/184501.xml", ""); //Auto Order List
$trmap["184509"] = Array("./Request/Stock/Order/184509.xml", "python"); //Auto Order History List
$trmap["184502"] = Array("./Request/Stock/Order/184502.xml", ""); //AO Delete
$trmap["184505"] = Array("./Request/Stock/Order/184505.xml", "python"); //AO Delete Child from Head
$trmap["184503"] = Array("./Request/Stock/Order/184503.xml", ""); //AO On Off
$trmap["184507"] = Array("./Request/Stock/Order/184507.xml", "python"); //AO All Del, On, Off
$trmap["189400"] = Array("./Request/Stock/Order/189400.xml", "python"); // New for 189400 SAO for web only
$trmap["182600"] = Array("./Request/Order/182600.xml", "");
$trmap["182605"] = Array("./Request/Order/182605.xml", "python"); //All Order List by Toni
$trmap["182800"] = Array("./Request/Order/182800.xml", "");
$trmap["189001"] = Array("./Request/Order/189001.xml", ""); // sell
$trmap["710024"] = Array("./Request/Order/710024.xml", "python");   //check bussines day by Toni
$trmap["710025"] = Array("./Request/Order/710025.xml", "python");   //check bussines day by Toni
$trmap["800017"] = Array("./Request/Order/800017.xml", "python");   //Time Flag by TONI
$trmap["187800"] = Array("./Request/Order/187800.xml", "python");   //Booking Order Input
$trmap["187801"] = Array("./Request/Order/187801.xml", "python");   //Booking Order List
$trmap["187802"] = Array("./Request/Order/187802.xml", "python");   //Booking Order Delete
// Quote
$trmap["100000"] = Array("./Request/Quote/100000.xml", "");
$trmap["100001"] = Array("./Request/Quote/100001.xml", "");
$trmap["100005"] = Array("./Request/Quote/100005.xml", ""); // Stock Tick History
$trmap["100006"] = Array("./Request/Quote/100006.xml", "");     // calc
$trmap["100010"] = Array("./Request/Quote/100010.xml", "python"); //getHairCut
$trmap["100011"] = Array("./Request/Quote/100011.xml", "python"); //Remarks2 Fahri
$trmap["100013"] = Array("./Request/Quote/100013.xml", "python"); // CurPrice + Orderbook
$trmap["100500"] = Array("./Request/Quote/100500.xml", ""); //ForeignSummary
$trmap["100700"] = Array("./Request/Quote/100700.xml", "");    //stock daily
$trmap["100701"] = Array("./Request/Quote/100701.xml", "python");    //stock daily
$trmap["100800"] = Array("./Request/Quote/100800.xml", ""); //BrokerTransbyStock

$trmap["101000"] = Array("./Request/Quote/101000.xml", ""); // Stock Order Detail Buy
$trmap["101001"] = Array("./Request/Quote/101001.xml", ""); // Stock Order Detail Sell
$trmap["101100"] = Array("./Request/Quote/101100.xml", ""); // Stock Trade Buyer
$trmap["101101"] = Array("./Request/Quote/101101.xml", ""); // Stock Trade Seller
$trmap["101102"] = Array("./Request/Quote/101102.xml", ""); // Stock Trade Detail Sell
$trmap["101200"] = Array("./Request/Quote/101200.xml", "");
$trmap["101201"] = Array("./Request/Quote/101201.xml", "");
$trmap["101204"] = Array("./Request/Quote/101204.xml", ""); // Create Rename Watchlist
$trmap["101600"] = Array("./Request/Quote/101600.xml", ""); // Foreign Transaction by Stock


$trmap["101211"] = Array("./Request/Quote/101211.xml", "python"); // Watch List V2
$trmap["101221"] = Array("./Request/Quote/101221.xml", ""); //Watch List Add Data
$trmap["101222"] = Array("./Request/Quote/101222.xml", "python"); //watchlist add update mato
$trmap["101224"] = Array("./Request/Quote/101224.xml", "python"); //watchlist add new Group
$trmap["101231"] = Array("./Request/Quote/101231.xml", "python"); //watchlist append stock item in group
$trmap["101232"] = Array("./Request/Quote/101232.xml", "python"); //watchlist save stock items in group
$trmap["101233"] = Array("./Request/Quote/101233.xml", "python"); //Delete Watchlist Group
$trmap["101235"] = Array("./Request/Quote/101235.xml", "python"); //Add Symbol untuk Watchlist
// Ranking
$trmap["103100"] = Array("./Request/Quote/103100.xml", "python"); // IDX watchlist 
$trmap["130000"] = Array("./Request/Ranking/130000.xml", "");   //Gainers Lossers
$trmap["130002"] = Array("./Request/Ranking/130002.xml", "python");   //Gainers Lossers Grid View
$trmap["130500"] = Array("./Request/Ranking/130500.xml", "");   //ForeignTransSummary
// Running
$trmap["120001"] = Array("./Request/Running/120001.xml", "");   // running
$trmap["120200"] = Array("./Request/Running/120200.xml", "");
// Setting
$trmap["000101"] = Array("./Request/Setting/000101.xml", "");   // ping
$trmap["000102"] = Array("./Request/Setting/000102.xml", "");
$trmap["000105"] = Array("./Request/Setting/000105.xml", "python");// login Hist Toni
$trmap["000106"] = Array("./Request/Setting/000106.xml", "python");// Cek Ping Toni
$trmap["000110"] = Array("./Request/Setting/000110.xml", "python");// Check Account Status(password)
$trmap["000114"] = Array("./Request/Setting/000114.xml", "python");// Get HTS Setting
$trmap["000116"] = Array("./Request/Setting/000116.xml", "python");// Get User List(Multi User Id
$trmap["000200"] = Array("./Request/Setting/000200.xml", "");
$trmap["000203"] = Array("./Request/Setting/000203.xml", "python"); //new pin coba
$trmap["080010"] = Array("./Request/Setting/000203.xml", "python"); //new pin ijan c
$trmap["000206"] = Array("./Request/Setting/000206.xml", "python"); //try pin (not encrypted but python)
$trmap["000210"] = Array("./Request/Setting/000210.xml", "python"); //cek pin toni
$trmap["000212"] = Array("./Request/Setting/000212.xml", "python"); //cek pin toni
$trmap["000215"] = Array("./Request/Setting/000215.xml", "python");// block hist Toni
$trmap["000216"] = Array("./Request/Account/000216.xml", "python"); // ChangePin V.2 Fahri
$trmap["000217"] = Array("./Request/Account/000217.xml", "python"); // ChangePass V.2 Fahri
$trmap["001000"] = Array("./Request/Setting/001000.xml", "python"); // Get PIN/LOGIN Timeout
$trmap["001001"] = Array("./Request/Setting/001001.xml", "python"); // Set PIN/LOGIN Timeout
$trmap["080108"] = Array("./Request/Setting/080108.xml", ""); // Get GTC Max Day
$trmap["080106"] = Array("./Request/Setting/080106.xml", ""); // Get Repeat Order Count
$trmap["080107"] = Array("./Request/Setting/080107.xml", ""); // Get Split Order Count
$trmap["080110"] = Array("./Request/Setting/080110.xml", ""); // Get Qty or Qty Repeat
$trmap["080112"] = Array("./Request/Setting/080112.xml", ""); // Get Qty Split



$trmap["080011"] = Array("./Request/Setting/080011.xml", "python"); //new pin ijan python
$trmap["080116"] = Array("./Request/Setting/080116.xml", "python"); // Order Settings
$trmap["080901"] = Array("./Request/Setting/080901.xml", "python");    //cek user status by Toni
$trmap["830000"] = Array("./Request/Setting/830000.xml", "python"); //chgPasswordd mobilee
$trmap["830001"] = Array("./Request/Setting/830001.xml", "python"); //chgPinn mobilee
$trmap["900000"] = Array("./Request/Setting/900000.xml", "python"); //API for Bot
// Stock
$trmap["141000"] = Array("./Request/Stock/Search/141000.xml", "");    //breaking hi / low
$trmap["141500"] = Array("./Request/Stock/Search/141500.xml", "");    //ichimoku

$trmap["115000"] = Array("./Request/Quote/115000.xml", "python"); // Fundamental and Ratio
$trmap["723400"] = Array("./Request/StockPick/723400.xml", "python"); // StockPick
$trmap["723200"] = Array("./Request/StockPick/723200.xml", "python"); // StockPick
$trmap["192000"] = Array("./Request/News/192000.xml", "python"); // News List
$trmap["192001"] = Array("./Request/News/192001.xml", "python"); // News Detail
$trmap["192100"] = Array("./Request/News/192100.xml", "python"); // Check Announcement
$trmap["192400"] = Array("./Request/News/192400.xml", "python"); // Check Announcement
$trmap["192401"] = Array("./Request/News/192401.xml", "python"); // Read Announcement
$trmap["192402"] = Array("./Request/News/192400.xml", "python"); // Check Personal Announcement
$trmap["192101"] = Array("./Request/News/192101.xml", "python"); // Show Announcement
$trmap["192103"] = Array("./Request/News/192103.xml", "python"); // Show/Read Personal Announcement


//
// for new API
//
$trmap["000101"]["ssval"] = Array( "id" => "_S_loginId");
$trmap["000110"]["ssval"] = Array( "loginId" => "_S_loginId");
$trmap["000217"]["ssval"] = Array( "userId" => "_S_loginId", "clientId" => "_S_clientId");

$trmap["082700"]["ssval"] = Array( "userID" => "_S_clientId", "pinCheck" => True);
$trmap["101200"]["ssval"] = Array( "id" => "_S_loginId");
$trmap["101201"]["ssval"] = Array( "id" => "_S_loginId");
$trmap["101204"]["ssval"] = Array( "id" => "_S_loginId");
$trmap["101211"]["ssval"] = Array( "id" => "_S_loginId");
$trmap["101221"]["ssval"] = Array( "id" => "_S_loginId");
$trmap["101231"]["ssval"] = Array( "id" => "_S_loginId");
$trmap["101232"]["ssval"] = Array( "id" => "_S_loginId");
$trmap["101233"]["ssval"] = Array( "id" => "_S_loginId");
$trmap["101235"]["ssval"] = Array( "id" => "_S_loginId", "loginId" => "_S_loginId" );

$trmap["182600"]["ssval"] = Array( "userId" => "_S_clientId", "cliId" => "_S_clientId", "pinCheck" => True );
$trmap["182800"]["ssval"] = Array( "userId" => "_S_clientId", "cliId" => "_S_clientId", "pinCheck" => True);

$trmap["189001"]["ssval"] = Array( "userId" => "_S_clientId" , "pinCheck" => True);
$trmap["189000"]["ssval"] = Array( "userId" => "_S_clientId" , "pinCheck" => True);
$trmap["189100"]["ssval"] = Array( "userId" => "_S_clientId" , "pinCheck" => True);
$trmap["189200"]["ssval"] = Array( "userId" => "_S_clientId" , "pinCheck" => True);
$trmap["189300"]["ssval"] = Array( "userId" => "_S_clientId" , "pinCheck" => True);
$trmap["189090"]["ssval"] = Array( "userId" => "_S_clientId" , "pinCheck" => True);

$trmap["186000"]["ssval"] = Array( "userId" => "_S_clientId" , "pinCheck" => True);
$trmap["186001"]["ssval"] = Array( "userId" => "_S_clientId" , "pinCheck" => True);
$trmap["186801"]["ssval"] = Array( "userID" => "_S_clientId" , "pinCheck" => True);

$trmap["187800"]["ssval"] = Array( "userId" => "_S_clientId" , "pinCheck" => True);
$trmap["187801"]["ssval"] = Array( "userId" => "_S_clientId" , "pinCheck" => True);

$trmap["189400"]["ssval"] = Array( "userId" => "_S_clientId" , "pinCheck" => True);

$trmap["184500"]["ssval"] = Array( "userId" => "_S_clientId" , "pinCheck" => True);
$trmap["184501"]["ssval"] = Array( "cliId" => "_S_clientId" , "pinCheck" => True);
$trmap["184520"]["ssval"] = Array( "userId" => "_S_clientId" , "pinCheck" => True);
$trmap["184504"]["ssval"] = Array( "pinCheck" => True);

$trmap["800000"]["ssval"] = Array( "userID" => "_S_clientId", "clientID" => "_S_clientId", "pinCheck" => True);
$trmap["800001"]["ssval"] = Array( "clientID" => "_S_clientId", "pinCheck" => True );
$trmap["800020"]["ssval"] = Array( "clientID" => "_S_clientId", "pinCheck" => True );
$trmap["800011"]["ssval"] = Array( "userId" => "_S_clientId" , "pinCheck" => True );
$trmap["800005"]["ssval"] = Array( "clientID" => "_S_clientId", "pinCheck" => True );
$trmap["802800"]["ssval"] = Array( "loginId" => "_S_loginId", "pinCheck" => True );
$trmap["800300"]["ssval"] = Array( "userId" => "_S_clientId", "pinCheck" => True );
$trmap["800910"]["ssval"] = Array( "loginId" => "_S_loginId" );
$trmap["710010"]["ssval"] = Array( "userId" => "_S_loginId");
?>
