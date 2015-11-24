<?php
	
	include 'header.php';

	echo '<pre>';
	print_r($tweets);
	echo '</pre>';
?>


<body class="container">
	<div class="visibile-lg" style="margin: 5% 0;"></div>
	<div class="container text-center">
		<img class="image-head" src="img/3be2c501646a37b5de1f6381df25a4dd.png">
		<div><p style="font-family: 'Poiret One', cursive;">Showing tweets for #custserv with atleast one retweet</p></div>
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div id="instant_results">
					<?php

						if ( !$error ) {	// if the error is set to false
						
							$tweets = $tweets->statuses;

							foreach($tweets as $tweet){
								// Change the condition to this, if you don't want to display retweets/quoted tweets
								// if ( $tweet->retweet_count == 0 || isset($tweet->retweeted_status) || isset($tweet->quoted_status))
								if ( $tweet->retweet_count == 0){
									continue;	// skip this tweet
								}
					?>
								<div class='timeline-tweets'>
								<img src='<?php echo $tweet->user->profile_image_url; ?>' class='img-thumbnail timeline' width='50'>
								<p><a href='http://twitter.com/intent/user?screen_name=<?php echo $tweet->user->screen_name; ?>' target='_blank'> <?php echo ($tweet->user->name); ?> <span class='text-muted'>@ <?php echo $tweet->user->screen_name; ?></span></a></p>
								<?php echo ($tweet->text);?><br>
								<span class='text-muted small'><?php echo date("g:i: A D, F jS Y",strtotime($tweet->created_at)); ?></span>
								<p class='tweet-controls'>
								<a href='https://twitter.com/intent/tweet?in_reply_to=<?php echo $tweet->id_str; ?>' target='_blank'> Reply</a>  |  <a href='https://twitter.com/intent/favorite?tweet_id=<?php echo $tweet->id_str; ?>' target='_blank'>Favorite</a>  |  <a href='https://twitter.com/intent/retweet?tweet_id=<?php echo $tweet->id_str; ?>' target='_blank'>Retweet</a>
								</p>
								</div>
					<?php
							}

						}

					?>
				</div>
			</div>
		</div>

<?php
	
	include 'footer.php';

?>
