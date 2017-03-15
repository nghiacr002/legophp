<?php

/**
 *
 *
  ---------------------------------------------------------------------------------------------------------

 *                   // Match all request URIs
  [i]                  // Match an integer
  [i:id]               // Match an integer as 'id'
  [a:action]           // Match alphanumeric characters as 'action'
  [h:key]              // Match hexadecimal characters as 'key'
  [:action]            // Match anything up to the next / or end of the URI as 'action'
  [create|edit:action] // Match either 'create' or 'edit' as 'action'
  [*]                  // Catch all (lazy, stops at the next trailing slash)
  [*:trailing]         // Catch all as 'trailing' (lazy)
  [**:trailing]        // Catch all (possessive - will match the rest of the URI)
  .[:format]?          // Match an optional parameter 'format' - a / or . before the block is also optional

  ---------------------------------------------------------------------------------------------------------
 */
$_ROUTER['default'] = array(
    'core_image_thumb' => array(
        'route' => 'image/[:size]',
        'controller' => 'media',
        'action' => 'thumb',
        'module' => 'core',
        'params' => array(),
    ),
    'core_media_original_url' => array(
        'route' => 'media/original/[**:path]',
        'controller' => 'media',
        'action' => 'original',
        'module' => 'core',
        'params' => array(),
    ),
	'core_hello_world' => array(
			'route' => 'hello/index',
			'controller' => 'hello',
			'action' => 'index',
			'module' => 'core',
			'params' => array(),
	),
);
