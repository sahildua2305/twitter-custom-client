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

?>