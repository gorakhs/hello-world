<?php
$qrCode_url = 'https://qr.alipay.com/bax03532k5kw0ubxhhyp00e9';
require "/vendor/autoload.php";
$qrcode = new QrReader($qrCode_url);
$text = $qrcode->text(); //return decoded text from QR Code