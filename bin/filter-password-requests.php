<?php
$sql_conn = mysqli_connect('localhost', 'root', 'toor', 'jesterous');

$query = "DELETE FROM password_recovery  WHERE TIMESTAMPDIFF(MINUTE,time_requested,NOW()) > 30;";
mysqli_query($sql_conn, $query);

$logQuery = "INSERT INTO logs (date, location ,message) VALUES (now(), 'Cron Job (Password Filter)', 'Just Filtered Requests')";
mysqli_query($sql_conn, $logQuery);
