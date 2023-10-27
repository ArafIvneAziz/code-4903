<?php
$timeout = 10000;

$url = 'https://www.tradingview.com/chart/?symbol=OANDA:EURUSD';

$append = <<<EOL
    
EOL;

$ch = curl_init($url);

curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$page_content = curl_exec($ch);

if ($page_content === false) {
    die('Curl error: ' . curl_error($ch));
}

$page_content = str_replace("https://s3.amazonaws.com/tradingview-currencies/conversions_en.json", "/build/static/js/conversions_en.json", $page_content);
$page_content = str_replace('window.initData.theme = "light"', 'window.initData.theme = "dark"', $page_content);
$page_content = str_replace('initData.defInterval = ""', 'initData.defInterval = "1"', $page_content);
$page_content = str_replace('snowplow-pixel.tradingview.com', $_SERVER['SERVER_NAME'] . '/market', $page_content);
$page_content = str_replace('window.WS_HOST_PING_REQUIRED = true', "window.WS_HOST_PING_REQUIRED = false", $page_content);
$page_content = str_replace('wss://pushstream.tradingview.com', '', $page_content);
$page_content = str_replace('window.GOOGLE_CLIENT_ID = "236720109952-v7ud8uaov0nb49fk5qm03as8o7dmsb30.apps.googleusercontent.com";', '', $page_content);
$page_content = str_replace('https://accounts.google.com/gsi/client', '', $page_content);
$page_content = str_replace('https://accounts.google.com/gsi/style', '', $page_content);
$page_content = str_replace('tv-dlive.tradingview.com', '', $page_content);
$page_content = str_replace('window.urlParams = window.initData.querySettings;', 'window.urlParams = window.initData.querySettings;alert((window.urlParams).join("\\n"))', $page_content);

curl_close($ch);
echo $page_content . $append;
?>