<?php
//do zrobienia:
//ile godzin temu
//zwracać 2 newsy

class rss {

  public function getRssData(){

    $rssStore = array(
      /*"https://api.rss2json.com/v1/api.json?rss_url=https%3A%2F%2Fwww.tvn24.pl%2Fnajwazniejsze.xml&api_key=bajblns3gotrilxuarnyad5zbkxwv7tqazadifzw",*/
      "https://api.rss2json.com/v1/api.json?rss_url=http%3A%2F%2Fwww.polsatsport.pl%2Frss%2Fwszystkie.xml&api_key=bajblns3gotrilxuarnyad5zbkxwv7tqazadifzw"
    );

    $rssStoreLength = sizeof($rssStore);
    $newsPool = [];
    $param = JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_ERROR_UTF8;

    for($i = 0; $i < $rssStoreLength; $i++){
      $rssChanel = $rssStore[$i];

      $curl_handle = curl_init();
      curl_setopt($curl_handle, CURLOPT_URL, $rssChanel);
      curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
      curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
      $jsonData = curl_exec($curl_handle);
      curl_close($curl_handle);

      $obj = json_decode($jsonData);
      $rssChanelLength = sizeof($obj->items);

      for($j = 0; $j < $rssChanelLength; $j++){
        $rssSource       = str_replace('"', '', json_encode($obj->feed->title, $param));
        $newsTitle       = str_replace('"', '', json_encode($obj->items[$j]->title, $param));
        $newsDescription = str_replace('"', '', json_encode($obj->items[$j]->description, $param));
        $newsDate        = str_replace('"', '', json_encode($obj->items[$j]->pubDate, $param));

        $newsPool[] =
        '<span class = "rss">'.
            '<div id = "rssTitle">'.
              $newsTitle.
            '</div>'.

            '<div id = "rssDescription">'.
              $newsDescription.
            '</div>'.

            '<div id = "rssDetails">'.
              $rssSource.' '.$newsDate.
            '</div>'.
        '</span>';
      }
    }
    $newsPoolLength = sizeof($newsPool);
    $randomNews = $newsPool[rand(0,$newsPoolLength-1)];
    return $randomNews;
  }
}


?>
