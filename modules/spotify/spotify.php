<?php
//refaktoryzacja kodu!!
class spotify {

  public function getSpotifyData(){

    $jsonData     = "http://ws.audioscrobbler.com/2.0/?method=user.getrecenttracks&user=damcyk511&api_key=####&format=json";
    $json         = file_get_contents($jsonData);
    $obj          = json_decode($json, true);

    $songName     = str_replace('"', '', json_encode($obj['recenttracks']['track'][0]['name']));
    $artist       = str_replace('"', '', json_encode($obj['recenttracks']['track'][0]['artist']['#text']));
    $album        = str_replace('"', '', json_encode($obj['recenttracks']['track'][0]['album']['#text']));
    $coverImage   = str_replace('"', '', json_encode($obj['recenttracks']['track'][0]['image'][3]['#text']));
    $isNowPlaying = str_replace('"', '', json_encode($obj['recenttracks']['track'][0]['@attr']['nowplaying']));

    if($isNowPlaying != "null" && $isNowPlaying == true){
      $spotifyData['html'] =
      //'<h1>Spotify <i class="fab fa-spotify"></i></h1>
        '<div id = "spotifySongName">'
          .$songName.'<br>
        </div>
        <script>replaceComplimentsWithSpotify()</script>
        <div id = "spotifyAuthor">
          ~ '.$artist.'<br>
        </div>

        <div id = "spotifyCoverImage">
          <img src = "'.$coverImage.'">
        </div>';
        $spotifyData['$isNowPlaying'] = $isNowPlaying;
        return $spotifyData;
    }
    $spotifyData['html'] = '<script>replaceComplimentsWithSpotify()</script>';
    $spotifyData['isNowPlaying'] = $isNowPlaying;
    return $spotifyData;
  }
}
/*$spotifyData{
    html     ->
    position ->
}*/

?>
