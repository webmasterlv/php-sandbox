<?php
error_reporting(E_ALL);
ini_set('display_errors', true);

function dd($var) {
	echo '<pre>';
	var_dump($var);
	echo '</pre>';
}

$code = $_POST['code'];
$history_file = dirname(__FILE__) . '/history';

if (is_writable($history_file)) {
	file_put_contents($history_file, $code);
}

echo '<div class="script-result">';
$ts = microtime(true);

$response = @eval($code);

$t = sprintf('%f', microtime(true)-$ts);

echo '<div class="script-stats">';
echo 'Script took '.$t.' sec to complete | PHP version: '.PHP_MAJOR_VERSION.'.'.PHP_MINOR_VERSION.'.'.PHP_RELEASE_VERSION;
echo '</div>';

echo '</div>';

