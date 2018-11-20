<?php
class weather {

  CONST ICON_CLEAR_SKY_DAY = 'http://openweathermap.org/img/w/01d.png';
  CONST ICON_FEW_CLOUDS_DAY = 'http://openweathermap.org/img/w/02d.png';
  CONST ICON_SCATTERED_CLOUDS_DAY = 'http://openweathermap.org/img/w/03d.png';
  CONST ICON_BROKEN_CLOUDS_DAY = 'http://openweathermap.org/img/w/04d.png';
  CONST ICON_SHOWER_RAIN_DAY = 'http://openweathermap.org/img/w/09d.png';
  CONST ICON_RAIN_DAY = 'http://openweathermap.org/img/w/10d.png';
  CONST ICON_THUNDERSTORM_DAY = 'http://openweathermap.org/img/w/11d.png';
  CONST ICON_SNOW_DAY = 'http://openweathermap.org/img/w/13d.png';
  CONST ICON_MIST_DAY = 'http://openweathermap.org/img/w/50d.png';
  CONST DEGREE_SIGN = '°';
  public function getWeatherData(){

    $jsonData = "http://api.openweathermap.org/data/2.5/forecast?q=Warszawa&APPID=be873ad92e17bd8ff1bfded505167d58&units=metric";
    $json     = file_get_contents($jsonData);
    $obj      = json_decode($json, true);

    $list       = $obj['list'];
    $listLength = sizeof($list);

    $fullDateNow           = $list[0]['dt_txt'];
    $celsiusDegreesNow = round($list[0]['main']['temp'], 0);
    $weatherStatusNow  = $list[0]['weather'][0]['description'];
    $weatherIconNow    = $list[0]['weather'][0]['icon'];

    $today = date('Y-m-d');
    $html[] = '<table id = "weatherTable">';
    $html[] = '<tr><th>'.$this->getWeatherIcon($weatherIconNow).'</th><th id = "weatherDegreesNow">'.$celsiusDegreesNow.SELF::DEGREE_SIGN.'</th></tr>';

    for($i = 0; $i < $listLength; $i++) {
      $fullDate       = $list[$i]['dt_txt'];
      $date           = date('Y-m-d',strtotime($fullDate));
      $hour           = date('H',strtotime($fullDate));
      $weekDay        = $this->getWeekday($list[$i]['dt_txt']);
      $celsiusDegrees = round($list[$i]['main']['temp'], 0);
      $weatherStatus  = $list[$i]['weather'][0]['description'];
      $weatherIcon    = $list[$i]['weather'][0]['icon'];
      
      if($today != $date) {
        if($hour == '12') {
          $celsiusDegreesDay = round($list[$i]['main']['temp'], 0);
          $celsiusDegreesNight = round($list[$i+4]['main']['temp'], 0);
          $html[] = '<tr><th id = "weatherWeekday">'.$weekDay.'</th><th id = "weatherIconTh">'.$this->getWeatherIcon($weatherIcon).'</th><th id = "weatherDegreesDay">'.$celsiusDegreesDay.SELF::DEGREE_SIGN.'</th><th id = "weatherDegreesNight">'.$celsiusDegreesNight.SELF::DEGREE_SIGN.'</th></tr>';
        }
      } else {
        $lastArrayElement = $i;
      }
    }
    $html[] = '</table>';
    return implode($html);
  }

  function getWeatherIcon($weatherIcon){
      //zamienic if na case
      //dodac noc
    if(strpos($weatherIcon,'01') !== false)       $iconToDisplay = '<img id = "weatherIcon" src = "'.SELF::ICON_CLEAR_SKY_DAY.'">';
    else if (strpos($weatherIcon,'02') !== false) $iconToDisplay = '<img id = "weatherIcon" src = "'.SELF::ICON_FEW_CLOUDS_DAY.'">';
    else if (strpos($weatherIcon,'03') !== false) $iconToDisplay = '<img id = "weatherIcon" src = "'.SELF::ICON_SCATTERED_CLOUDS_DAY.'">';
    else if (strpos($weatherIcon,'04') !== false) $iconToDisplay = '<img id = "weatherIcon" src = "'.SELF::ICON_BROKEN_CLOUDS_DAY.'">';
    else if (strpos($weatherIcon,'09') !== false) $iconToDisplay = '<img id = "weatherIcon" src = "'.SELF::ICON_SHOWER_RAIN_DAY.'">';
    else if (strpos($weatherIcon,'10') !== false) $iconToDisplay = '<img id = "weatherIcon" src = "'.SELF::ICON_RAIN_DAY.'">';
    else if (strpos($weatherIcon,'11') !== false) $iconToDisplay = '<img id = "weatherIcon" src = "'.SELF::ICON_THUNDERSTORM_DAY.'">';
    else if (strpos($weatherIcon,'13') !== false) $iconToDisplay = '<img id = "weatherIcon" src = "'.SELF::ICON_SNOW_DAY.'">';
    else if (strpos($weatherIcon,'50') !== false) $iconToDisplay = '<img id = "weatherIcon" src = "'.SELF::ICON_MIST_DAY.'">';

    return $iconToDisplay;
  }

  function getWeekday($date) {
    $dayOfWeekend = date('w', strtotime($date));
    $weekday = ['Niedziela', 'Poniedziałek', 'Wtorek', 'Środa', 'Czwartek', 'Piątek', 'Sobota'];
    $weekdayShort = ['Nd. ', 'Pon.', 'Wt. ', 'Śr. ', 'Czw.', 'Pt. ', 'Sb. '];
    return $weekdayShort[$dayOfWeekend];
  }
}
?>
