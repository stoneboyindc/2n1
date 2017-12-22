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

function outputOneRowPage3($hashMap, $leftAttr, $midAttr, $rightAttr)
{
  $year = substr($hashMap[$leftAttr],0,4);
  $month = substr($hashMap[$leftAttr],4,2);
  $day = substr($hashMap[$leftAttr],6,2);
  echo '<div class="Rtable-cell20 value border">'.$year.'/'.$month.'/'.$day.'</div>';
  echo '<div class="Rtable-cell60 value border">'.$hashMap[$midAttr].'</div>';
  echo '<div class="Rtable-cell20 value border">'.$hashMap[$rightAttr].'</div>';
}

function outputOneRowTitlePage2($leftAttr, $midAttr, $rightAttr)
{
  echo '<div class="Rtable Rtable--3cols border">';
  echo '<div class="Rtable-cell label border">'.$leftAttr.'</div>';
  echo '<div class="Rtable-cell label border">'.$midAttr.'</div>';
  echo '<div class="Rtable-cell label border">'.$rightAttr.'</div>';
  echo '</div>';
}
/*
function outputOneRowValPage2($hashMap, $leftAttr, $midAttr, $rightAttr)
{
  echo '<div class="Rtable Rtable--3cols border">';
  echo '<div class="Rtable-cell value border">'.$hashMap[$leftAttr].'</div>';
  echo '<div class="Rtable-cell value border">'.$hashMap[$midAttr].'</div>';
  echo '<div class="Rtable-cell value border">'.$hashMap[$rightAttr].'</div>';
  echo '</div>';
}
*/
function outputOneRowValPage2($hashMap, $label, 
    $leftAttr1, $midAttr1, $rightAttr1,
     $leftAttr2, $midAttr2, $rightAttr2,
      $leftAttr3, $midAttr3, $rightAttr3)
{
  echo '<div class="Rtable-cell label border">'.$hashMap[$label].'</div>';
  echo '<div class="Rtable-cell value border">'.$hashMap[$leftAttr1].'</div>';
  echo '<div class="Rtable-cell value border">'.$hashMap[$midAttr1].'</div>';
  echo '<div class="Rtable-cell value border">'.$hashMap[$rightAttr1].'</div>';
  echo '<div class="Rtable-cell value border">'.$hashMap[$leftAttr2].'</div>';
  echo '<div class="Rtable-cell value border">'.$hashMap[$midAttr2].'</div>';
  echo '<div class="Rtable-cell value border">'.$hashMap[$rightAttr2].'</div>';
  echo '<div class="Rtable-cell value border">'.$hashMap[$leftAttr3].'</div>';
  echo '<div class="Rtable-cell value border">'.$hashMap[$midAttr3].'</div>';
  echo '<div class="Rtable-cell value border">'.$hashMap[$rightAttr3].'</div>';
}

function output7ColValPage2($hashMap, $label, 
    $leftAttr1, $rightAttr1,
     $leftAttr2, $rightAttr2,
      $leftAttr3, $rightAttr3)
{
  echo '<div class="Rtable-cell label border">'.$hashMap[$label].'</div>';
  echo '<div class="Rtable-cell value border">'.$hashMap[$leftAttr1].'</div>';
  echo '<div class="Rtable-cell value border">'.$hashMap[$rightAttr1].'</div>';
  echo '<div class="Rtable-cell value border">'.$hashMap[$leftAttr2].'</div>';
  echo '<div class="Rtable-cell value border">'.$hashMap[$rightAttr2].'</div>';
  echo '<div class="Rtable-cell value border">'.$hashMap[$leftAttr3].'</div>';
  echo '<div class="Rtable-cell value border">'.$hashMap[$rightAttr3].'</div>';
}

$style = <<<XYZ
<style type='text/css'>
html, body, article, aside, details, figcaption, figure, header, hgroup, menu, nav, section, header, table, tbody, tfoot, thead, tr, th, td, article, aside, audio, video
{margin:0;height:100%;padding:0;border:0;outline:0;font-size:100%;font:inherit;font-family:'NanumSquare','나눔스퀘어','Nanum Square Light',Oswald,'Nanum Gothic';color:#555;vertical-align:middle;background:transparent;box-sizing:border-box;-o-box-sizing:border-box;-ms-box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box}

.Rtable {
  display: flex;
  flex-wrap: wrap;
  margin: 0 0 3em 0;
  padding: 0;
}
.Rtable-cell, .Rtable-cell10, .Rtable-cell20, .Rtable-cell30, .Rtable-cell60 {
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
.Rtable--3cols > .Rtable-cell20  { width: 20%; }
.Rtable--3cols > .Rtable-cell60  { width: 60%; }
.Rtable--4cols > .Rtable-cell  { width: 25%; }
.Rtable--4cols > .Rtable-cell10  { width: 10%; }
.Rtable--4cols > .Rtable-cell20  { width: 20%; }
.Rtable--4cols > .Rtable-cell30  { width: 30%; }
.Rtable--5cols > .Rtable-cell  { width: 20%; }
.Rtable--6cols > .Rtable-cell  { width: 16.6%; }
.Rtable--7cols > .Rtable-cell  { width: 14.285%; }
.Rtable--10cols > .Rtable-cell  { width: 10%; }

.value {
  color: #55555;
  background-color: #FFFFFF;
}
.valueLarge {
  color: #55555;
  background-color: #FFFFFF;
  font-size: 300%;
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

$headUrl = "http://asp1.krx.co.kr/servlet/krx.asp.XMLSise?code=031980";
$headXml = get_site_html($headUrl);
$xmlstr = stripFirstLine($headXml);
$headResult = new SimpleXMLElement($xmlstr);
$hashMap = array();
foreach($headResult->attributes() as $a => $b) {
  $hashMap[$a]=''.$b.'';
}
buildMap($headResult->TBL_StockInfo->attributes(), $hashMap);
buildMap($headResult->TBL_Hoga->attributes(), $hashMap);
buildMap($headResult->stockInfo->attributes(), $hashMap);

$url = "http://asp1.krx.co.kr/servlet/krx.asp.XMLJemu?code=031980";
$xml = get_site_html($url);
$xmlstr = stripFirstLine($xml);

$result = new SimpleXMLElement($xmlstr);
?>
<?php

$daeCha = array();
buildMap($result->TBL_DaeCha->attributes(), $daeCha);

//print_r($daeCha);

$daeChaDataMap = array();
for ($i = 0; $i < 10; $i++) {
  $daeChaDataMap[$i] = array();
  buildMap($result->TBL_DaeCha->TBL_DaeCha_data[$i]->attributes(), $daeChaDataMap[$i]);
}

//print_r($daeChaDataMap);

$sonIk = array();
buildMap($result->TBL_SonIk->attributes(), $sonIk);

//print_r($sonIk);

$sonIkDataMap = array();
for ($i = 0; $i < 6; $i++) {
  $sonIkDataMap[$i] = array();
  buildMap($result->TBL_SonIk->TBL_SonIk_data[$i]->attributes(), $sonIkDataMap[$i]);
}

//print_r($sonIkDataMap);

$cashFlow = array();
buildMap($result->TBL_CashFlow->attributes(), $cashFlow);

//print_r($cashFlow);

$cashFlowDataMap = array();
for ($i = 0; $i < 6; $i++) {
  $cashFlowDataMap[$i] = array();
  buildMap($result->TBL_CashFlow->TBL_CashFlow_data[$i]->attributes(), $cashFlowDataMap[$i]);
}

//print_r($cashFlowDataMap);

echo '<div class="Rtable Rtable--4cols">';
echo '<div class="Rtable-cell value">현재가</div>';
echo '<div class="Rtable-cell value"></div>';
echo '<div class="Rtable-cell value">KOSPI</div>';
echo '<div class="Rtable-cell value">KOSDAQ</div>';
echo '<div class="Rtable-cell valueLarge">'.$hashMap["CurJuka"].'</div>';
echo '<div class="Rtable-cell value">';
echo '<div style="margin: 0px;" class="Rtable Rtable--2cols">';
echo '<div class="Rtable-cell value">전일대비</div>';
echo '<div class="Rtable-cell value">'.$hashMap["Debi"].'</div>';
echo '<div class="Rtable-cell value">전일종가</div>';
echo '<div class="Rtable-cell value">'.$hashMap["PrevJuka"].'</div>';
echo '<div class="Rtable-cell value">거래량</div>';
echo '<div class="Rtable-cell value">'.$hashMap["Volume"].'</div>';
echo '</div>';
echo '</div>';
echo '<div class="Rtable-cell valueLarge">'.$hashMap["kospiJisu"].'</div>';
echo '<div class="Rtable-cell valueLarge">'.$hashMap["kosdaqJisu"].'</div>';
echo '<div class="Rtable-cell value"></div>';
echo '<div class="Rtable-cell value"></div>';
echo '<div class="Rtable-cell value">'.$hashMap["kospiDebi"].'</div>';
echo '<div class="Rtable-cell value">'.$hashMap["kosdaqJisuDebi"].'</div>';
echo '</div>';

echo '<div style="margin: 0px;" class="Rtable Rtable--2cols">';
echo '<div style="text-align:left" class="Rtable-cell">대차대조표</div>';
echo '<div style="text-align:right" class="Rtable-cell">'.$hashMap["querytime"].'</div>';
echo '</div>';

/*
echo '<div class="Rtable Rtable--4cols border">';
echo '<div class="Rtable-cell10 label border">재무항목</div>';
echo '<div class="Rtable-cell30 label2 border">'.$daeCha["year0"].'/'.$daeCha["month0"].'</div>';
echo '<div class="Rtable-cell30 label border">'.$daeCha["year1"].'/'.$daeCha["month1"].'</div>';
echo '<div class="Rtable-cell30 label2 border">'.$daeCha["year2"].'/'.$daeCha["month2"].'</div>';
echo '<div class="Rtable-cell10 label border">재무항목</div>';
echo '<div class="Rtable-cell30 label2 border">';
outputOneRowTitlePage2("금액","구성비","증감율");
echo '</div>';
echo '<div class="Rtable-cell30 label border">';
outputOneRowTitlePage2("금액","구성비","증감율");
echo '</div>';
echo '<div class="Rtable-cell30 label2 border">';
outputOneRowTitlePage2("금액","구성비","증감율");
echo '</div>';
*/

echo '<div class="Rtable Rtable--10cols border">';
for ($i = 0; $i < 10; $i++) {
  outputOneRowValPage2($daeChaDataMap[$i], "hangMok".$i, 
    "year1Money".$i, "year1GuSungRate".$i, "year1JungGamRate".$i,
    "year1Money".$i, "year2GuSungRate".$i, "year2JungGamRate".$i,
    "year3Money".$i, "year3GuSungRate".$i, "year3JungGamRate".$i );
}
echo '</div>';

echo '<div class="Rtable Rtable--10cols border">';
for ($i = 0; $i < 6; $i++) {
  outputOneRowValPage2($sonIkDataMap[$i], "hangMok".$i, 
    "year1Money".$i, "year1GuSungRate".$i, "year1JungGamRate".$i,
    "year1Money".$i, "year2GuSungRate".$i, "year2JungGamRate".$i,
    "year3Money".$i, "year3GuSungRate".$i, "year3JungGamRate".$i );
}
echo '</div>';

echo '<div class="Rtable Rtable--7cols border">';
for ($i = 0; $i < 6; $i++) {
  output7ColValPage2($cashFlowDataMap[$i], "hangMok".$i, 
    "year1Money".$i,  "year1JungGamRate".$i,
    "year1Money".$i,  "year2JungGamRate".$i,
    "year3Money".$i,  "year3JungGamRate".$i );
}
echo '</div>';

/*
outputOneRowPage3($volMap[0],'distime','disTitle','submitOblgNm');
outputOneRowPage3($volMap[1],'distime','disTitle','submitOblgNm');
outputOneRowPage3($volMap[2],'distime','disTitle','submitOblgNm');
outputOneRowPage3($volMap[3],'distime','disTitle','submitOblgNm');
outputOneRowPage3($volMap[4],'distime','disTitle','submitOblgNm');
outputOneRowPage3($volMap[5],'distime','disTitle','submitOblgNm');
outputOneRowPage3($volMap[6],'distime','disTitle','submitOblgNm');
outputOneRowPage3($volMap[7],'distime','disTitle','submitOblgNm');
outputOneRowPage3($volMap[8],'distime','disTitle','submitOblgNm');
outputOneRowPage3($volMap[9],'distime','disTitle','submitOblgNm');
echo '</div>';
*/

?>