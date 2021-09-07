<?php

function dbConnect()
{
    $link = mysqli_connect('db', 'book_log', 'pass', 'book_log');
    if (!$link) {
        echo 'error：データベースに接続できませんでした' . PHP_EOL;
        echo 'Debugging error：' . mysqli_connect_error($link) . PHP_EOL;
        exit;
    }
    echo 'データベースに接続できました' . PHP_EOL;
    return $link;
}

function createMemo()
{
    echo 'メモを登録してください' .PHP_EOL;
    echo 'タイトル：';
    $title = trim(fgets(STDIN));
    echo 'メモ本文：';
    $memo = trim(fgets(STDIN));
    echo '日付：';
    $day = trim(fgets(STDIN));
    return
    [
        'title' => $title,
        'memo' => $memo,
        'day' => $day,
    ];

    echo '登録が完了しました' .PHP_EOL . PHP_EOL;
}

function listMemo($notes)
{
    echo 'メモを表示します' . PHP_EOL;
    foreach( $notes as $note){
        echo 'タイトル：' . $note['title'] . PHP_EOL;
        echo '本文：' . $note['memo'] . PHP_EOL;
        echo '日付：' . $note['day'] . PHP_EOL;
        echo '-----------'. PHP_EOL;
    }
}

$notes = [];
$link = dbConnect();

while(true){
    echo '1：メモを登録する' .PHP_EOL;
    echo '2：メモを閲覧する' .PHP_EOL;
    echo '9：アプリを終了する' .PHP_EOL;
    echo '番号を選択してください(1, 2, 9)：';
    $num = trim(fgets(STDIN));

    if($num === '1'){
        $notes[] = createMemo();
        }elseif($num === '2'){
        listMemo($notes);
        }elseif($num === '9'){
        break;
        }
}
