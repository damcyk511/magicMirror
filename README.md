będzie tu dużo rzeczy! :D


<?php
//https://www.apptha.com/blog/import-google-calendar-events-in-php/
class icsCalendar {
    /* Function is to get all the contents from ics and explode all the datas according to the events and its sections */

    function getIcsEventsAsArray($file) {
        $icalString = file_get_contents ( $file );
        $icsDates = array ();
        /* Explode the ICs Data to get datas as array according to string ‘BEGIN:’ */
        $icsData = explode ( "BEGIN:", $icalString );
        /* Iterating the icsData value to make all the start end dates as sub array */
        foreach ( $icsData as $key => $value ) {
            $icsDatesMeta [$key] = explode ( "\n", $value );
        }
        /* Itearting the Ics Meta Value */
        foreach ( $icsDatesMeta as $key => $value ) {
            foreach ( $value as $subKey => $subValue ) {
                /* to get ics events in proper order */
                $icsDates = $this->getICSDates ( $key, $subKey, $subValue, $icsDates );
            }
        }

        $icsDatesLength = sizeof($icsDates);
        for($i = 1; $i <= $icsDatesLength; $i++){
          if($icsDates[$i]["SUMMARY"]){

              $startTime = $icsDates[$i]["DTSTART"];
              if($icsDates[$i]["DTSTART;VALUE=DATE"]) {
                $startTime = $icsDates[$i]["DTSTART;VALUE=DATE"];
              }
              $startTimestamp = strtotime($startTime);
              $startDate = date('d-m-Y H:i:s', $startTimestamp);

              $endTime = $icsDates[$i]["DTEND"];
              if($icsDates[$i]["DTEND;VALUE=DATE"]) {
                $endTime = $icsDates[$i]["DTEND;VALUE=DATE"];
              }
              $endTimestamp = strtotime($endTime)-1; //-1 sekunda, by cofnąć dzień
              $endDate = date('d-m-Y H:i:s', $endTimestamp);

              $currentTimestamp = time();
              $currentDate = date('d-m-Y H:i:s', $currentTimestamp);

              $activeEvent = false;
              if($currentTimestamp < $endTimestamp) {
                if ($currentTimestamp > $startTimestamp){
                  $activeEvent = '<span style="color: yellow">Trwa </span>- ';
                }
              }

              $intervalValue = $this->dateDifference($currentDate, $endDate);

              $timeToEnd = '';
              if($activeEvent) $timeToEnd = '<br/><span style="color: yellow">Koniec za '.$intervalValue.'</span>';

              //$startDate
              //$endDate
              //$currentDate
              //$activeEvent
              //$timeToEnd
              //$timeToStart -- do zrobienia  -- "Początek za: 5 dni."
              /*
              if($activeEvent){
                $html .= Obiad w Koszykach -- koniec za 2 godziny;
              }
              */

              $html .= $activeEvent.$icsDates[$i]["SUMMARY"]."</br>Początek: ".$startDate.'</br> Koniec: '.$endDate.$timeToEnd.'</br></br>';
          }
        }
        return $html;
    }

    /* funcion is to avaid the elements wich is not having the proper start, end  and summary informations */
    function getICSDates($key, $subKey, $subValue, $icsDates) {
        if ($key != 0 && $subKey == 0) {
            $icsDates [$key] ["BEGIN"] = $subValue;
        } else {
            $subValueArr = explode ( ":", $subValue, 2 );
            if (isset ( $subValueArr [1] )) {
                $icsDates [$key] [$subValueArr [0]] = $subValueArr [1];
            }
        }
        return $icsDates;
    }

    function dateDifference($startDate, $endDate) {
      //oblicz różnicę między dwoma datami
      //dodać obsługę ostatniej cyfry
      $interval = date_diff(new DateTime($startDate), new DateTime($endDate));

           if ($interval->y > 4)  $intervalValue =  $interval->y.' lata';
      else if ($interval->y == 1) $intervalValue =  $interval->y.' rok';
      else if ($interval->y > 1)  $intervalValue =  $interval->y.' lat';
           if ($interval->m > 4)  $intervalValue =  $interval->m.' miesięcy';
      else if ($interval->m == 1) $intervalValue =  $interval->m.' miesiąc';
      else if ($interval->m > 1)  $intervalValue =  $interval->m.' miesiące';
           if ($interval->d > 1)  $intervalValue =  $interval->d.' dni';
      else if ($interval->d == 1) $intervalValue =  $interval->d.' dzień';
           if ($interval->h > 4)  $intervalValue =  $interval->h.' godzin';
      else if ($interval->h == 1) $intervalValue =  $interval->h.' godzinę';
      else if ($interval->h > 1)  $intervalValue =  $interval->h.' godziny';
           if ($interval->i > 4)  $intervalValue =  $interval->i.' minut';
      else if ($interval->i == 1) $intervalValue =  $interval->i.' minutę';
      else if ($interval->i > 1)  $intervalValue =  $interval->i.' minuty';

      return $intervalValue;
    }

}
?>
