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

function outputTable($attrList)
{
$variable = <<<XYZ
<table class="board-list10">
  <thead>
    <tr>
      <th>Name</th>
      <th>Value</th>
    </tr>
  </thead>
  <tbody>
XYZ;
echo $variable;

foreach($attrList as $a => $b) {
echo "<tr>";
echo "<td>".$a."</td>";
echo "<td>".$b."</td>";
echo "</tr>";
}
$end = <<<XYZ
  </tbody>
</table>
XYZ;
echo $end;
}

$style = <<<XYZ
<style type='text/css'>
html, body, article, aside, details, figcaption, figure, header, hgroup, menu, nav, section, header, table, tbody, tfoot, thead, tr, th, td, article, aside, audio, video
{margin:0;height:100%;padding:0;border:0;outline:0;font-size:100%;font:inherit;font-family:'NanumSquare','나눔스퀘어','Nanum Square Light',Oswald,'Nanum Gothic';color:#555;vertical-align:middle;background:transparent;box-sizing:border-box;-o-box-sizing:border-box;-ms-box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box}

table.board-list10{width:100%;border-top:2px solid #019b45;text-align:center}
table.board-list10 thead th{padding:20px 5px;border-bottom:1px solid #ccc;font-weight:bold;background:url("/common/bulImg/bul_Table3Line.gif") no-repeat left center;}
table.board-list10 tbody td,
table.board-list10 tbody th{padding:20px 12px;background:url("bul_Table3Line.gif") no-repeat left center;border-bottom:1px solid #ccc;}
table.board-list10.layout{border-collapse:collapse;width:100%;}
table.board-list10.display{margin:1em 0;}
table.board-list10.display th,
table.board-list10.display td{padding:20px 5px}
table.board-list10.responsive-table{}
table.board-list10 .actions{color:#fff;background:#019b45}
table.board-list10 tbody td{text-overflow:ellipsis;white-space:nowrap;word-wrap:normal;max-width:220px;overflow:hidden;}

table.board-list10 tbody th.first{border-left:0;}
table.board-list10 tbody td:first-child,
table.board-list10 thead th:first-child,
table.board-list10 tbody th:first-child{background:none}
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
echo "<h2>Query Time</h2>";
//outputTable($result->attributes());
foreach($result->attributes() as $a => $b) {
    echo $b."<br>";
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
echo "<h2>TBL_StockInfo</h2>";
outputTable($result->TBL_StockInfo->attributes());
echo "<h2>TBL_AskPrice</h2>";
for ($i = 0; $i < 5; $i++) {
  outputTable($result->TBL_AskPrice->AskPrice[$i]->attributes());
}
echo "<h2>TBL_Hoga</h2>";
outputTable($result->TBL_Hoga->attributes());
echo "<h2>StockInfo</h2>";
outputTable($result->stockInfo->attributes());
/*
foreach($result->TBL_TimeConclude->TBL_TimeConclude[0]->attributes() as $a => $b) {
    echo $a,'="',$b,"\"\n";
}
*/

?>