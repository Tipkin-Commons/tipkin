<?php
require_once (__DIR__ . "/../apps/database.config.php");

class PDOFactory
{

	public static function getMysqlConnexion()
	{
		global $tipkin_host, $tipkin_dbname, $tipkin_username, $tipkin_password;
		$db = new PDO('mysql:host=' . $tipkin_host . ';dbname=' . $tipkin_dbname, $tipkin_username, $tipkin_password);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		return $db;
	}
}
?>