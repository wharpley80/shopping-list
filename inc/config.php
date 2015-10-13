<?php

// these two constants are used to create root-relative web addresses
// and absolute server paths throughout all the code

define("LOCALHOST", $_SERVER['SERVER_NAME'] == "localhost");

if (LOCALHOST) {
	define("BASE_URL","/shopping-list/");
	define("ROOT_PATH",$_SERVER["DOCUMENT_ROOT"] . "/shopping-list/");

	define("DB_HOST","localhost");
	define("DB_NAME","grocery_list");
	define("DB_PORT","3306"); 
	define("DB_USER","root");
	define("DB_PASS","root");
} else {
	define("DB_HOST","localhost");
	define("DB_NAME","grocery_list");
	define("DB_PORT","3306"); // default: 3306
	define("DB_USER","wharpley1980");
	define("DB_PASS","wotco712");
}