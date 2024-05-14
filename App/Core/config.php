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
	define('DB_NAME', 'yoanmen_arcadia');
	define('DB_HOST', 'mysql-yoanmen.alwaysdata.net');
	define('DB_USER', 'yoanmen');
	define('DB_PASSWORD', '115225335');

	define('ROOT', 'https://yoanmen.alwaysdata.net');

	define('COUCHDB_URL', "http://yoanmen:115225335@couchdb-yoanmen.alwaysdata.net:5984/yoanmen_arcadia");
}

define('MAIL', "yoanmen@alwaysdata.net");
define('APP_NAME', "Zoo Arcadia");
define('APP_DESC', "Arcadia Zoo official website");
define('COUNT_CONNECTION', 4);

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
