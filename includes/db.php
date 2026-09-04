<?php
/**
 * Database connection for Abigail Beauty Bar.
 *
 * Update the four constants below to match your local setup
 * (XAMPP/MAMP default values are already filled in).
 */

define('DB_HOST', 'localhost');
define('DB_NAME', 'abigail_beauty_bar');
define('DB_USER', 'root');
define('DB_PASS', '');


function get_db_connection(): mysqli
{
    static $connection = null;

    if ($connection === null) {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $connection->set_charset('utf8mb4');
    }

    return $connection;
}
