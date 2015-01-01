<html>
<head>
<meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>Tippr</title>
	<!-- Latest compiled and minified CSS -->
	<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css'>
	
	<!-- Custom styles for this template -->
        <link href='jumbotron-narrow.css' rel='stylesheet'>

	<!-- Optional theme -->
	<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css'>

	<!-- Latest compiled and minified JavaScript -->
	<script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js'></script>

	<script src="qrcode-generator/js/qrcode.js"></script>
	<script type="text/javascript">
		var updateQr = function(text) {
			var qr = qrcode(3, 'M');
			qr.addData(text);
			qr.make();

			var cellSize = 4;

			document.getElementById('qr').innerHTML = qr.createImgTag(cellSize);
		};
	</script>
	
	</head>
<?php
  $domain=$_GET['domain'];
  $username="redacted";
  $password="redacted";	
  $contents = file_get_contents("https://www.whoisxmlapi.com//whoisserver/WhoisService?domainName=$domain&username=$username&password=$password&outputFormat=JSON");
  //echo $contents;
  $res=json_decode($contents);
  if($res){
  	if($res->ErrorMessage){
  		echo $res->ErrorMessage->msg;
  	}	
  	else{
  		$whoisRecord = $res->WhoisRecord;
  		if($whoisRecord){
    		$email = print_r($whoisRecord->registrant->email,1);
  		}
  	}
  }
?>
<?php 
$JSONResponse = file_get_contents("https://blockchain.info/api/v2/create_wallet?api_code=redacted&password=changeyourpassword&email=$email");
$arr = json_decode($JSONResponse, true);
$address = $arr['address'];
$link = $arr['link'];
$File = "wallets.txt"; 
$Handle = fopen($File, 'a+');
$Data = "\n$link"; 
fwrite($Handle, $Data);  
fclose($Handle); 
$notice_text = "This is a multi-part message in MIME format.";
$plain_text = "You've been tipped! Someone was visiting your website and they really liked it! Well, enough to tip you at least. What is Bitcoin? 'Bitcoin is a digital currency used to pay for a variety of goods and services. In many ways, it works the same as paper money with some key differences. Although physical forms of Bitcoin exist, the currency's primary form is data so you trade it online, peer to peer, using wallet software or an online service. You can obtain Bitcoin's either by trading other money, goods, or services with people who have them or through mining. The mining process involves running software that performs complex mathematical equations for which you're rewarded a very small portion of a Bitcoin. When you actually have some of the currency, you can then use it to purchase anything that accepts it.' -Adam Dachis, Lifehacker Now that you know what Bitcoin is, go ahead and claim your tip! Your default password is changeyourpassword, however we suggest you create a new password as soon as you log in. $link P.S. - If you'd like to learn more about Bitcoin, bitcoin.org is a great resource. You should also check meetup.com to see if there are any Bitcoin meetups near you!";
$html_text = "<html>
  <head>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>Tippr</title>
	<!-- Latest compiled and minified CSS -->
	<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css'>
	
	<!-- Custom styles for this template -->
        <link href='https://tippr.org/app/jumbotron-narrow.css' rel='stylesheet'>

	<!-- Optional theme -->
	<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css'>

	<!-- Latest compiled and minified JavaScript -->
	<script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js'></script>
  </head>
  <body>

    <div class='container'>
      <div class='header'>
        <nav>
          <ul class='nav nav-pills pull-right'>
          </ul>
        </nav>
      </div>

      <div class='jumbotron'>
        <h1>You've been tipped!</h1><br />
        <p class='lead'>Someone was visiting your website and they really liked it!</p><br />
        <h2>What is Bitcoin?</h2><br /><p class='lead'>'Bitcoin is a digital currency used to pay for a variety of goods and services. In many ways, it works the same as paper money with some key differences. Although physical forms of Bitcoin exist, the currency's primary form is data so you trade it online, peer to peer, using wallet software or an online service. You can obtain Bitcoin's either by trading other money, goods, or services with people who have them or through mining. The mining process involves running software that performs complex mathematical equations for which you're rewarded a very small portion of a Bitcoin. When you actually have some of the currency, you can then use it to purchase anything that accepts it.'<br />-<a href='http://lifehacker.com/5991523/what-is-bitcoin-and-what-can-i-do-with-it'><i>Adam Dachis, Lifehacker</i></a></p><br />
        <p>Now that you know what Bitcoin is, go ahead and claim your tip! Your default password is <b>changeyourpassword</b>, however we suggest you create a new password as soon as you log in. <b>If you don't change your password in a week, we'll assume you don't want the tip, and it will be returned to the sender.</b></p><br />
        <p><a class='btn btn-lg btn-success' href='$link' role='button'>Claim your tip</a></p><br />
        <p>P.S. - If you'd like to learn more about Bitcoin, <a href='http://bitcoin.org'>bitcoin.org</a> is a great resource. You should also check <a href='http://meetup.com'>meetup.com</a> to see if there are any Bitcoin meetups near you!</p>
      </div>


      <footer class='footer'>
        <center><p>&copy; Tippr 2014</p></center>
      </footer>

    </div> <!-- /container -->
  </body>
</html>";

$semi_rand = md5(time());
$mime_boundary = "==MULTIPART_BOUNDARY_$semi_rand";
$mime_boundary_header = chr(34) . $mime_boundary . chr(34);

$to = $email;
$from = "Tippr <welcome@tippr.org>";
$subject = "You've been tipped!";

$body = "$notice_text

--$mime_boundary
Content-Type: text/plain; charset=us-ascii
Content-Transfer-Encoding: 7bit

$plain_text

--$mime_boundary
Content-Type: text/html; charset=us-ascii
Content-Transfer-Encoding: 7bit

$html_text

--$mime_boundary--";

if (@mail($to, $subject, $body,
    "From: " . $from . "\n" .
    "MIME-Version: 1.0\n" .
    "Content-Type: multipart/alternative;\n" .
    "     boundary=" . $mime_boundary_header))
    echo " ";
else
    echo " ";
?>
<body onLoad="updateQr('bitcoin:<?php echo $address; ?>');">
<?php
if($email == ""){
	echo "<center><b>There is no email associated with this domain in our WHOIS database.</b></center><br />";
}
else {
?>
<center>Send Bitcoins to:<br />
<div id="qr"></div>(<?php echo $address; ?>)</center>
<?php
}
?>
<center><br />If the tip is not collected within a week, it will be returned to you.<br />Powered by <a href="https://tippr.org">Tippr</a>.</center>
