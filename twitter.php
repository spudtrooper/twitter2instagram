<?php

require_once('config.php');
require_once('codebird.php');
require('common.php');

use Codebird\Codebird;

function getCodebird() {
  Codebird::setConsumerKey(CONSUMER_KEY, CONSUMER_SECRET);
  $cb = Codebird::getInstance();
  $cb->setToken(ACCESS_TOKEN, ACCESS_TOKEN_SECRET);
  return $cb;
}

function getTweets($screenName) {
  $cb = getCodebird();
  $api_options = array
    ('screen_name' => $screenName,
     //  Add alt tags
     //  https://blog.twitter.com/2016/alt-text-support-for-twitter-cards-and-the-rest-api
     'include_ext_alt_text' => true,
     //  Add Long Text Supported
     //  https://dev.twitter.com/overview/api/upcoming-changes-to-tweets
     'tweet_mode' => 'extended');
  $reply = (array) $cb->statuses_userTimeline($api_options);
  $res = [];
  for ($i = 0; $i < count($reply); $i++) {
    $msg = $reply[$i];
    $id = $msg->{id_str};
    $text = $msg->{full_text};
    $truncated = $msg->{truncated};
    info('text = ' . $text);
    info('truncated = ' . $truncated);
    info('$id = ' . $id . ' $msg->{in_reply_to_user_id_str} = ' 
         . $msg->{in_reply_to_user_id_str});
    if (!is_null($msg->{in_reply_to_user_id_str})) {
      info('Skipping because in_reply_to_user_id_str=' . 
           $msg->{in_reply_to_user_id_str} . ' is not null');
      continue;
    }
    // Skip retweets.
    if (preg_match('#^RT #', $text) === 1) {
      info('Skipping because RT');
      continue;
    }
    if (preg_match('#^$#', $text) === 1) {
      info('Skipping because no text');
      continue;
    }
    if (preg_match('#^$#', $id) === 1) {
      info('Skipping because no id');
      continue;
    }
    info("text: " . $text);
    info("id: " . $id);
    $url = $msg->entities->{urls}[0]->{url};
    $obj = (object) [
                     'screenName' => $screenName,
                     'id' => $id,
                     'text' => $text,
                     'url' => $url,
                     'truncated' => $truncated,
                     ];
    print_r($obj);
    $res[] = $obj;
  }
  return $res;
}

?>