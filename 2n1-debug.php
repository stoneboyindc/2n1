<?php

function get_site_html($site_url) 
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_COOKIESESSION, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 4);
    curl_setopt($ch, CURLOPT_FORBID_REUSE, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_URL, $site_url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    global $base_url; 
    $base_url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
    $http_response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close ($ch);
    return $response;
}

function stripFirstLine($text)
{        
  return substr( $text, strpos($text, "\n")+1 );
}

$html = "";
$url = "http://asp1.krx.co.kr/servlet/krx.asp.XMLSiseEng?code=031980";
//$xml = simplexml_load_file($url);
$xml = get_site_html($url);

$xmlstr = stripFirstLine($xml);


$result = new SimpleXMLElement($xmlstr);
print_r($result);
/*
foreach($result->DailyStock[0]->attributes() as $a => $b) {
    echo $a,'="',$b,"\"\n";
}
*/

//echo $xml;
/*
for($i = 0; $i < 5; $i++){

    $image = $xml->channel->item[$i]->children('media', True)->content->attributes();
    $title = $xml->channel->item[$i]->title;
    $link = $xml->channel->item[$i]->link;
    $description = $xml->channel->item[$i]->description;
    $pubDate = $xml->channel->item[$i]->pubDate;

    $html .= "<img src='$image' alt='$title'>";
    $html .= "<a href='$link'><h3>$title</h3></a>";
    $html .= "$description";
    $html .= "<br />$pubDate<hr />";
}
*/
echo $html;
?>