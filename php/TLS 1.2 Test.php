<pre>
    This page is used to help us test and diagnose TLS 1.2 handshake issues
    TLS 1.2 Test
    PHP Version: <?php echo phpversion(); ?>

    cURL Version Version: <?php echo curl_version()['version']; ?>

    SSL Library: <?php echo curl_version()['ssl_version']; ?>

    <form autocomplete="off" method="post" action="#" enctype="application/x-www-form-urlencoded">Enter your private Key: <input name="api"><input type="submit"> </form>
    <?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['api'] ) {
    $api = $_POST['api'];
}else{
    $api = 'skapi_cert_MYl2AQAowiQAbLp5JesGKh7QFkcizOP2jcX9BrEMqQ';
}
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
                                            <hps:CardNbr>448144077201692</hps:CardNbr>
                                            <hps:ExpMonth>02</hps:ExpMonth>
                                            <hps:ExpYear>2025</hps:ExpYear>
                                        </hps:ManualEntry>
                                        <hps:TokenRequest>Y</hps:TokenRequest>
                                    </hps:CardData>
                                    <hps:Amt>0.01</hps:Amt>
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

    /**
     * Created by PhpStorm.
     * User: charles.simmons
     * Date: 4/18/2016
     * Time: 4:09 PM
     */
    function tempStream(){
        static $tempCounter = 0;
        return fopen('php://temp' . $tempCounter++, 'w+');
    }
    function readStream($stream){
        rewind($stream);
        return stream_get_contents($stream);
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_VERBOSE, true);
    $verbose = tempStream();
    curl_setopt($ch, CURLOPT_STDERR, $verbose);
    curl_setopt($ch, CURLOPT_URL, "https://" . ( preg_match('/cert/', $api) === 1 ? 'cert.' : ''  ) . "api2.heartlandportico.com/Hps.Exchange.PosGateway/PosGatewayService.asmx"); //Url together with parameters
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 100);
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
    //file_put_contents('req.xml',$dom->saveXml());
    $dom = new DOMDocument;
    $dom->preserveWhiteSpace = FALSE;
    $dom->loadXML($curlOut);
    $dom->formatOutput = TRUE;
    //file_put_contents('resp.xml',$dom->saveXml());
    $curlOut =  $dom->saveXml();

    $curl_getinfo = curl_getinfo($ch);

    $curl_errno = curl_errno($ch);

    curl_close($ch);
    ?>

    Below is information you should send to our Support Team

    REQUEST DATA This is a connection log sent to: https://cert.api2.heartlandportico.com/Hps.Exchange.PosGateway/PosGatewayService.asmx
    <textarea cols="100" rows="25"><?php echo $data ?></textarea>

    CURLOPT_VERBOSE This is a connection log
    <textarea cols="100" rows="25"><?php echo readStream($verbose) ?></textarea>

    curl_exec Is this the expected content from the server?
    <textarea cols="100" rows="25"><?php echo $curlOut ?></textarea>

    curl_getinfo Information about the response
    <textarea cols="100" rows="25"><?php echo print_r($curl_getinfo) ?></textarea>

    curl_errno if this is not "0" please consult: https://curl.haxx.se/docs/ssl-compared.html
    <textarea cols="100" rows="1"><?php echo $curl_errno ?></textarea>
