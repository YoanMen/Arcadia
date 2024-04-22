<?php

if ($_SERVER['SERVER_NAME'] == 'localhost') {
	/** database config LOCAL **/
	define('DB_NAME', 'arcadia');
	define('DB_HOST', 'localhost');
	define('DB_USER', 'root');
	define('DB_PASSWORD', '');

	define('ROOT', 'http://localhost');

	define('COUCHDB_URL', "http://admin:115225335@localhost:5984/arcadia");
} else {
	/** database config**/
	define('DB_NAME', 'my_db');
	define('DB_HOST', 'localhost');
	define('DB_USER', 'root');
	define('DB_PASSWORD', '');
	define('DB_DRIVER', '');

	define('ROOT', 'https://yoanmen.alwaysdata.net');

	define('COUCHDB_URL', "http://admin:115225335@couchdb-yoanmen.alwaysdata.net:5984/yoanmen_arcadia");
}

define('APP_NAME', "Zoo Arcadia");
define('APP_DESC', "Arcadia Zoo official website");

/** true means show errors **/
define('DEBUG', true);

/** Upload files config**/
define('DESTINATION_IMAGE_FOLDER', 'uploads/');
define('MAX_FILE_SIZE', 3048576);
define('ALLOWED_EXTENSIONS_FILE', ['png', 'jpeg', 'jpg', 'webp']);
define('ALLOWED_MIME_TYPES', [
	'image/jpeg',
	'image/png',
	'image/webp'
]);
