PHP Version: <?php echo phpversion(); ?><br /><br />

cURL Version Version: <?php echo curl_version()['version']; ?><br /><br />

SSL Library: <?php echo curl_version()['ssl_version']; ?><br /><br />

<form autocomplete="off" method="get" action="#" enctype="application/x-www-form-urlencoded">Enter your private Key: <input name="api"><br />Enter a TransactionID: <input name="t"><br /><input type="submit"> </form><?php // Trans Lookup
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset( $_GET['api']))
{
$api = (string) $_GET['api'];
$trID = (int) $_GET['t']; // '1773753952';

$data = <<<EOD
<?xml version="1.0" encoding="utf-8"?>
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:hps="http://Hps.Exchange.PosGateway">
	<soapenv:Header/>
	<soapenv:Body>
		<hps:PosRequest clientType="" clientVer="">
			<hps:Ver1.0>
				<hps:Header>
					<hps:SecretAPIKey>$api</hps:SecretAPIKey>
					<hps:DeveloperID>000000</hps:DeveloperID>
					<hps:VersionNbr>0000</hps:VersionNbr>
					<hps:SiteTrace/>
				</hps:Header>
				<hps:Transaction>
					<hps:ReportTxnDetail>
						<hps:TxnId>$trID</hps:TxnId>
					</hps:ReportTxnDetail>
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
if ($api){
            $request = curl_init();
            curl_setopt($request, CURLOPT_URL, "https://" . ( preg_match('/cert/', $api) === 1 ? 'cert.' : ''  ) . "api2.heartlandportico.com/Hps.Exchange.PosGateway/PosGatewayService.asmx");
            curl_setopt($request, CURLOPT_CONNECTTIMEOUT, 100);
            curl_setopt($request, CURLOPT_TIMEOUT, 100);
            curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($request, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($request, CURLOPT_SSL_VERIFYHOST, false);
            if ($data != null) {
                curl_setopt($request, CURLOPT_CUSTOMREQUEST, 'POST');
                curl_setopt($request, CURLOPT_POSTFIELDS, $data);
            }
            curl_setopt($request, CURLOPT_HTTPHEADER, $header);
            curl_setopt($request, CURLOPT_PROTOCOLS, CURLPROTO_HTTPS);

            $curlResponse = curl_exec($request);
            file_put_contents('resp.xml',$curlResponse);
$dom = new DOMDocument;
$dom->preserveWhiteSpace = FALSE;
$dom->loadXML($curlResponse);
$dom->formatOutput = TRUE;
exit ('<textarea cols="200" rows="80">' . $dom->saveXml() . '</textarea>');
}}


?>No Data
