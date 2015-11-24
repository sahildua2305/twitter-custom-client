<?php

/**
 * Class to fetch search results from GET/search/tweets
 * using Twitter API
 * 
 */
class HashtagSearchModel {

	private $matched_tweets = NULL;
	private $error_status   = false;

	/**
	 * Constuctor function for HashtagSearchModel class
	 * fetches tweets containing the given hashtag
	 * 
	 * @param $hashtag, Hashtag for which tweets are to be searched on Twitter
	 */
	function __construct($hashtag = NULL){

		// opening an oauth connection to Twitter API
		$api_connection = new TwitterOAuth( CONSUMER_KEY, CONSUMER_SECRET, ACCESS_TOKEN, ACCESS_TOKEN_SECRET );

		// making API call on search endpoint along with the given hashtag
		$tweets = $api_connection->get( 'https://api.twitter.com/1.1/search/tweets.json?q=' . urlencode( '#' . $hashtag ) . '&result_type=recent&count=100');

		/**
		 * Enable error-checking when this kind of response is returned
		 * {"errors":[{"message":"Could not authenticate you","code":32}]}
		 * {"errors":[{"message":"Rate limit exceeded","code":88}]}
		 */
		if ( isset($tweets->errors) ) {
			$this->error_status = true;
		}

		$this->matched_tweets = $tweets;

	}

	/**
	 * getErrorStatus() - function for getting the error_status flag for current HashtagSearchModel object 
	 * 
	 * @return bool, whatever is the error status after fetching tweets from Twitter API
	 */
	public function getErrorStatus(){
		return $this->error_status;
	}

	/**
	 * getTweets() - function for getting access to the tweets returned by API for the given hashtag
	 * 
	 * @return object, Twitter tweets response object is returned directly
	 */
	public function getTweets(){
		return $this->matched_tweets;
	}

}
