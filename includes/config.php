<?php
// define site name
define('SITE_NAME', 'SimpleLance DEV');

// define site URL
define('SITE_URL', 'simplelance.dev');

// define database settings
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'simplelance');

// define currency
define('CURRNAME', 'Euro');
define('CURRSYM', '&euro;');
define('CURRCODE', 'EUR');

// set stripe keys
$stripe = array(
    "publishable_key" => "pk_key",
    "secret_key" => "sk_key"
);
