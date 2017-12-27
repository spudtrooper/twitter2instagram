<?php

header('Content-Type: text/plain');

require_once('instagram.php');
require_once('common.php');
require_once('config.php');

function main() {
  $instagramUsername = INSTAGRAM_USERNAME;
  $instagramPassword = INSTAGRAM_PASSWORD;

  $base = CREATE_IMAGE_HOST;

  $skipLocal = getRequestParam('skip_local', false);
  $delete = getRequestParam('delete', false);

  if (isset($_REQUEST['test'])) {
    $rest = '?test=1';
  } else {
    $rest = '';
  }
  $latestUrl = getUrl($base . '/latest.php');
  info('latestUrl: ' . $latestUrl);
  $contents = file_get_contents($latestUrl);
  $paths = explode('|', $contents);
  foreach ($paths as $path) {
    if ($path == '') {
      info('Skipping empty path');
      continue;
    }
    if ($skipLocal && file_exists($path)) {
      info('Skipping ' . $path);
    }
    $url = getUrl($base . '/' . $path);
    info('Requesting image: ' . $url);
    $contents = file_get_contents($url);
    $outDir = maybeMkdir(dirname($path));
    $path = $outDir . '/' . basename($path);
    writeFile($path, $contents);
    $urlStr = '';
    echo 'Wrote to ' . $path;
    echo "\n";
    postToInstagram($instagramUsername, $instagramPassword, $path);
    if ($delete) {
      $urlToDelete = getUrl($base . '/delete.php?path=' . $path);
      info('Deleting image: ' . $urlToDelete);
      echo file_get_contents($urlToDelete);
      echo "\n";
    }
    echo 'Sent to instagram...';
    echo "\n";
  }
}

main();
?>