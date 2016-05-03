<pre>
    This page is used to help us test and diagnose TLS 1.2 handshake issues
    TLS 1.2 Test
    PHP Version: <?php echo phpversion(); ?>

    cURL Version Version: <?php echo curl_version()['version'];$_SERVER ?>

    SSL Library: <?php echo curl_version()['ssl_version'];
    $url = "https://api2.heartlandportico.com/Hps.Exchange.PosGateway/PosGatewayService.asmx";

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
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_PROTOCOLS, CURLPROTO_HTTPS);

    $curlOut = curl_exec($ch);
    $curl_getinfo = curl_getinfo($ch);
    $curl_errno = curl_errno($ch);
    curl_close($ch);
    ?>

    Please send this URL to our Support Team <a href="mailto:DeveloperPortal@e-hps.com">email DeveloperPortal@e-hps.com</a>
    <?php echo 'http' . (array_key_exists('HTTPS', $_SERVER) && isset($_SERVER['HTTPS']) ? 's://' : '://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>

    REQUEST DATA This is a connection log sent to: <?php echo $url; ?>

    CURLOPT_VERBOSE This is a connection log
    <textarea cols="100" rows="25"><?php echo readStream($verbose) ?></textarea>

    curl_getinfo Information about the response
    <textarea cols="100" rows="25"><?php echo print_r($curl_getinfo) ?></textarea>

    curl_errno if this is not "0" please consult: https://curl.haxx.se/docs/ssl-compared.html
    <textarea cols="100" rows="1"><?php echo $curl_errno ?></textarea>
