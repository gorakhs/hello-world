<?php

header("Content-type: text/html; charset=utf-8");



function call_rest_service($service_url, $json_data, $method) {

        $content_length = isset($json_data) ? strlen($json_data) : 0;
        $headers = array(
            'Content-Type: application/json',
            'Content-Length: ' . $content_length,
            'Accept: application/json',
        );
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json_data);
        curl_setopt($curl, CURLOPT_URL, $service_url);
        curl_setopt($curl, CURLOPT_VERBOSE, 0);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 15);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);

        /*
        if (CURL_PROXY_REQUIRED == 'True') {
            $proxy_tunnel_flag = (defined('CURL_PROXY_TUNNEL_FLAG') && strtoupper(CURL_PROXY_TUNNEL_FLAG) == 'FALSE') ? false : true;
            curl_setopt($curl, CURLOPT_HTTPPROXYTUNNEL, $proxy_tunnel_flag);
            curl_setopt($curl, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
            curl_setopt($curl, CURLOPT_PROXY, CURL_PROXY_SERVER_DETAILS);
        }*/
        
        //curl_setopt($curl, CURLOPT_PROXY,  'elephants.elehost.com:8119' );


        $result = curl_exec($curl);
        
        if (curl_error($curl)) {
            $error = curl_error($curl);
            echo '<br>Curl error:' . $error . ". Call url: $service_url";
        }
        curl_close($curl);
        
        return $result;
    }
    
function sendRequest($data, $url)
{
    $resp_data = array ();
    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, $url );
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-length:'.strlen($data)));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    $resp = curl_exec ( $ch );
    $resp_data = urldecode( $resp );
    curl_close($ch);
    return $resp_data;
};

//echo 'Processing...<br>';
$data_array = array();
$data_array['amount'] = '1';
$data_array['biz_type'] = 'WECHATPAY';
$data_array['order_id'] = "TEST".date("YmdHis"); //'TEST20171130175453';
$data_array['call_back_url'] = "http://dev.optimalmrm.com/ottpay_demo_callback.php?method=payment_ottpay";
$temp_data_array = $data_array;
ksort($temp_data_array);
$data_str = implode(array_values($temp_data_array));
$data_md5 = strtoupper(md5($data_str));
$user_key = 'EA6BB31FE672050A'; //using your Sign Key provided by OTTPAY;
$aesKeyStr = strtoupper(substr(md5($data_md5.$user_key),8,16));
$data_json = json_encode($data_array);

$encrypted_data = Security::encrypt($data_json, $aesKeyStr);





$params_array = array();
$params_array['action'] = 'ACTIVEPAY';
$params_array['version'] = '1.0';
$params_array['merchant_id'] = 'ON00000001';//using your Merchant ID provided by OTTPAY;
$params_array['data'] = $encrypted_data;
$params_array['md5'] = $data_md5;
$params_json = json_encode($params_array, JSON_UNESCAPED_UNICODE);
//test url - http://uatapi.ottpay.com:8081/process
//$resp_data = sendRequest($params_json, 'https://frontapi.ottpay.com:443/process');
//$resp_data = sendRequest($params_json, 'http://uatapi.ottpay.com:8081/process');
$resp_data =call_rest_service('http://uatapi.ottpay.com:8081/process', $params_json, 'POST') ;

$resp_arr = (array) json_decode($resp_data, true);
$aesKeyStr = strtoupper(substr(md5($resp_arr['md5'].$user_key),8,16));
$decrypted_data = Security::decrypt($resp_arr['data'], $aesKeyStr);

$return_data_arr = (array) json_decode($decrypted_data, true);
$qrCode_url = $return_data_arr['code_url'];
//print_r($return_data_arr);
//echo 'response qrCode_url = '.$qrCode_url;

function LoadJpeg($imgname)
{
    /* Attempt to open */
    $im = @imagecreatefromjpeg($imgname);

    /* See if it failed */
    if(!$im)
    {
        /* Create a black image */
        $im  = imagecreatetruecolor(150, 30);
        $bgc = imagecolorallocate($im, 255, 255, 255);
        $tc  = imagecolorallocate($im, 0, 0, 0);

        imagefilledrectangle($im, 0, 0, 150, 30, $bgc);

        /* Output an error message */
        imagestring($im, 1, 5, 5, 'Error loading ' . $imgname, $tc);
    }

    return $im;
}
/*
header('Content-Type: image/jpeg');

$img = LoadJpeg($qrCode_url);

imagejpeg($img);
imagedestroy($img);
*/



class Security
{
    public static function encrypt($input, $key)
    {
        $size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
        $input = Security::pkcs5_pad($input, $size);
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_ECB, '');
        $iv = mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        mcrypt_generic_init($td, $key, $iv);
        $data = mcrypt_generic($td, $input);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        $data = utf8_encode(base64_encode($data));
        return $data;
    }

	
	    private static function pkcs5_pad ($text, $blocksize)
    {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    public static function decrypt($sStr, $sKey)
    {
        $decrypted= mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $sKey, base64_decode(str_replace(" ","+",$sStr)), MCRYPT_MODE_ECB);
        $dec_s = strlen($decrypted);
        $padding = ord($decrypted[$dec_s-1]);
        $decrypted = substr($decrypted, 0, -$padding);
        return $decrypted;
    }
}

class QRGenerator { 
 
    protected $size; 
    protected $data; 
    protected $encoding; 
    protected $errorCorrectionLevel; 
    protected $marginInRows; 
    protected $debug; 
 
    public function __construct($data='http://www.phpgang.com',$size='300',$encoding='UTF-8',$errorCorrectionLevel='L',$marginInRows=4,$debug=false) { 
 $debug = true;
        $this->data=urlencode($data); 
        $this->size=($size>100 && $size<800)? $size : 300; 
        $this->encoding=($encoding == 'Shift_JIS' || $encoding == 'ISO-8859-1' || $encoding == 'UTF-8') ? $encoding : 'UTF-8'; 
        $this->errorCorrectionLevel=($errorCorrectionLevel == 'L' || $errorCorrectionLevel == 'M' || $errorCorrectionLevel == 'Q' || $errorCorrectionLevel == 'H') ?  $errorCorrectionLevel : 'L';
        $this->marginInRows=($marginInRows>0 && $marginInRows<10) ? $marginInRows:4; 
        $this->debug = ($debug==true)? true:false;     
    }
public function generate(){ 
 
        $QRLink = "https://chart.googleapis.com/chart?cht=qr&chs=".$this->size."x".$this->size.                            "&chl=" . $this->data .  
                   "&choe=" . $this->encoding . 
                   "&chld=" . $this->errorCorrectionLevel . "|" . $this->marginInRows; 
        if ($this->debug) echo   $QRLink;          
        return $QRLink; 
    }
}

$ex1 = new QRGenerator($qrCode_url); 
echo "<img src=".$ex1->generate().">";

require __DIR__ . "/autoload.php";
$qrcode = new QrReader($qrCode_url);
$text = $qrcode->text(); //return decoded text from QR Code