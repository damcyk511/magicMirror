<?php
class currenciesRate {

  public function getCurrenciesRate(){

    $jsonData      = "http://api.nbp.pl/api/exchangerates/tables/A?format=json";
    $json          = file_get_contents($jsonData);
    $obj           = json_decode($json, true);

    $effectiveDate = str_replace('"', '', json_encode($obj[0]['effectiveDate']));
    $usd           = str_replace('"', '', json_encode($obj[0]['rates'][1]['mid']));
    $eur           = str_replace('"', '', json_encode($obj[0]['rates'][7]['mid']));
    $gbp           = str_replace('"', '', json_encode($obj[0]['rates'][10]['mid']));

    $html =
    "<h1>Kursy walut</h1>
    <i class='fas fa-dollar-sign'></i>".$usd."<br>
    <i class='fas fa-euro-sign'></i>".$eur."<br>
    <i class='fas fa-pound-sign'></i>".$gbp."<br>";

    return $html;
  }
}
?>
