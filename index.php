<?php

	// include Twitter Oauth Library
	require_once 'includes/library/twitteroauth.php';
	
	// include config files
	require_once 'includes/twitter-config.php';

	// include all models
	require_once 'models/HashtagSearchModel.php';

	// include Slim
	require 'Slim/Slim.php';
	\Slim\Slim::registerAutoloader();

	// initiate Slim in DEBUG mode
	$app = new \Slim\Slim(array(
		'debug' => true,
		'mode' => 'development',
		'log.enables' => true,
		'log.level' => \Slim\Log::DEBUG
	));
	$app->config('debug', true);

	/** Routes */
	$app->map('/', 'showCustservTweets')->via('GET');
	$app->map('/get-more-tweets/:max_id', 'getMoreTweets')->via('GET');

	$app->run();

	/** Functions */

	function showCustservTweets(){

		global $app;

		// hard-code the hashtag for this sample app, can be taken from user
		$hashtag = 'custserv';

		// instantiate new hashtagsearch with the given hashtag
		$hashtag_search = new HashtagSearchModel($hashtag);

		if( $hashtag_search->getErrorStatus() ){
			
			// render the view and send the error code along with blank response for tweets
			$app->render (
				'show-tweets.php',
				array( 'error' => true,
					'tweets' => ""),
				400
			);
			
		}
		else {

			// get object of matched tweets
			$all_tweets = $hashtag_search->getTweets();

			// print_r($all_tweets);

			// render the view and send the matched tweets object
			$app->render (
				'show-tweets.php',
				array( 'error' => false,
					'tweets' => $all_tweets),
				200
			);

		}

	}

	/**
	 * getMoreTweets() - function catering to '/get-more-tweets/:max_id'
	 * used for getting more tweets from Twitter API (with a max_id)
	 * 
	 * @param  $max_id, Max ID before which tweets are to be fetched
	 * @return JSON encoded output containing more tweetsbefore the tweet with given max_id
	 */
	function getMoreTweets($max_id = NULL){

		/**
		 * Error Handling - if no max_id is passed with this request
		 */
		if( !max_id ){
			echo json_encode( array( 'status' => 400, 'html' => 'Invalid request parameters') );
			return;
		}

		global $app;

		// hard-code the hashtag for this sample app, can be taken from user
		$hashtag = 'custserv';		

		// instantiate new hashtagsearch with the given hashtag
		$hashtag_search = new HashtagSearchModel($hashtag, $max_id);

		

	}

?>