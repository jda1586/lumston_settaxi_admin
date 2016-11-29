<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Third Party Services
	|--------------------------------------------------------------------------
	|
	| This file is for storing the credentials for third party services such
	| as Stripe, Mailgun, Mandrill, and others. This file provides a sane
	| default location for this type of information, allowing packages
	| to have a conventional place to find your various credentials.
	|
	*/

	'mailgun' => array(
		'domain' => '',
		'secret' => '',
	),

	'mandrill' => array(
		'secret' => '',
	),

	'stripe' => array(
		'model'  => 'User',
		'secret' => '',
	),

	//push notifications
	'google-apikey' => 'AIzaSyBEz_ep6USTV3J8x3i4TaZ-gP3A07Q_tJM',

	'facebook' => array(
		'appid'     => '816648705045250',
		'appsecret' => '27b0c839611d7b0b3f8b58a6d522d4f7',
	)

);
