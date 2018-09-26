<?php
//dodać obsługę pogody
//zawsze wysyłać dwa komplementy
class compliments {

  public function getCompliments(){

      date_default_timezone_set('Europe/Warsaw');

      $compliments = array(
                       'allTime'=>   array(
                         'Miło Cię widzieć!',
                         'Cześć!',
                         'Mamy ładny dzień!',
                       ),
                       'morning'=>   array(
                         'Mamy ranek.',
                       ),
                       'afternoon'=> array(
                         'Jest popołudnie',
                       ),
                       'evening'=>   array(
                         'Wieczór',
                       ),
                       'night'=>     array(
                         'Do spania!',
                       )
                     );

     $allTimeLength = sizeof($compliments['allTime']);
     for($i = 0; $i < $allTimeLength; $i++){
         $complimentsToDisplay[] = $compliments['allTime'][$i];
     }

     $hour = date('H');

     if($hour <= 5){ //night
       $timeOfDay = 'night';
     }
     else if($hour >= 6 && $hour <= 12){ //morning
       $timeOfDay = 'morning';
     }
     else if($hour >= 13 && $hour <= 17){ //afternoon
       $timeOfDay = 'afternoon';
     }
     else if($hour >= 18 && $hour <= 21){ //evening
       $timeOfDay = 'evening';
     }
     else if($hour >= 22){  //night
       $timeOfDay = 'night';
     }
     $timeOfDayLength = sizeof($compliments[$timeOfDay]);

     for($i = 0; $i < $timeOfDayLength; $i++){
         $complimentsToDisplay[] = $compliments[$timeOfDay][$i];
     }

    $complimentsToDisplayLength = sizeof($complimentsToDisplay);
    $randomCompliment = $complimentsToDisplay[rand(0,$complimentsToDisplayLength-1)];
    $html = '<span class = "compliments" >'.
              '<center>'.
                $randomCompliment.
              '</center>'.
            '</span>';

    return $html;
  }
}

?>
