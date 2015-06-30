<?php

function println($text, $cli)
{
    print $text . ($cli ? "\n" : '<br />');
}

$cli = php_sapi_name() == 'cli';
$php = phpversion();
$tls = explode(' ', OPENSSL_VERSION_TEXT);
$com = version_compare($tls[1], '1.0.1', '>=');

println('PHP: ' . $php, $cli);
println('OpenSSL: ' . $tls[1], $cli);
println('PCI 3.1 compatible: ' . ($com ? 'YES' : 'NO'), $cli);

if (!$com) {
    println('', $cli);
    println('Please contact EntApp_DevPortal@e-hps.com for', $cli);
    println('tips on becoming PCI 3.1 compatible.', $cli);
}
