<pre>
    This page is used to help us test and diagnose TLS 1.2 handshake issues
    TLS 1.2 Test
    PHP Version: <?php echo phpversion(); ?>

    cURL Version Version: <?php echo curl_version()['version'];$_SERVER ?>

    SSL Library: <?php echo curl_version()['ssl_version']; ?>

    <form autocomplete="off" method="post" action="#" enctype="application/x-www-form-urlencoded">Enter your private Key: <input name="api"><input type="submit" value="Test My Key"> </form>
    <?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && trim(filter_var($_POST['api'],FILTER_SANITIZE_STRING))) {
    $api = trim(filter_var($_POST['api'],FILTER_SANITIZE_STRING));
}else{
    die('Please paste in your private API key');
}
    $url = "https://" . ( preg_match('/cert/', $api) === 1 ? 'cert.' : ''  ) . "api2.heartlandportico.com/Hps.Exchange.PosGateway/PosGatewayService.asmx";
$data = <<<EOD
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:hps="http://Hps.Exchange.PosGateway">

            <soapenv:Body>
                <hps:PosRequest clientType="" clientVer="">
                    <hps:Ver1.0>
                        <hps:Header>
                            <hps:SecretAPIKey>$api</hps:SecretAPIKey>
                        </hps:Header>
                        <hps:Transaction>
                            <hps:CreditSale>
                                <hps:Block1>
                                    <hps:CardData>
                                        <hps:ManualEntry>
                                            <hps:CardNbr>5448144077201692</hps:CardNbr>
                                            <hps:ExpMonth>02</hps:ExpMonth>
                                            <hps:ExpYear>2025</hps:ExpYear>
                                        </hps:ManualEntry>
                                        <hps:TokenRequest>Y</hps:TokenRequest>
                                    </hps:CardData>
                                    <hps:Amt>.01</hps:Amt>
                                    <hps:AllowDup>Y</hps:AllowDup>
                                </hps:Block1>
                            </hps:CreditSale>
                        </hps:Transaction>
                    </hps:Ver1.0>
                </hps:PosRequest>
            </soapenv:Body>
        </soapenv:Envelope>
EOD;

$header = array(
    'Content-type: text/xml;charset="utf-8"',
    'Accept: text/xml',
    'SOAPAction: ""',
    'Content-length: '.strlen($data),
);
function tempStream(){
    static $tempCounter = 0;
    return fopen('php://temp' . $tempCounter++, 'w+');
}
function readStream($stream){
    rewind($stream);
    return stream_get_contents($stream);
} //iptables -A INPUT -s 12.130.236.166 -j DROP
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_VERBOSE, true);
    $verbose = tempStream();
    curl_setopt($ch, CURLOPT_STDERR, $verbose);
    curl_setopt($ch, CURLOPT_URL, $url); //Url together with parameters
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1000);
    curl_setopt($ch, CURLOPT_TIMEOUT, 100);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    if ($data != null) {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_PROTOCOLS, CURLPROTO_HTTPS);

    $curlOut = curl_exec($ch);
    $dom = new DOMDocument;
    $dom->preserveWhiteSpace = FALSE;
    $dom->loadXML($data);
    $dom->formatOutput = TRUE;
    $data =  str_replace('544814407720', '************', $dom->saveXml()) ;
    $data =  str_replace($api, substr($api,0,15) . '.......' . substr($api,-5), $data) ;
    $dom = new DOMDocument;
    $dom->preserveWhiteSpace = FALSE;
    $dom->loadXML($curlOut);
    $dom->formatOutput = TRUE;
    $curlOut =  $dom->saveXml();
    $curl_getinfo = curl_getinfo($ch);
    $curl_errno = curl_errno($ch);
    curl_close($ch);
    ?>

    Please send this URL to our Support Team <a href="mailto:DeveloperPortal@e-hps.com">email DeveloperPortal@e-hps.com</a>
    <?php echo 'http' . (array_key_exists('HTTPS', $_SERVER) && isset($_SERVER['HTTPS']) ? 's://' : '://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>

    REQUEST DATA This is a connection log sent to: <?php echo $url; ?>

    <textarea cols="100" rows="25"><?php echo $data ?></textarea>

    CURLOPT_VERBOSE This is a connection log
    <textarea cols="100" rows="25"><?php echo readStream($verbose) ?></textarea>

    curl_exec Is this the expected content from the server?
    <textarea cols="100" rows="25"><?php echo $curlOut ?></textarea>

    curl_getinfo Information about the response
    <textarea cols="100" rows="25"><?php echo print_r($curl_getinfo) ?></textarea>

    curl_errno if this is not "0" please consult: https://curl.haxx.se/docs/ssl-compared.html
    <textarea cols="100" rows="1"><?php echo $curl_errno ?></textarea>
