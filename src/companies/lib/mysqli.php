<?php

require __DIR__ . '/../../vendor/autoload.php';

function dbConnect()
{
        //mysqli_connect()は接続に失敗した場合FALSEを返す
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../..');
    $dotenv->load();

    $dbHost = $_ENV['DB_HOST'];
    $dbUsername = $_ENV['DB_USERNAME'];
    $dbPassword = $_ENV['DB_PASSWORD'];
    $dbDatabase = $_ENV['DB_DATABASE'];

    $link = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbDatabase);
    if (!$link) {
        echo 'Error：データベースに接続できませんでした' .PHP_EOL;
        echo 'Debugging error:' . mysqli_connect_error() .PHP_EOL;
        exit;
    }
    // echo 'データベースに接続できました' . PHP_EOL;
    return $link;
}
