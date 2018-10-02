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
              $endTimestamp = strtotime($endTime);
              $endDate = date('d-m-Y H:i:s', $endTimestamp);

              $currentTimestamp = time();

              $activeEvent = false;
              if($currentTimestamp < $endTimestamp) {
                if ($currentTimestamp > $startTimestamp){
                  $activeEvent = '<span style="color: yellow">Trwa </span>- ';
                }
              }
              //https://www.w3schools.com/php/showphp.asp?filename=demo_func_date_diff
              $html .= $activeEvent.$icsDates[$i]["SUMMARY"]."</br>Początek: ".$startDate.'</br> Koniec: '.$endDate.'</br></br>';
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

}
?>
