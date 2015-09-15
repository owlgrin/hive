<?php

return array(

	'bus' => 'Owlgrin\Hive\Command\Bus\TransactionBus',

	'response' => array(
		/**
		 * The default headers that are needed to be sent with every response
		 */
		'headers' => array(
			// 'Access-Control-Allow-Origin' => 'http://example.com'
		),

		/**
		 * The 'after' filters, if any to be run when creating responses
		 */
		'after' => null
	),
);