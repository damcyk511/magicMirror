<?php

class icsCalendar {

  function icsToDisplay($icsFile, $calendarName = '#calendarName', $amountEventsToDisplay = 2) {

    /* Funkcja zwraca właściwe wydarzenia z kalendarza w formie. */

    $uglyArray    = $this->getIcsEventsAsArray($icsFile);
    $cleanArray   = $this->prepareIcs($uglyArray);
    $sortedEvents = $this->bubbleSort($cleanArray);
    $nextEvents   = $this->getNextEvents($sortedEvents, $amountEventsToDisplay);

    $nextEventsLength = sizeof($nextEvents);
    $html .= '<div id = "icsCalendarName">'.$calendarName.'</div>';
    $html .= '<table id = "iscCalendarTable">';
    for($i = 0; $i < $nextEventsLength; $i++){
      $html .= '<tr>';
      $html .= '<td id = "iscSumTd">';
      $html .= '<div id = "icsCalendarSummary"><i class="far fa-calendar"></i> '.$nextEvents[$i]['summary'].'</div>';
      $html .= '</td>';
      $html .= '<td id = "icsCalendarTime">';
      if($nextEvents[$i]['activeEvent']) {
        $html .= 'Koniec za '.$nextEvents[$i]['timeToEnd'];
      } else {
        $html .= 'Początek za '.$nextEvents[$i]['timeToStart'];
      }
      $html .= '</td>';
      $html .= '</tr>';
      $html .= '<br/>';
    }
    $html .= '</table>';

    return $html;

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

                $summary   = $icsDates[$i]["SUMMARY"];
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
                    $activeEvent = true;
                  }
                }

                $intervalValueStart = $this->dateDifference($currentDate, $endDate);

                $timeToEnd = '';
                if($activeEvent) $timeToEnd = $intervalValueStart;

                $intervalValueEnd = $this->dateDifference($currentDate, $startDate);
                $timeToStart = $intervalValueEnd;

                $eventList[$i]['summary']        = $summary;
                $eventList[$i]['startDate']      = $startDate;
                $eventList[$i]['endDate']        = $endDate;
                $eventList[$i]['timeToEnd']      = $timeToEnd;
                $eventList[$i]['timeToStart']    = $timeToStart;
                $eventList[$i]['startTimestamp'] = $startTimestamp;
                $eventList[$i]['activeEvent']    = $activeEvent;
            }
          }
        }
        return $eventList;
    }

  function getNextEvents($eventList, $amountEventsToReturn = 2) {
    
    /* Funkcja zwraca żądaną ilość zdarzeń */

    $currentTimestamp = time();
    $eventListLength = sizeof($eventList);

    for($i = 0; $i < $eventListLength; $i++) {
      if($eventList[$i]['activeEvent'] || $eventList[$i]['startTimestamp'] > $currentTimestamp) {
         $nextEvents[] = $eventList[$i];
      }
    }

    for($i = 0; $i < $amountEventsToReturn; $i++){
      $eventsToReturn[] = $nextEvents[$i];
    }
    return $eventsToReturn;
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
               if ($interval->h > 4)  $intervalValue =  $interval->h.' godz.';
          else if ($interval->h == 1) $intervalValue =  $interval->h.' godz.';
          else if ($interval->h > 1)  $intervalValue =  $interval->h.' godz.';
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
