<?php
include('libs/hawkiqPSApi/hawkiqPSApi.php');
class psn {

  public function getPsnData(){

    $psnID = "damcyk511";
    $psnapi = new hawkiqPSApi($psnID);
    $playerInfo = $psnapi->get_infos();

    $html =
    "<h1>PSN</h1>
    <i class='fas fa-star'></i>Level: ".$playerInfo['level']."<br>
    <i class='fas fa-trophy'></i>Platyna: ".$playerInfo['platinum']."<br>
    <i class='fas fa-trophy'></i>Złoto: ".$playerInfo['gold']."<br>
    <i class='fas fa-trophy'></i>Srebro: ".$playerInfo['silver']."<br>
    <i class='fas fa-trophy'></i>Brąz: ".$playerInfo['bronze']."<br>
    Total: ".$playerInfo['total']."<br>
    Osatnia gra: ".$playerInfo['lastgame']."<br>";

    return $html;
  }

}
?>
