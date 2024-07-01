<?php
$host = '127.0.0.1';
$dbname = 'bot';
$user = 'postgres';
$password = 'postgres';

$conn_string = "host=$host dbname=$dbname user=$user password=$password";
$dbconn = pg_connect($conn_string);

if ($dbconn) {
    echo "Connected to the PostgreSQL database successfully!";
} else {
    echo "Failed to connect to the PostgreSQL database.";
}
?>