<pre>
    This page is used to help us test and diagnose TLS 1.2 handshake issues
    TLS 1.2 Test
    PHP Version: <?php echo phpversion(); ?>

    cURL Version Version: <?php echo curl_version()['version']; ?>

    SSL Library: <?php echo curl_version()['ssl_version']; ?>

    <?php
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
    curl_setopt($ch, CURLOPT_URL, "https://api2.heartlandportico.com/Hps.Exchange.PosGateway/PosGatewayService.asmx"); //Url together with parameters
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 100);
    curl_setopt($ch, CURLOPT_TIMEOUT, 100);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_PROTOCOLS, CURLPROTO_HTTPS);

    $curlOut = tempStream();
    fwrite($curlOut,curl_exec($ch));

    $curl_getinfo = tempStream();
    fwrite($curl_getinfo,print_r(curl_getinfo($ch),true));

    $curl_errno = tempStream();
    fwrite($curl_errno,curl_errno($ch));

    curl_close($ch);
    ?>

Below is information you should send to our Support Team

    CURLOPT_VERBOSE This is a connection log
    <textarea cols="100" rows="25"><?php echo readStream($verbose) ?></textarea>

    curl_exec Is this the expected content from the server?
    <textarea cols="100" rows="25"><?php echo readStream($curlOut) ?></textarea>

    curl_getinfo Information about the response
    <textarea cols="100" rows="25"><?php echo readStream($curl_getinfo) ?></textarea>

    curl_errno if this is not "0" please consult: https://curl.haxx.se/docs/ssl-compared.html
    <textarea cols="100" rows="1"><?php echo readStream($curl_errno) ?></textarea>
