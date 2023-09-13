<?php
/**
 * connect.inc.php
 *
 * Details to connect to the database
 * @author Florian Leitner <florian.leitner AT student DOT tugraz DOT at>
 * @version 1.0
 * @package YPLP
 * @copyright (c) 2009 Yeast Genetics and Molecular Biology Group University Graz
 */

// force to use https
$ssl_encryption = 'no';

$db_host = getenv('DB_HOST');
$db_port = getenv('DB_PORT');
$db_username = getenv('DB_USERNAME');
$db_password = getenv('DB_PASSWORD');
$db_name = getenv('DB_NAME');

$conn_string = "host=$db_host port=$db_port dbname=$db_name user=$db_username password=$db_password";
$db_connection = pg_connect($conn_string) or die("<html><body><h1>The service is temporarily unavailable</h1></body></html>");

?>
