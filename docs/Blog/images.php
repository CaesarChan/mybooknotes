<?php

/**
 * get请求
 * @param $url,请求地址
 * @return bool|string
 */
function getRequest($url){
    $headerArray =array("Content-type:application/json;","Accept:application/json");
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch,CURLOPT_HTTPHEADER,$headerArray);
    $output = curl_exec($ch);
    curl_close($ch);
    //将返回的json对象解码成数组对象并返回
    //$output = json_decode($output,true);
    return $output;
}

for($i=0;$i<100;$i++){


$link = getRequest("https://api.strikefreedom.top/image/random");

// $a = new SimpleXMLElement($link);
preg_match_all('/<a[^>]+href=([\'"])(?<href>.+?)\1[^>]*>/i', $link, $result);

if (!empty($result)) {
    # Found a link.
    $url = $result['href'][0];
    echo $url.PHP_EOL;

    // $url = "https://res.strikefreedom.top/static_res/blog/background/wallhaven-xlpyxv.jpg";
    //https://cdn.jsdelivr.net/gh/mashirozx/sakura@3.3.3
    $items = parse_url($url);
    $imgDir = __DIR__.$items['path'];

    copy($url,$imgDir);
}
}
