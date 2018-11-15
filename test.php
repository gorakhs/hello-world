<?php
$link = mysqli_connect("localhost", "root", "password");
if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

echo "Success: A proper connection to MySQL was made! The my_db database is great." . PHP_EOL;
echo "Host information: " . mysqli_get_host_info($link) . PHP_EOL;

mysqli_close($link);
exit;

$json = (string) '[{"Banner":"CTR","Store":"0399","SKU":"4083191","CheckDigit":"8","Product":"4083191P","Price":304.99,"Quantity":0,"Promo":{"Price":287.49,"EndDate":"2018-11-08","Origin":"1"},"SaveStory":"Great Buy","Description":"LT235\/80R17120\/117RP","Messages":{"Warranty":"Passenger and light truck tires purchased, installed and balanced at a Canadian Tire Associate Store are covered by  a pro-rated Road Hazard Damage and Manufacturing Defects warranty for the life of the useable tread* or five years from the date of purchase, whichever comes first. The original work order\/invoice must be presented in order for this warranty to be honoured. Tires purchased, installed and balanced at Canadian Tire also come with new rubber valve stems**, rotation every 10,000 km and free flat repairs.  See store staff for complete warranty details. <BR>*Useable tread is the original tread worn down to the level of the tread wear indicators, which is 2\/32\\\" of tread remaining. <BR>**Additional charges may apply for vehicles equipped with Tire Pressure Monitoring Systems (TPMS)."},"PartNumber":"48686","Rebate":{"pdfLink":"http:\/\/www.canadiantire.ca\/en\/automotive\/tires-wheels\/mail-in-rebates.html","Value":0E+0,"StartDate":"2018-10-12","EndDate":"2018-12-21","Message":"Promo Price includes instant rebate. Cannot be used in conjunction with mail-in rebate","PriceAfterRebate":287.49,"QuantityPer":4E+0},"IsOnline":{"Active":"Y","Exclusive":"N","Sellable":"Y","Orderable":"N","StoreClearance":"N"},"Corporate":{"Quantity":0,"MinOrderQty":1,"MinETA":1,"MaxETA":5},"PriceFrom":"N"}]';
echo $json;
 $prddatamain_arr = json_decode($json);
                $prddatamain = $prddatamain_arr[0];
                print_r($prddatamain);
exit;
$r = 32768008;
//$r = 1310721;
echo $packaging = $r & 0xFFFF; echo '<br>';
	echo $box = ($r >> 16) & 0xFFFF;echo '<br>';
echo $r & 0xFFFF;

$ScheduledTime = '2018-08-10';
$current_date = '2018-08-12';
$datetime1 = date_create($ScheduledTime);
$datetime2 = date_create($current_date);
$interval = $datetime1->diff($datetime2);
$diff = $interval->format('%a days');
if ($diff > 1 && strtotime($ScheduledTime) < strtotime($current_date)) {
                              echo $diff;
                            }


echo '&#39;';
//echo date('Y-m-d H:i:s', strtotime('2018-04-27T08:10:25'));

//2018-05-26T13:00:00-04:00

date_default_timezone_set('Asia/Kolkata');

if (date_default_timezone_get()) {
    echo 'date_default_timezone_set: ' . date_default_timezone_get() . '<br />';
}
echo '<br><br>';
if (ini_get('date.timezone')) {
    echo 'date.timezone: ' . ini_get('date.timezone');
}echo '<br><br>';
echo date('Y-m-d H:i:s', strtotime('2018-06-06T01:00:00-04:00'));
echo '<br><br>';
echo date('Y-m-d H:i:s', strtotime('2018-06-06T01:00:00'));
echo '<br><br>';
echo date('Y-m-d H:i:s', strtotime('2018-06-06T01:00:00UTC'));
echo '<br><br>';
echo date('Y-m-d H:i:s', strtotime('2018-06-06T01:00:00-04:00'));

