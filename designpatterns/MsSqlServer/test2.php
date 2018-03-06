<?php
$server = "mtcdb.cuzsb4nwew8a.us-east-2.rds.amazonaws.com";
$user = "mtcdb";
$pass = "mtcdb4app";
$database = "MTC_Mobile_Dev";



//$serverName = "mtcdb.cuzsb4nwew8a.us-east-2.rds.amazonaws.com";
//$uid = "mtcdb";
//$pwd = "mtcdb4app";
//$databaseName = "MTC_Mobile_Dev";

try {
    $pdo = new \PDO(
        sprintf(
         "dblib:host=%s;dbname=%s",
         $server,
         $database
        ),
         $user,
         $pass
    );
     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
     echo "There was a problem connecting. " . $e->getMessage();
}

$query = "SELECT * FROM users";
$statement = $pdo->prepare($query);
$statement->execute();
$results = $statement->fetchAll(PDO::FETCH_ASSOC);

var_dump($results);