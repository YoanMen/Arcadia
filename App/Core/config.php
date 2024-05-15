<?php
if ($_SERVER['SERVER_NAME'] == 'arcadia') {

	/** database config LOCALHOST **/

	define('DB_NAME', 'arcadia');
	define('DB_HOST', 'localhost');
	define('DB_USER', '');
	define('DB_PASSWORD', '');

	define('ROOT', 'http://arcadia');

	define('COUCHDB_URL', "http://USER:PASSWORD@localhost:5984/arcadia");
} else {

	/** database config DEPLOYMENT**/

	define('DB_NAME', '');
	define('DB_HOST', '');
	define('DB_USER', '');
	define('DB_PASSWORD', '');

	define('ROOT', '');

	define('COUCHDB_URL', "");
}


define('APP_NAME', "Zoo Arcadia");
define('APP_DESC', "Arcadia Zoo official website");


/** Disable account after count **/
define('COUNT_CONNECTION', 4);


define('MAIL', "arcadiacontact@mailinator.com");


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
