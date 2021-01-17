<?php

/**
 * @name:       Samp Front
 * @version:    0.5.1
 * @author:     EOussama (eoussama.github.io)
 * @license     MIT
 * @source:     github.com/EOussama/samp-front
 * 
 * This script is responsible for creating news articles.
 */

// Setting the header.
header('Content-type: application/json');

// Requiring the configurations.
require_once "./../../config/config.php";

// Loading the website's configurations.
$config = unserialize(CONFIG);

// Requiring all dependencies.
require_once pathfy('models', 'Database.php');
require_once pathfy('models', 'News.php');

try {
	if (isset($_POST['title']) && isset($_POST['body'])) {
		$title  = htmlspecialchars(strip_tags($_POST['title']));
		$body   = htmlspecialchars($_POST['body']);

		$db = new Database(
			$config['database']['host'],
			$config['database']['port'],
			$config['database']['name'],
			$config['database']['user'],
			$config['database']['pass']
		);
		$conn = $db->connect();
		$news = new News($conn, $config['database']['newsTable']);

		if (!$news->create($title, $body)) {
			throw new Exception("News article was not created.");
		} else {
			$data = array("" => "");
		}
	} else {
		throw new Exception("Insufficient data recieved.");
	}
} catch (Exception $e) {
	$data = array("error" => htmlspecialchars(strip_tags($e->getMessage())));
} finally {
	echo json_encode($data);
}
