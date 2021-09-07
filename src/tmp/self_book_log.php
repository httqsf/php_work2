<?php

function validate($review)
{
    $errors = [];

    //書籍名が正しく入力されているかチェック
    if (!strlen($review['title'])){
        $errors['title'] = '書籍名を入力してください';
    } elseif (strlen($review['title']) > 255){
        $errors['title'] = '書籍名は255文字以内で入力してください';
    }

    //著者名が正しく入力されているかチェック
    if (!strlen($review['author'])) {
        $errors['author'] = '著者名を入力してください';
    } elseif (strlen($review['author']) <= 100){
        $errors['title'] = '100文字以内で入力してください';
    }

    //読書状況の入力チェック
    //下記コメントif文の書き方でも可能
    // if(!($review['status'] === '読了' || $review['status'] === '読んでいる' || $review['status'] === '未読')){
    //     $errors['status'] = '未読,読んでいる,読了で入力してください';
    // }

    if (!in_array($review['status'], ['読了', '読んでいる', '読了'], true)){
        $errors['status'] = '未読,読んでいる,読了で入力してください';
    }

    //評価が正しく入力されているかチェック
    if (1 > $review['score'] || 5 < $review['score']){
        $errors['score'] = '評価は１〜５の整数を入力してください';
    }

    //感想の入力チェック
    if(!strlen($review['impression'])){
        $errors['impression'] = '感想を入力してください';
    }elseif(strlen($review['impression']) > 1000){
        $errors['impression'] = '感想は1000文字以内で入力してください';
    }

    return $errors;
}

function createReview($link)
{
    $review = [];
    echo '読書ログを登録してください' . PHP_EOL;
    echo '書籍名：';
    $review['title'] = trim(fgets(STDIN));

    echo '著者名：';
    $review['author'] = trim(fgets(STDIN));

    echo '読書状況(未読,読んでいる,読了)：';
    $review['status'] = trim(fgets(STDIN));

    echo '評価(5点満点の整数)：';
    $review['score'] = (int) trim(fgets(STDIN));

    echo '感想：';
    $review['impression'] = trim(fgets(STDIN));

    $validated = validate($review);
    if (count($validated) > 0) {
        foreach ($validated as $error) {
            echo $error .PHP_EOL;
        }
        return;
    }

$sql = <<<EOT
    INSERT INTO reviews (
    title,
    author,
    status,
    score,
    impression
    ) VALUES (
    "{$review['title']}",
    "{$review['author']}",
    "{$review['status']}",
    "{$review['score'] }",
    "{$review['impression']}"
    )
EOT;

    $result = mysqli_query($link, $sql);
    if ($result){
                echo 'データを追加しました' . PHP_EOL;
    }else {
        echo 'Error: データの追加に失敗しました' .PHP_EOL;
        echo 'Debugging error: ' . mysqli_error($link) . PHP_EOL . PHP_EOL;
    }
}

function listReview()
{
    echo '読書ログを表示します' . PHP_EOL;

    $link = mysqli_connect('db', 'book_log', 'pass', 'book_log');
    $sql = 'SELECT title, author, status, score, impression FROM reviews';
    $results = mysqli_query($link, $sql);

    while ($reviews = mysqli_fetch_assoc($results)) {
        echo '書籍名：' . $reviews['title'] . PHP_EOL;
        echo '著者名：' . $reviews['author'] . PHP_EOL;
        echo '読書状況：' . $reviews['status'] . PHP_EOL;
        echo '評価：' . $reviews['score'] . PHP_EOL;
        echo '感想：' . $reviews['impression'] . PHP_EOL;
        echo '--------------'. PHP_EOL;
    }

    mysqli_free_result($results);
}


function dbConnect()
{
        //mysqli_connect()は接続に失敗した場合FALSEを返す
        $link = mysqli_connect('db', 'book_log', 'pass', 'book_log');
        if (!$link) {

            echo 'Error：データベースに接続できませんでした' .PHP_EOL;
            echo 'Debugging error:' . mysqli_connect_error() .PHP_EOL;
            exit;
        }
        echo 'データベースに接続できました' . PHP_EOL;
        return $link;
}

    $reviews = [];
    $link = dbConnect();

    while(true) {

        echo '1. 読書ログを登録' .PHP_EOL;
        echo '2. 読書ログを表示' .PHP_EOL;
        echo '9. アプリケーションを終了' .PHP_EOL;
        echo '番号を選択してください（1,2,9）：';
        $num = trim(fgets(STDIN));

        if ($num === '1'){
            //今後の使い勝手を良くするためにcreateReviewだけを関数としてまとめていた。
            //createReviewを受け取ったあとに$reviewsに代入している。
            createReview($link);
        } elseif ($num === '2'){
            //関数名(引数);
            listReview();
        } elseif ($num === '9'){
            mysqli_close($link);
            echo 'アプリケーションを終了します' . PHP_EOL;
            break;
        }
    }
