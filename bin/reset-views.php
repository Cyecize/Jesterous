<?php
$sql_conn = mysqli_connect('localhost', 'root', 'toor', 'jesterous');
$query1 = "UPDATE articles AS a SET a.daily_views = 0 WHERE a.daily_views < 10";
$query2 = "UPDATE articles AS a SET a.daily_views = 9 WHERE a.daily_views >= 10";

mysqli_query($sql_conn, $query1 );
mysqli_query($sql_conn, $query2 );

$logQuery = "INSERT INTO logs (date, location ,message) VALUES (now(), 'Cron Job (Article Resetter)', 'Just reset articles')";
mysqli_query($sql_conn, $logQuery);

