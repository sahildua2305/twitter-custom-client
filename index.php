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
	$app->map('/get-more-tweets', 'getMoreTweets')->via('GET');

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
					't_response' => ""),
				403
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
					't_response' => $all_tweets),
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
		 * Error Handling - if no next_results parameter is passed with this request
		 */
		if( !isset($_REQUEST['next_results']) || $_REQUEST['next_results'] == NULL ){
			echo json_encode( array( 'status' => 400, 'html' => 'Invalid request parameters') );
			return;
		}

		global $app;

		// retrieve the request parameters for the GET request
		$next_results = $_REQUEST['next_results'];

		// hard-code the hashtag for this sample app, can be taken from user
		$hashtag = 'custserv';

		// instantiate new hashtagsearch with the given hashtag
		$hashtag_search = new HashtagSearchModel($hashtag, $next_results);

		// get Twitter response from the model
		$t_response = $hashtag_search->getTweets();

		/**
		 * ERROR HANDLING
		 */
		if( isset($t_response->statuses) ){

			$tweets = $t_response->statuses;

			// Appending new tweets to the html response
			$html = '';
			foreach($tweets as $a){
				// TRICKY: Change the condition to this, if you don't want to display retweets/quoted tweets
				// if ( $a->retweet_count == 0 || isset($a->retweeted_status) || isset($tweet->quoted_status))
				if ( $a->retweet_count == 0){
					continue;	// skip this tweet
				}
				$html .= "<div class='timeline-tweets'>";
				$html .="<img src='".$a->user->profile_image_url."' class='img-thumbnail timeline' width='50'>";
				$html .="<p><a href='http://twitter.com/intent/user?screen_name=".$a->user->screen_name."' target='_blank'>".($a->user->name)." <span class='text-muted'>@".$a->user->screen_name."</span></a></p>";
				$html .=($a->text)."<br>";
				$html .="<span class='text-muted small'>".date("g:i: A D, F jS Y",strtotime($a->created_at))."</span>";
				$html .="<p class='tweet-controls'>";
				$html .="<a href='https://twitter.com/intent/tweet?in_reply_to=".$a->id_str."' target='_blank'> Reply</a>  |  <a href='https://twitter.com/intent/favorite?tweet_id=".$a->id_str."' target='_blank'>Favorite</a>  |  <a href='https://twitter.com/intent/retweet?tweet_id=".$a->id_str."' target='_blank'>Retweet</a>";
				$html .="</p>";
				$html .="</div>";

			}

			// fetching new max_id
			$new_next_results = $t_response->search_metadata->next_results;

			echo json_encode( array('status' => 200,
									'html' => $html,
									'response' => $t_response,
									'old_next_results' => $next_results,
									'new_next_results' => $new_next_results));
		}
		else{

			echo json_encode( array('status' => 400,
									'html' => '',
									'response' => $t_response,
									'old_next_results' => $next_results,
									'new_next_results' => $next_results));

		}

	}

?>