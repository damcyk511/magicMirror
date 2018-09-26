<?php
//do zrobienia:
//pętla - licznik newsów
//inne źródła
//ile godzin temu
//zwracać 2 newsy
//przepisać kod na nowo z większą liczbą kanałów
// iteracja po wszystkich kanałach --> dodanie wszystkich newsów do jednej tablicy --> przesłanie dalej jednego losowego
class rss {

  public function getRssData(){

    $jsonData  = "https://api.rss2json.com/v1/api.json?rss_url=http%3A%2F%2Fwww.polsatsport.pl%2Frss%2Fwszystkie.xml&api_key=bajblns3gotrilxuarnyad5zbkxwv7tqazadifzw";
    $json      = file_get_contents($jsonData);
    $obj       = json_decode($json);

    $html[]    =  "<h1>Kanały RSS <i class='fas fa-rss'></i></h1>";

    for($i = 0; $i < 10; $i++){
      $rssSource       = str_replace('"', '', json_encode($obj->feed->title, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_ERROR_UTF8));
      $newsTitle       = str_replace('"', '', json_encode($obj->items[$i]->title, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_ERROR_UTF8));
      $newsDescription = str_replace('"', '', json_encode($obj->items[$i]->description, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_ERROR_UTF8));
      $newsDate        = str_replace('"', '', json_encode($obj->items[$i]->pubDate, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_ERROR_UTF8));

      $html[] =
      "<div id = 'rss".$i."'>
          <h2>".$newsTitle."</h2>".
          $newsDescription."<br>".
          $newsDate." ".$rssSource."<br>
      </div>";
    }
    return implode($html);
  }
}
?>
