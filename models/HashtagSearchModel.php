<?php

/**
 * Class to fetch search results from GET/search/tweets
 * using Twitter API
 * 
 */
class HashtagSearchModel {

	private $t_response = NULL;
	private $error_status   = false;

	/**
	 * Constuctor function for HashtagSearchModel class
	 * fetches tweets containing the given hashtag
	 * 
	 * @param $hashtag, Hashtag for which tweets are to be searched on Twitter
	 */
	function __construct($hashtag = NULL, $max_id = NULL){

		// opening an oauth connection to Twitter API
		$api_connection = new TwitterOAuth( CONSUMER_KEY, CONSUMER_SECRET, ACCESS_TOKEN, ACCESS_TOKEN_SECRET );

		// form the API endpoint URL with required parameters
		$api_url = 'https://api.twitter.com/1.1/search/tweets.json?q=' . urlencode( '#' . $hashtag ) . '&include_entities=false&result_type=recent&count=100';

		// if max_id is passed while instantiating the object,
		// include that in the API endpoint URL
		if( $max_id != NULL ){
			$api_url .= ( '&max_id=' . $max_id );
		}

		// making API call on search endpoint along with the given hashtag
		$tweets = $api_connection->get( $api_url );

		/**
		 * Enable error-checking when this kind of response is returned
		 * {"errors":[{"message":"Could not authenticate you","code":32}]}
		 * {"errors":[{"message":"Rate limit exceeded","code":88}]}
		 */
		if ( isset($tweets->errors) || !isset($tweets->statuses) ) {
			$this->error_status = true;
		}

		$this->t_response = $tweets;

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
		return $this->t_response;
	}

}
