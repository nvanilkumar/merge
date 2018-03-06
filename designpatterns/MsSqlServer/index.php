<?php

$serverName = "mtcdb.cuzsb4nwew8a.us-east-2.rds.amazonaws.com";
$uid = "mtcdb";
$pwd = "mtcdb4app";
$databaseName = "MTC_Mobile_Dev";

$connectionInfo = array("UID" => $uid,
    "PWD" => $pwd,
    "Database" => $databaseName);

/* Connect using SQL Server Authentication. */
$conn = sqlsrv_connect($serverName, $connectionInfo);
if( $conn )
{
    echo "Connected";
}
else
{
    echo "<pre>";
    die( print_r( sqlsrv_errors(), true));
}
//var_dump($conn);exit;
$tsql = "SELECT * FROM users";

/* Execute the query. */

$stmt = sqlsrv_query($conn, $tsql);

if ($stmt) {
    echo "Statement executed.<br>\n";
} else {
    echo "Error in statement execution.\n";
    die(print_r(sqlsrv_errors(), true));
}

/* Iterate through the result set printing a row of data upon each iteration. */

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC)) {
//    print_r($row);exit;
    echo "Col1: " . $row[0] . "\n";
    echo "Col2: " . $row[1] . "\n";
    echo "Col3: " . $row[2] . "<br>\n";
    echo "-----------------<br>\n";
}

/* Free statement and connection resources. */
sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
