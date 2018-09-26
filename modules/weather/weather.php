<?php
class weather {

  public function getWeatherData(){

    $jsonData = "http://dataservice.accuweather.com/forecasts/v1/daily/5day/274663?apikey=k1iXbGf6BwLyWi9ALodAnMHKGiQI9Onc&language=pl-pl";
    $json     = file_get_contents($jsonData);
    $obj      = json_decode($json);

    $html[]   =  "<h1>Pogoda</h1>";

    for($i = 0; $i < 5; $i++){
      $date              = str_replace('"', '', json_encode($obj->DailyForecasts[$i]->Date));
      $weekDay           = date('l', strtotime($date));
      $fahrenheitDegrees = json_encode($obj->DailyForecasts[$i]->Temperature->Maximum->Value); 
      $celsiusDegrees    = round(($fahrenheitDegrees - 32) * 5 / 9);
      $weatherStatus     = str_replace('"', '', json_encode($obj->DailyForecasts[$i]->Day->IconPhrase, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_ERROR_UTF8));
      $icon              = str_replace('"', '', json_encode($obj->DailyForecasts[$i]->Day->Icon, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_ERROR_UTF8));

      $html[] = $weekDay.": ".$celsiusDegrees."℃ ".$weatherStatus." ".$icon."<br>";
    }
    return implode($html);
  }
}
?>
