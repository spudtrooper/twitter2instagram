# Twitter 2 Instagram

This is a very dirty solution for automatically creating instagram
posts from tweets. e.g. when @sarahcpr posted
https://twitter.com/sarahcpr/status/945378282471600129,
https://www.instagram.com/p/BdIzpwpj_gg was created.

## How it works

You need an twitter account, twitter API credentials, and an instagram
account.

* *checkTwitter.php* checks a twitter account and creates images for
  any posts for which it hasn't created images yet. State is kept in
  the file system.
* *checkForTweets.php* checks for images created from the step
  above. Images found are posted to the instagram account and the
  image is deleted so it won't be posted again.

The dirty parts:

1. Because of some dependency issues, these two scripts are written to
   run on different servers. I don't recall the specific dependency
   problems.
1. Instagram doesn't have a posting API, so *instagram.php* posts by
   forging a User-Agent and programmatically logging in. The down side
   is that this code requires plain-text access to your instagram
   password. This along with your twitter API credentials are stored
   in *config.php*.

## Dependencies:

* [GD Text](https://github.com/stil/gd-text) for creating images with text
* [Codebird](https://github.com/jublonet/codebird-php) for checking twitter

## Usage

1. Copy *config-sample.php* to *config.php*.
1. Upload all files to a server at a given path (say, *server-host-A*)
   that can run *checkTwitter.php*. Define CREATE_IMAGE_HOST to be
   *server-host-A* in *config.php*.
1. Upload all files to a server at a given path (say, *server-host-B))
   that can run *checkForTweets.php*. Define POST_TO_INSTAGRAM_HOST to
   be *server-host-B* in *config.php*.
1. Fill in the rest of *config.php* using twitter API credentials,
   twitter username, and instagram credentials.

When you want to convert tweets to instagram posts, run *cron.php* on
any server.

