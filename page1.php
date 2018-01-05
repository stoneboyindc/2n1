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

echo '<div style="text-align:right" class="Rtable-cell">'.$hashMap["querytime"].'</div>';
echo '<div class="Rtable Rtable--4cols border">';
outputOneRowA($hashMap,'종목명','JongName','현재가','CurJuka');
outputOneRowA($hashMap,'전일대비','Debi','전일종가','PrevJuka');
outputOneRowA($hashMap,'거래량','Volume','거래대금','Money');
outputOneRowA($hashMap,'시가','StartJuka','-','-');
outputOneRowA($hashMap,'고가','HighJuka','저가','LowJuka');
outputOneRowA($hashMap,'52주 최고','High52','52주 최저','Low52');
outputOneRowA($hashMap,'상한가','UpJuka','하한가','DownJuka');
outputOneRowA($hashMap,'PER','Per','-','-');
outputOneRowA($hashMap,'상장주식수','Amount','액면가','FaceJuka');
echo '</div>';

echo '<div class="Rtable Rtable--4cols border">';
echo '<div class="Rtable-cell label border">매도상위</div>';
echo '<div class="Rtable-cell label2 border">거래량</div>';
echo '<div class="Rtable-cell label border">매수상위</div>';
echo '<div class="Rtable-cell label2 border">거래량</div>';

outputOneRowB($volMap[0],'미래에셋대우','member_memdoVol','미래에셋대우','member_mesuoVol');
outputOneRowB($volMap[1],'CS증권','member_memdoVol','키움증권','member_mesuoVol');
outputOneRowB($volMap[2],'키움증권','member_memdoVol','메리츠','member_mesuoVol');
outputOneRowB($volMap[3],'모간서울','member_memdoVol','한국증권','member_mesuoVol');
outputOneRowB($volMap[4],'신한투자','member_memdoVol','메릴린치','member_mesuoVol');
echo '</div>';

echo '<div class="Rtable Rtable--3cols">';
echo '<div class="Rtable-cell label2 border">매수잔량</div>';
echo '<div class="Rtable-cell label border">호가</div>';
echo '<div class="Rtable-cell label2 border">매도잔량</div>';
outputOneRowC($hashMap,'mesuJan0','mesuHoka0','-');
outputOneRowC($hashMap,'mesuJan1','mesuHoka0','-');
outputOneRowC($hashMap,'mesuJan2','mesuHoka0','-');
outputOneRowC($hashMap,'mesuJan3','mesuHoka0','-');
outputOneRowC($hashMap,'mesuJan4','mesuHoka0','-');
outputOneRowC($hashMap,'-','medoHoka0','medoJan0');
outputOneRowC($hashMap,'-','medoHoka1','medoJan1');
outputOneRowC($hashMap,'-','medoHoka2','medoJan2');
outputOneRowC($hashMap,'-','medoHoka3','medoJan3');
outputOneRowC($hashMap,'-','medoHoka4','medoJan4');
echo '</div>';
/*
echo '<div class="Rtable-cell20">종목명</div>';
echo '<div class="Rtable-cell30">'.$hashMap["JongName"].'</div>';
echo '<div class="Rtable-cell20">현재가</div>';
echo '<div class="Rtable-cell30">'.$hashMap["CurJuka"].'</div>';
*/
/*
print_r ($hashMap);
print_r ($hashMap['JongName']);
*/
?>
