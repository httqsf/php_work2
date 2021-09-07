<?php

require "mysqli.php" . "/../lib/mysqli.php";

function dropTable($link)
{
    $dropTables = 'DROP TABLE IF EXISTS reviews;';
    $result = mysqli_query($link, $dropTables);
    if ($result){
                echo 'テーブルを削除しました' . PHP_EOL;
    }else {
        echo 'Error: テーブルの削除に失敗しました' .PHP_EOL;
        echo 'Debugging error: ' . mysqli_error($link) . PHP_EOL . PHP_EOL;
    }
}

function createTable($link)
{
    $createTable = <<<EOT
    CREATE TABLE reviews(
        id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) ,
        author VARCHAR(100),
        status VARCHAR(20),
        score INTEGER,
        impression VARCHAR(1000),
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) DEFAULT CHARACTER SET = utf8mb4;
EOT;
    $result = mysqli_query($link, $createTable);
    if ($result){
                echo 'テーブルを作成しました' . PHP_EOL;
    }else {
        echo 'Error: テーブルの作成に失敗しました' .PHP_EOL;
        echo 'Debugging error: ' . mysqli_error($link) . PHP_EOL . PHP_EOL;
    }
}

$link = dbConnect();
dropTable($link);
createTable($link);
mysqli_close($link);
