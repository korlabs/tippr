<html>
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
<?php
  $domain=$_GET['domain'];
  $username="YOUR-USERNAME-HERE";
  $password="YOUR-PASSWORD-HERE";	
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
$JSONResponse = file_get_contents("https://blockchain.info/api/v2/create_wallet?api_code=API-CODE-HERE&password=changeyourpassword&email=$email");
$arr = json_decode($JSONResponse, true);
$address = $arr['address'];
$link = $arr['link'];
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
        <p>Now that you know what Bitcoin is, go ahead and claim your tip! Your default password is <b>changeyourpassword</b>, however we suggest you create a new password as soon as you log in.</p><br />
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
<center>Send Bitcoins to:<br /><br />
<img src="https://api.qrserver.com/v1/create-qr-code/?size=90x90&data=bitcoin:<?php echo $address; ?>"><br /><br />(<?php echo $address; ?>)<br /><br />Powered by <a href="https://tippr.org">Tippr</a>.</center>
