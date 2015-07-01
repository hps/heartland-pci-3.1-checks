# Heartland PCI 3.1: PHP Check

PCI 3.1 requires the use of TLS 1.1/1.2 when processing payments.

## Usage

If you have SSH or direct command line access on your webhost,
upload the `heartland-pci-check.php` script to your server,
and run it with:

```
$ php heartland-pci-check.php
```

If you only have FTP or file manager access on your webhost,
upload the `heartland-pci-check.php` script with that method
to a web accessible directory (`public_html`, `www`, etc.),
and access the file with your browser:

```
http://[your website's domain or IP here]/heartland-pci-check.php
```

## Information

When run, the `heartland-pci-check.php` script will provide your
current PHP version, the version of OpenSSL available to the
PHP runtime, and a simple `YES`/`NO` notice of PCI 3.1 compatibility.

PHP uses curl and OpenSSL for connecting to Heartland's servers. TLS
1.1/1.2 support was not added to OpenSSL until version branch 1.0.1,
so we are comparing your server's version of OpenSSL against that
version.
