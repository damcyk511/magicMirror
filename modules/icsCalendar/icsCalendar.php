<?php

class icsCalendar {

  function icsToDisplay($icsFile) {

    /* */

    $uglyArray    = $this->getIcsEventsAsArray($icsFile);
    $cleanArray   = $this->prepareIcs($uglyArray);
    $sortedEvents = $this->bubbleSort($cleanArray);

    return print_r($sortedEvents);

  }

  function getIcsEventsAsArray($file) {

    /* Funkcja pobiera plik .ics, a następnie konwertuje go na tablicę. */

    /* Źródło: */
    /* https://www.apptha.com/blog/import-google-calendar-events-in-php/ */

    $icalString = file_get_contents($file);
    $icsDates   = array();
    $icsData    = explode("BEGIN:", $icalString);

    foreach($icsData as $key => $value) {
        $icsDatesMeta[$key] = explode("\n", $value);
    }

    foreach($icsDatesMeta as $key => $value) {
        foreach($value as $subKey => $subValue) {
            $icsDates = $this->getICSDates($key, $subKey, $subValue, $icsDates);
        }
    }
    return $icsDates;
  }

  function getICSDates($key, $subKey, $subValue, $icsDates) {

    /* Funkcja wykonuje dodatkowe walidacje na pliku .ics. */

    /* Źródło: */
    /* https://www.apptha.com/blog/import-google-calendar-events-in-php/ */

    if($key != 0 && $subKey == 0) {
        $icsDates[$key]["BEGIN"] = $subValue;
    } else {
        $subValueArr = explode(":", $subValue, 2);
        if(isset($subValueArr[1])) {
            $icsDates[$key][$subValueArr[0]] = $subValueArr[1];
        }
    }
    return $icsDates;
  }

    function prepareIcs($icsDates) {

      /* Funkcja wyciąga wyłącznie potrzebne dane z całej przekonwertowanej tablicy */

        $icsDatesLength = sizeof($icsDates);
        for($i = 1; $i <= $icsDatesLength; $i++){

          if($icsDates[$i]["SUMMARY"]){
            if($icsDates[$i]["DTSTART"] || $icsDates[$i]["DTSTART;VALUE=DATE"]){

                $startTime = $icsDates[$i]["DTSTART"];
                if($icsDates[$i]["DTSTART;VALUE=DATE"]) {
                  $startTime = $icsDates[$i]["DTSTART;VALUE=DATE"];
                }
                $startTimestamp = strtotime($startTime);
                $startDate = date('d-m-Y H:i:s', $startTimestamp);

                $endTime = $startTime;
                $endTimestamp = $startTimestamp;
                $endDate = $startDate;

                if($icsDates[$i]["DTEND"] || $icsDates[$i]["DTEND;VALUE=DATE"]){
                  $endTime = $icsDates[$i]["DTEND"];
                  if($icsDates[$i]["DTEND;VALUE=DATE"]) {
                    $endTime = $icsDates[$i]["DTEND;VALUE=DATE"];
                  }

                  $endTimestamp = strtotime($endTime)-1; /* -1 sekunda, by cofnąć dzień */
                  $endDate = date('d-m-Y H:i:s', $endTimestamp);
                }

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
                $eventList[$i]['startTimestamp'] = $startTimestamp;
                $eventList[$i]['html'] = $activeEvent.$icsDates[$i]["SUMMARY"]."</br>Początek: ".$startDate.'</br> Koniec: '.$endDate.$timeToEnd.'</br></br>';
            }
          }
        }
        return $eventList;
    }

  function dateDifference($startDate, $endDate) {

    /* Funkcja oblicza ilość czasu między dwoma datami */

    /* Opracowano na podstawie: */
    /* http://php.net/manual/pl/datetime.diff.php */
    //dodać obsługę ostatniej cyfry

    $interval = date_diff(new DateTime($startDate), new DateTime($endDate));

         if ($interval->y > 4)  $intervalValue =  $interval->y.' lata';
    else if ($interval->y == 1) $intervalValue =  $interval->y.' rok';
    else if ($interval->y > 1)  $intervalValue =  $interval->y.' lat';
    else if ($interval->y == 0) {
           if ($interval->m > 4)  $intervalValue =  $interval->m.' miesięcy';
      else if ($interval->m == 1) $intervalValue =  $interval->m.' miesiąc';
      else if ($interval->m > 1)  $intervalValue =  $interval->m.' miesiące';
      else if ($interval->m == 0) {
             if ($interval->d > 1)  $intervalValue =  $interval->d.' dni';
        else if ($interval->d == 1) $intervalValue =  $interval->d.' dzień';
        else if ($interval->d == 0) {
               if ($interval->h > 4)  $intervalValue =  $interval->h.' godzin';
          else if ($interval->h == 1) $intervalValue =  $interval->h.' godzinę';
          else if ($interval->h > 1)  $intervalValue =  $interval->h.' godziny';
          else if ($interval->h == 0) {
                 if ($interval->i > 4)  $intervalValue =  $interval->i.' minut';
            else if ($interval->i == 1) $intervalValue =  $interval->i.' minutę';
            else if ($interval->i > 1)  $intervalValue =  $interval->i.' minuty';
          }
        }
      }
    }
    return $intervalValue;
  }

  function bubbleSort($array) {

    /* Funkcja sortuje tablicę na podstawie czasu rozpoczęcia zdarzenia */

    /* Opracowano na podstawie: */
    /* http://www.algorytm.org/algorytmy-sortowania/sortowanie-babelkowe-bubblesort/bubble-1-php.html */

    $arrayLength = sizeof($array);
    for($i = $arrayLength; $i >= 0; $i--) {
      for($j = 0; $j < $i-1; $j++) {

        if($array[$j]['startTimestamp'] > $array[$j+1]['startTimestamp']) {
          $tmp         = $array[$j];
          $array[$j]   = $array[$j+1];
          $array[$j+1] = $tmp;
        }
      }
    }

    for($i = 0; $i < $arrayLength; $i++){
      if(sizeof($array[$i])!=0){
        $sortedArray[] = $array[$i];
      }
    }
    return $sortedArray;
  }
}
?>
