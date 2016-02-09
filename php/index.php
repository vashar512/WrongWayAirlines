<?php
	$server_credentials = file_get_contents('./server_credentials.txt');
	
	$file_contents_array = explode(PHP_EOL, $server_credentials);

	$server = $file_contents_array[0];
	$hostname = $file_contents_array[1];
	$password = $file_contents_array[2];

	$connection = mysql_connect($server, $hostname, $password);
	$database_connection = mysql_select_db('vha6', $connection);

	if(!$connection) {
		die('Could not connect: '.$connection);
	}

	if(!$database_connection) {
		die('Could not connect to database: '.$database_connection);
	}

	$username = mysql_real_escape_string($_GET['username']);
	$password = mysql_real_escape_string($_GET['password']);

	if (isset($username) && isset($password)) {
		$login = new login();
		$correctLoginCredentials = $login->loginUsingCredentials($username, $password);
		
		if($correctLoginCredentials) {
			echo '<br/> Correct Login Credentials. ';
		}
	}

	class login {
		function __construct() {
			echo 'Hi you are trying to login';
		}

		function loginUsingCredentials($username, $password) {
			$query = 'SELECT firstName, lastName FROM `administratorLogin` WHERE username="'.$username.'" AND password="'.md5("$password").'";';
			$result = mysql_query($query);

			if(!$result) {
				echo "DB Error, could not query the database\n";
				echo 'MySQL Error: ' . mysql_error();
				return false;
			}

			if (mysql_num_rows($result) != 0) {
				while ($record = mysql_fetch_assoc($result)) {
					$first = $record["firstName"];
					$last = $record["lastName"];
					echo '<br/>' . $first;
					echo '<br/>' . $last;
				}

				mysql_free_result($result);
				return true;
			}

			return false;
		}
	};

?>

