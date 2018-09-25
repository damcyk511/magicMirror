<?php
class spotify {

  public function getSpotifyData(){

    $jsonData     = "http://ws.audioscrobbler.com/2.0/?method=user.getrecenttracks&user=damcyk511&api_key=7cf4573ce7dd9bfb671d0862f753c27c&format=json";
    $json         = file_get_contents($jsonData);
    $obj          = json_decode($json, true);

    $songName     = str_replace('"', '', json_encode($obj['recenttracks']['track'][0]['name']));
    $artist       = str_replace('"', '', json_encode($obj['recenttracks']['track'][0]['artist']['#text']));
    $album        = str_replace('"', '', json_encode($obj['recenttracks']['track'][0]['album']['#text']));
    $coverImage   = str_replace('"', '', json_encode($obj['recenttracks']['track'][0]['image'][3]['#text']));
    $isNowPlaying = str_replace('"', '', json_encode($obj['recenttracks']['track'][0]['@attr']['nowplaying']));

    if($isNowPlaying != "null" && $isNowPlaying == true){
      $html =
      "<h1>Spotify <i class='fab fa-spotify'></i></h1>
      Utwór: ".$songName."<br>
      Wykonawca: ".$artist."<br>
      Album: ".$album."<br>
      <img src = ".$coverImage.">";
    }
    return $html;
  }
}
/*$spotifyData{
    html     ->
    position ->
}*/
?>
