# Twitter Custom Client

A simple Twitter API client that fetches and displays tweets that<br>
* have been retweeted at least once and<br>
* contain the hashtag `#custserv`<br>

##Features

* Built on the top of [SLIM framework](http://www.slimframework.com/)
* MVC Architecture based
* Easy to extend
* Infinite scroll feature (for older tweets)
* Supports powerful templating

##Installation

* Clone or download the source code zip
* Move it to the Document root
* Add your Twitter API credentials in `/includes/twitter-config-sample.php` and rename it to `twitter-config.php`
* Open browser and navigate to localhost
* All the recent tweets satisfying the above mentioned conditions will be displayed

## Possible Improvements

* Use MemCache or Redis to cache the tweets in order to save API calls
* Add configurable search terms support
* Write tests for HashtagSearch model

Feel free to contact me via [email](http://scr.im/sahildua)
