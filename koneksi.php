<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'saw');

try {
	// setting database untuk mysql
	$pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
	
	// Setting Error Mode ke Exception
	$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	
	// Setting Error Mode ke mode Warning
	# $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
	
} 
catch(PDOException $e) {
	die("Ups, tidak dapat terkoneksi ke database<br><br>".$e->getMessage());
}