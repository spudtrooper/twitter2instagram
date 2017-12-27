<?php

define('STATUSES_DIR', 'data/statuses');
define('NEW_IMAGES_DIR', 'data/images/new');
define('OLD_IMAGES_DIR', 'data/images/old');

function info($msg) {
  echo $msg . "\xA";
  echo '<br/>';
}

function note($msg) {
  echo $msg . "\xA";
  echo '<br/>';
}

function maybeMkdir($dir) {
  if (isset($_REQUEST['test'])) {
    $dir .= '-test';
  }
  if (!file_exists($dir)) {
    mkdir($dir, 0755, true);
  }
  return $dir;
}

function writeFile($filename,$contents) {
  info("Writing to '$filename'");
  $handle = fopen($filename,"w");
  if (fwrite($handle, $contents) === FALSE) {
    echo "Cannot write to file ($filename)";
    exit;
  }
  fclose($handle);
}

/**
 * Returns whether the new file was written.
 */
function writeStatusFile($screenName, $idStr, $text) {
  $outDir = maybeMkdir(STATUSES_DIR . '/' . $screenName);
  $outFile = $outDir . '/' . $idStr . '.txt';
  if (!file_exists($outFile)) {
    writeFile($outFile, $text);
    return true;
  }
  return false;
}

function getRequestParam($name, $defaultValue='') {
  return isset($_REQUEST[$name]) ? $_REQUEST[$name] : $defaultValue;
}

function normalizeTweet($tweet) {
  $tweet = urldecode($tweet);
  if (strlen($tweet) > 500) {
    exit(1);
  }
  $tweet = preg_replace('/<[^>]+>/', '', $tweet);
  $tweet = preg_replace('/&amp;/', '&', $tweet);
  return $tweet;
}



function shouldIncludeTweet($text, $truncated=FALSE) {
  if (!$truncated && preg_match('/https?\:\/\//', $text)) {
    return FALSE;
  }
  return TRUE;
}

function addParams($url, $params) {
  if ($params != '') {
    if (preg_match('/\?/', $url)) {
      $url .= '&';
    } else {
      $url .= '?';
    }
    $url .= $params;
  }
  return $url;
}

function getUrl($url) {
  if (isset($_REQUEST['test'])) {
    $rest = 'test=1';
  } else {
    $rest = '';
  }
  return addParams($url, $rest);
}

?>