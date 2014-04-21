<!DOCTYPE html>
<html>
<head>
  <title>Shutterstock API PHP Sample Video Code</title>
</head>
<body>

<?php


class ShutterstockAPI {
  protected $ch;
  protected $username;
  protected $key;

  public function __construct($username, $key) {
    $this->username = $username;
    $this->key = $key;
  }

  protected function getCurl($url) {
    if (is_null($this->ch)) {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
      curl_setopt($ch, CURLOPT_USERPWD, $this->username . ':' . $this->key);
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $this->ch = $ch;
    }

    curl_setopt($this->ch, CURLOPT_URL, $url);

    return $this->ch;
  }

  public function search($search_terms, $type='images') {
    $search_terms_for_url = preg_replace('/ /', '+', $search_terms);
    $url = 'http://api.shutterstock.com/' . $type . '/search.json?searchterm=' . $search_terms_for_url;
    $username = $this->username;
    $key = $this->key;
    $ch = $this->getCurl($url);
    $json = curl_exec($ch);
    return json_decode($json);
  }
}



$api = new ShutterstockAPI('[[API username]]', '[[API key]]');
$videos = $api->search('music', 'videos');

echo('<h1>Add clips to your video</h1>');
echo('<div id="wrapper" style="width:100%;">');
if ($videos) {
  for ($i = 0; $i < 8; $i++) {
    $description = $videos->results[$i]->description;
    $preview_image = $videos->results[$i]->sizes->preview_image->url;
    $video_id = $videos->results[$i]->video_id;
    $link = '[[Shutterstock Partner Platform Link]]' . urlencode($videos->results[$i]->web_url . '?id=' . $video_id);
    echo('<div class="image">' . "\n");
    echo('  <a href="' . $link . '">' . "\n");
    echo('    <img src="' . $preview_image . '" alt="' . $description . '" style="float:left; width:120px; margin:4px;">' . "\n");
    echo('  </a>' . "\n");
    echo('</div>' . "\n");

  }
}
echo('</div>');

?>



</body>
</html>
