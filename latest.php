<?php

/**
 * Download latest release from github release articats
 * Written by a.menk@imi.de
 * License: Public Domain
 */

define('REPO', 'imi-digital/iRobo');

$opts = [
	'http' => [
		'method' => 'GET',
		'header' => [
			'User-Agent: PHP'
		]
	]
];

$context = stream_context_create($opts);

$releases = file_get_contents('https://api.github.com/repos/' . REPO . '/releases', false, $context);
$releases = json_decode($releases);

$url = $releases[0]->assets[0]->browser_download_url;

header('Location: ' . $url);