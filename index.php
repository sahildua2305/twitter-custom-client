<?php

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


	/** Functions */


?>