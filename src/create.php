<?php

// require_once 'mysqli.php';

// 1.HTTPメソッドがPOSTだったら
// 2.POSTされた会社情報を変数に格納する
// 3.バリデーションする
// 4.データベースに接続する
// 5.データベースにデータを登録する
// 6.データベースとの接続を切断する

require_once __DIR__ . "/lib/mysqli.php";

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
    } elseif (strlen($review['author']) > 100){
        $errors['title'] = '100文字以内で入力してください';
    }
    //読書状況の入力チェック
    if (!in_array($review['status'], ['未読', '読んでる', '読了'])) {
        $errors['status'] = '読書状況は「未読」「読んでる」「読了」のいずれかを選択してください';
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

function createReview($link, $review)
{
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
    "{$review['score']}",
    "{$review['impression']}"
)
EOT;
    $result = mysqli_query($link, $sql);
    if(!$result){
        error_log('Error: fail to create review');
        error_log('Debugging Error: ' . mysqli_error($link));
    }
}

// 1.HTTPメソッドがPOSTだったら
if ($_SERVER['REQUEST_METHOD'] === 'POST'){

    //  * status（読書状況）が未入力のときに $_POST['status'] を呼び出すとエラーになるためその対策として処理を入れている
    //  * エラーになる理由は、ラジオボタンがチェックされていないとデータが送信されず、$_POST 内に status というキーが存在しないにも関わらず status キーにアクセスしようとするから
    //  * status が未入力のときにエラーにならないのであれば他の対処方法でも良い（読書状況のラジオボタンにデフォルトでチェックを入れておくなど）
    $status = '';
    if (array_key_exists('status', $_POST)) {
        $status = $_POST['status'];
    }


// 2.POSTされた会社情報を変数に格納する
    $review = [
        'title' => $_POST['title'],
        'author' => $_POST['author'],
        'status' => $status,
        'score' => $_POST['score'],
        'impression' => $_POST['impression']
    ];
    // 3.バリデーションする
    $errors = validate($review);
    // 3.(1)バリデーションにエラーがなければ下の処理を進める
    if (!count($errors)){
    // 4.データベースに接続する
    $link = dbConnect();
    // 5.データベースにデータを登録する
    createReview($link, $review);
    // 6.データベースとの接続を切断する
    mysqli_close($link);
    header("Location: index.php");
    }
}

$title = '読書ログ登録';
$content = __DIR__ . "/views/new.php";
include __DIR__ . '/views/layout.php';
