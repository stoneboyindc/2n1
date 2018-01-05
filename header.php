<?php
header('Content-type: text/html; charset=UTF-8');

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

function buildMap($attrList, &$map)
{
  foreach($attrList as $a => $b) {
    $map[$a] = ''.$b.'';
  }
}

function getPreviousClosePrice($text)
{        
  $tokStart = '<td class="rgt">';
  $tokEnd = '<td class="rgt rm">';
  $pieces = explode($tokStart, $text);
  $pieces2 = explode($tokEnd, $pieces[4]);
  $val = rtrim( $pieces2[0] );

  return intval(str_replace(",", "", $val));
}

function getArrow($curJuka, $prevJuka  ) {
  if ($curJuka > $prevJuka) {
    $arrow = '<span style="color:#FF2600;">&#x25B2;</span>';
  } elseif ($curJuka < $prevJuka) {
    $arrow = '<span style="color:#0433FF;">&#x25BC;</span>';
  } else {
    $arrow = '';
  }
  return $arrow;
}

function outputOneRowA($hashMap, $leftName, $leftAttr, $rightName, $rightAttr)
{
  echo '<div class="Rtable-cell20 label border">'.$leftName.'</div>';
  echo '<div class="Rtable-cell30 value border">'.$hashMap[$leftAttr].'</div>';
  echo '<div class="Rtable-cell20 label border">'.$rightName.'</div>';
  echo '<div class="Rtable-cell30 value border">'.$hashMap[$rightAttr].'</div>';
}

function outputOneRowB($hashMap, $leftName, $leftAttr, $rightName, $rightAttr)
{
  echo '<div class="Rtable-cell value border">'.$leftName.'</div>';
  echo '<div class="Rtable-cell value border">'.$hashMap[$leftAttr].'</div>';
  echo '<div class="Rtable-cell value border">'.$rightName.'</div>';
  echo '<div class="Rtable-cell value border">'.$hashMap[$rightAttr].'</div>';
}

function outputOneRowC($hashMap, $leftAttr, $midAttr, $rightAttr)
{
  echo '<div class="Rtable-cell value border">'.$hashMap[$leftAttr].'</div>';
  echo '<div class="Rtable-cell value border">'.$hashMap[$midAttr].'</div>';
  echo '<div class="Rtable-cell value border">'.$hashMap[$rightAttr].'</div>';
}
$style = <<<XYZ
<style type='text/css'>
html, body, article, aside, details, figcaption, figure, header, hgroup, menu, nav, section, header, table, tbody, tfoot, thead, tr, th, td, article, aside, audio, video
{margin:0;height:100%;padding:0;border:0;outline:0;font-size:100%;font:inherit;font-family:'NanumSquare','나눔스퀘어','Nanum Square Light',Oswald,'Nanum Gothic';color:#555;vertical-align:middle;background:transparent;box-sizing:border-box;-o-box-sizing:border-box;-ms-box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box}

@media only screen and (max-width: 736px) {

.Rtable {
  display: flex;
  flex-wrap: wrap;
  font-size: 2vw;
  margin: 0 0 3em 0;
  padding: 0;
}
.valueLarge {
  color: #55555;
  background-color: #FFFFFF;
  font-size: 3vw;
  padding-bottom: 5px;
}

}

.Rtable {
  display: flex;
  flex-wrap: wrap;
  margin: 0 0 3em 0;
  padding: 0;
}
.Rtable-cell, .Rtable-cell20, .Rtable-cell30 {
  text-align: center;
  padding-top: 5px;
  padding-bottom: 5px;
  box-sizing: border-box;
  flex-grow: 1;
  width: 100%;  // Default to full width
  padding: 0.8em 1.2em;
  overflow: hidden; // Or flex might break
  list-style: none;
  > h1, > h2, > h3, > h4, > h5, > h6 { margin: 0; }
}
.border {
    border: solid 1px #e2e6e9;
}
.Rtable--2cols > .Rtable-cell  { width: 50%; }
.Rtable--3cols > .Rtable-cell  { width: 33.33%; }
.Rtable--4cols > .Rtable-cell  { width: 25%; }
.Rtable--4cols > .Rtable-cell20  { width: 20%; }
.Rtable--4cols > .Rtable-cell30  { width: 30%; }
.Rtable--5cols > .Rtable-cell  { width: 20%; }
.Rtable--6cols > .Rtable-cell  { width: 16.6%; }

.value {
  color: #55555;
  background-color: #FFFFFF;
}
.valueLarge {
  color: #55555;
  background-color: #FFFFFF;
  font-size: 200%;
  padding-bottom: 5px;
}
.label {
  color: #FFFFFF;
  background-color: #000000;
}
.label2 {
  color: #FFFFFF;
  background-color: #5E5E5E;
}
</style>
XYZ;
echo $style;

$url = "http://asp1.krx.co.kr/servlet/krx.asp.XMLSiseEng?code=031980";
$lang = $_REQUEST['lang'];
if (empty($lang)) {
    
} else {
   $url = "http://asp1.krx.co.kr/servlet/krx.asp.XMLSise?code=031980";
}

$html = "";

//$xml = simplexml_load_file($url);
$xml = get_site_html($url);
// $xmlstr = preg_replace('/^.+\n/', '', $xml);
$xmlstr = stripFirstLine($xml);

$result = new SimpleXMLElement($xmlstr);
//print_r($result[0]);
//echo "<h2>Query Time</h2>";
$hashMap = array();
//buildMap($result->attributes());
foreach($result->attributes() as $a => $b) {
  $hashMap[$a]=''.$b.'';
}

$urlKOSPI = "https://finance.google.com/finance/historical?q=KRX:KOSPI";
$urlKOSDAQ = "http://finance.google.com/finance/historical?q=KOSDAQ:KOSDAQ";
$respBody = get_site_html($urlKOSPI);
$kospi = getPreviousClosePrice($respBody);
$respBody = get_site_html($urlKOSDAQ);
$kosdaq = getPreviousClosePrice($respBody);
/*
foreach($result->attributes() as $a => $b) {
    echo $a,'="',$b,"\"\n";
}
*/
//print_r($result->TBL_DailyStock);
//print_r($result->TBL_DailyStock->DailyStock[0]);
?>
<?php
buildMap($result->TBL_StockInfo->attributes(), $hashMap);
buildMap($result->TBL_Hoga->attributes(), $hashMap);
buildMap($result->stockInfo->attributes(), $hashMap);

$volMap = array();
for ($i = 0; $i < 5; $i++) {
  $volMap[$i] = array();
  buildMap($result->TBL_AskPrice->AskPrice[$i]->attributes(), $volMap[$i]);
}

echo '<div class="Rtable Rtable--4cols">';
echo '<div class="Rtable-cell value">현재가</div>';
echo '<div class="Rtable-cell value"></div>';
echo '<div class="Rtable-cell value">KOSPI</div>';
echo '<div class="Rtable-cell value">KOSDAQ</div>';
echo '<div class="Rtable-cell valueLarge">'.$hashMap["CurJuka"].'</div>';
echo '<div class="Rtable-cell value">';
echo '<div style="margin: 0px;" class="Rtable Rtable--2cols">';
echo '<div class="Rtable-cell value">전일대비</div>';
echo '<div class="Rtable-cell value">'.getArrow($hashMap["CurJuka"],$hashMap["PrevJuka"]).' '.$hashMap["Debi"].'</div>';
echo '<div class="Rtable-cell value">전일종가</div>';
echo '<div class="Rtable-cell value">'.$hashMap["PrevJuka"].'</div>';
echo '<div class="Rtable-cell value">거래량</div>';
echo '<div class="Rtable-cell value">'.$hashMap["Volume"].'</div>';
echo '</div>';
echo '</div>';
echo '<div class="Rtable-cell" style="border-left: 6px solid #FF2600;"><span class="valueLarge">'.$hashMap["kospiJisu"].'</span><br><span class="value">'.getArrow($hashMap["kospiJisu"],$kospi).' '.$hashMap["kospiDebi"].'</div>';
echo '<div class="Rtable-cell"><span class="valueLarge">'.$hashMap["kosdaqJisu"].'</span><br><span class="value">'.getArrow($hashMap["kosdaqJisu"],$kosdaq).' '.$hashMap["kosdaqJisuDebi"].'</div>';
echo '</div>';

?>