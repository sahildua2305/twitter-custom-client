<?php
session_start();
require_once('library/twitteroauth.php');
include('config.php');

//$_POST['keyword'] = "#sahil";
if(!isset($_POST['keyword'])){
	echo '<script>window.location="index.php";</script>';
}
else if($_POST['keyword']=='#'){
	echo json_encode(array("success"=>"null","html"=>"","response"=>""));
}
else{
	// read the current active OAUTH account 
	$file = fopen("id.txt", "r");
	$index = fgets($file);
	fclose($file);
	
	$connection = new TwitterOAuth($CONSUMER_KEY, $CONSUMER_SECRET, $OAUTH_TOKEN[$index], $OAUTH_TOKEN_SECRET[$index]);
	//for($i=0;$i<180;$i++)
	$tweets = $connection->get('https://api.twitter.com/1.1/search/tweets.json?q='.urlencode($_POST['keyword']).'&result_type=recent');
	
	// Enable error-checking when this kind of response is returned
	// {"errors":[{"message":"Could not authenticate you","code":32}]}
	// {"errors":[{"message":"Rate limit exceeded","code":88}]}
	
	if($tweets->errors && $tweets->errors[0]->code==32){
		$connection = new TwitterOAuth($CONSUMER_KEY, $CONSUMER_SECRET, $OAUTH_TOKEN[$index], $OAUTH_TOKEN_SECRET[$index]);
		$tweets = $connection->get('https://api.twitter.com/1.1/search/tweets.json?q='.urlencode($_POST['keyword']).'&result_type=recent');
		
		mail('sahildua2305@gmail.com', 'Twitter-Instant-Authentication-Problem', '{"errors":[{"message":"Could not authenticate you","code":32}]}');
	}
	
	if($tweets->errors && $tweets->errors[0]->code==88){
		// Limit exceeded
		// change the OAUTH_TOKEN and OAUTH_TOKEN_SECRET index
		
		$index = ($index+1)%count($OAUTH_TOKEN);
		$file = fopen("id.txt", "w");
		fwrite($file, $index);
		fclose($file);
		
		$connection = new TwitterOAuth($CONSUMER_KEY, $CONSUMER_SECRET, $OAUTH_TOKEN[$index], $OAUTH_TOKEN_SECRET[$index]);
		$tweets = $connection->get('https://api.twitter.com/1.1/search/tweets.json?q='.urlencode($_POST['keyword']).'&result_type=recent');
		
		mail('sahildua2305@gmail.com', 'Twitter-Instant-Limit-Exceeded', "Switched to new account");
	}
	
	foreach($tweets as $tweet){
		break;
	}
	$html = '';
	foreach($tweet as $a){
		$html .= "<div class='timeline-tweets'>";
		$html .="<img src='".$a->user->profile_image_url."' class='img-thumbnail timeline' width='50'>";
		$html .="<p><a href='http://twitter.com/intent/user?screen_name=".$a->user->screen_name."' target='_blank'>".($a->user->name)." <span class='text-muted'>@".$a->user->screen_name."</span></a></p>";
		$html .=($a->text)."<br>";
		$html .="<span class='text-muted small'>".date("g:i: A D, F jS Y",strtotime($a->created_at))."</span>";
		$html .="<p class='tweet-controls'>";
		if($a->user->screen_name != $_SESSION['twi_tw_screen_name'])
			$html .="<a href='https://twitter.com/intent/tweet?in_reply_to=".$a->id_str."' target='_blank'> Reply</a>  |  <a href='https://twitter.com/intent/favorite?tweet_id=".$a->id_str."' target='_blank'>Favorite</a>  |  <a href='https://twitter.com/intent/retweet?tweet_id=".$a->id_str."' target='_blank'>Retweet</a>";
		$html .="</p>";
		$html .="</div>";
	}
	echo json_encode(array("success"=>"true", "html"=>$html,"response"=>$tweets));
}


?>
