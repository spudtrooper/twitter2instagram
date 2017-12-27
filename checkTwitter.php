<?php

header('Content-Type: text/plain');

require_once('twitter.php');
require_once('image.php');
require_once('common.php');
require_once('config.php');

function getNumStatusFiles($twitterScreenName) {
  $num = 0;
  $dirname = maybeMkdir(STATUSES_DIR . '/' . $twitterScreenName);
  if (! $dh = opendir($dirname) ) {
    die("Whoops, couldn't open $base!");
  }
  while (($fileName = readdir($dh)) !== false) {
    $num++;
  }
  return $num;
}

function main() {
  info('PHP version: ' . phpversion());

  $twitterScreenName = TWITTER_SCREEN_NAME;

  $numStatusFiles = getNumStatusFiles($twitterScreenName);
  foreach  (getTweets($twitterScreenName) as $obj) {
    $text = $obj->text;
    $id = $obj->id;
    $truncated = $obj->truncated;
    info('Looking at obj ' 
         . ' id=' . $id 
         . ' text=' . $text 
         . ' truncated=' . $truncated);
    if (!shouldIncludeTweet($text, $truncated)) {
      info('Skipping text = '.  $text . ' truncated='. $truncated);
      continue;
    }
    if (!writeStatusFile($twitterScreenName, $id, $obj->text)) {
      info('Skipping id=' . $id);
      continue;
    }
    $outDir = maybeMkdir(NEW_IMAGES_DIR . '/' . $twitterScreenName);
    $imageFile = $outDir . '/'. $obj->id . '.jpg';
    info('Creating image for ' . $text . ' to ' . $imageFile);
    createImage($obj->text, $imageFile, '@' . $twitterScreenName);
    $urlFile = $outDir . '/'. $obj->id . '.url';
    writeFile($urlFile, $obj->url);
  }  
}

main();
?>