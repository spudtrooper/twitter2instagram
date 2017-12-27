<?php

require_once('common.php');
require_once('gdtext.php');

use GDText\Box;
use GDText\Color;

function solidColorImage($width=100, $height=100, $backgroundColor) {
  $im = imagecreatetruecolor($width, $height);
  if (!$backgroundColor) {
    $backgroundColor = imagecolorallocate($im, 0xff, 0xff, 0xff);
    echo '$backgroundColor=' . $$backgroundColor;
  }
  imagefill($im, 0, 0, $backgroundColor);
  return $im;
}

function createImage($text, $imageFile, $footer=null) {
  maybeMkdir(dirname($imageFile));

  $font = getRequestParam('font', 'courier.ttf');
  $footerFont = getRequestParam('footerFont', 'courier.ttf');
  $fontSize = intval(getRequestParam('fontSize', 18));
  $footerFontSize = intval(getRequestParam('footerFontSize', 
                                           4 * $fontSize / 5));
  $leftDist = intval(getRequestParam('leftDist', 30));
  $topDist = intval(getRequestParam('topDist', 30));
  $textAlignHorizontal = getRequestParam('alignHorizontal', 'left');
  $textAlignVertical = getRequestParam('alignVertical', 'top');
  info('font=' . $font);
  info('footerFont=' . $footerFont);

  // Create the image
  $charsPerLine = isset($_REQUEST['chars_per_line']) ?
    (int) ($_REQUEST['chars_per_line']) : 25;
  $textHeight = isset($_REQUEST['text_height']) ? 
    (int) ($_REQUEST['text_height']) : 28;
  $width = getRequestParam('width', 400);
  $height = getRequestParam('height', 400);
  info('width=' . $width);
  info('height=' . $height);
  
  $im = solidColorImage($width, $height, NULL);
  $fontColor = new Color(0, 0, 0);

  if ($footer) {
    $textbox = new Box($im);
    $textbox->setFontSize($footerFontSize);
    $textbox->setFontFace($footerFont);
    $textbox->setFontColor($fontColor);
    $imgWidth = imagesx($im) - 3 * $leftDist / 2;
    $imgHeight = imagesy($im) - $leftDist / 2;
    $textbox->setBox(-$leftDist/8, -$topDist, $imgWidth, $imgHeight);
    
    $textbox->setTextAlign('right', 'bottom');
    $textbox->draw($footer);
  }

  $textbox = new Box($im);
  $textbox->setFontSize($fontSize);
  $textbox->setFontFace($font);
  $textbox->setFontColor($fontColor);
  $imgWidth = imagesx($im) - 2 * $leftDist;
  $imgHeight = imagesy($im) - 2 * $topDist;
  $textbox->setBox($leftDist, $topDist, $imgWidth, $imgHeight);
  
  // now we have to align the text horizontally and vertically inside
  // the textbox the texbox covers whole image, so text will be
  // centered relatively to it
  $textbox->setTextAlign($textAlignHorizontal, $textAlignVertical);
  // it accepts multiline text
  $textbox->draw($text);
  
  // Save the image to the file for next time.
  imagejpeg($im, $imageFile);
  imagedestroy($im);

  info('Created image for ' . $imageFile);
}

?>