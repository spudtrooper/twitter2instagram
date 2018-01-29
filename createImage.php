<?php

require_once('image.php');
require_once('common.php');

function main() {
  setDebug(false);
  $text = getRequestParam('text');
  $id = getRequestParam('id');
  $screenName = getRequestParam('screenName');
  $color = getRequestParam('color', 0);
  $footer = getRequestParam('footer');
  maybeMkdir('view');
  maybeMkdir('view/' . $screenName);
  $imageFile = getRequestParam('imageFile', 
                               'view/' . $screenName . '/'. $id . '.jpg');
  createImage($text, $imageFile, '@' . $screenName);
  echo '<img src="' . $imageFile . '">';
}

main();
?>