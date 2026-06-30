<?php

return [
	/*
	 * Connect to the Hubspot API using a private app access token
	 */
	'access_token' => env('HUBSPOT_ACCESS_TOKEN'),

	/*
	 * Connect to the Hubspot API using a Developer API Key
	 */
	'developer_key' => env('HUBSPOT_DEVELOPER_KEY'),

	/*
	 * Options to enable built in middlewares to handle rate limiting
	 *
	 * @see https://github.com/HubSpot/hubspot-api-php#api-client-comes-with-middleware-for-implementation-of-rate-and-concurrent-limiting
	 */
	'enable_constant_delay' => false,
	'exponential_delay' => null,

	/*
	 * Guzzle Client options that are user for Hubspot API requests
	 *
	 * @see https://docs.guzzlephp.org/en/stable/request-options.html
	 */
	'client_options' => [
		'http_errors' => true,
	],
	'emails' => [
		'message_received' => 185436260516,
		'vendor_registration' => 185422367587,
		'24h_follow_up' => 185334540107,
		'48h_follow_up' => 185334918739,
		'consultation_scheduled' => 185337146204,
		'consultation_scheduled_client' => 185341373049,
		'consultation_declined_client' => 185341902696,
		'booking_confirmed' => 185337570419,
		'first_booking_client' => 185342027784,
		'subsequent_booking_client' => 185422366307,
		'couple_registration_client' => 185420189343,
		'consultation_requested_client' => 185422365089
	]
];
