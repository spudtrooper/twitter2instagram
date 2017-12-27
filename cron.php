<?php

header('Content-Type: text/plain');

require_once('config.php');
require_once('common.php');

$urls = 
  [CREATE_IMAGE_HOST . '/checkTwitter.php',
   POST_TO_INSTAGRAM_HOST . '/checkForTweets.php?delete=1'];
foreach ($urls as $url) {
  $url = getUrl($url);
  echo "\n";
  echo 'START: ' . $url . ' ...';
  echo "\n";
  echo file_get_contents($url);
  echo "\n";
  echo 'END: ' . $url;
}

?>