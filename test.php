<?php

require_once 'db_connect.php';

function testDB()
{
    global $dbconn;
    $result = pg_query($dbconn, "SELECT * FROM users");
    if ($result) {
        while ($arr = pg_fetch_assoc($result)) {
            if (isset($arr['phone'])) {
                print ($arr['phone']);
                print "<br/>";
            }
        }
    } else {
        echo "Query failed.";
    }
}

testDB();
