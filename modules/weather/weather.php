<?php
class weather {

  public function getWeatherData(){

    //$jsonData = "http://dataservice.accuweather.com/forecasts/v1/daily/5day/274663?apikey=jpr7owu2pfdCLi3rz7N2NezR4vvHiGEz&language=pl-pl";
    $jsonData = "http://api.openweathermap.org/data/2.5/forecast?q=Warszawa&APPID=be873ad92e17bd8ff1bfded505167d58&units=metric";
    $json     = file_get_contents($jsonData);
    $obj      = json_decode($json);

    for($i = 0; $i < 5; $i++){
      $date              = str_replace('"', '', json_encode($obj->list[$i]->dt_txt));
      $weekDay           = date('l', strtotime($date));
      $celsiusDegrees    = round(json_encode($obj->list[$i]->main->temp));
      //$weatherStatus     = str_replace('"', '', json_encode($obj->DailyForecasts[$i]->Day->IconPhrase, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_ERROR_UTF8));
      //$icon              = str_replace('"', '', json_encode($obj->DailyForecasts[$i]->Day->Icon, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_ERROR_UTF8));

      $html[] = $date.": ".$celsiusDegrees."<br>";
    }
    return implode($html);
  }
}
?>
