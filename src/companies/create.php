<?php
// 記述する手順
// 1.HTTPメソッドがPOSTだったら
// 2.POSTされた会社情報を変数に格納する
// 3.バリデーションする
// 4.データベースに接続する
// 5.データベースにデータを登録する
// 6.データベースとの接続を切断する

require_once __DIR__ . '/lib/mysqli.php';

function validate($company)
{
    $errors = [];

    //会社名のバリデーション
    //もし会社名が入力されていない場合'会社名を入力してください'の表示する
    if (!strlen($company['name'])){
        $errors['name'] = '会社名を入力してください';
    } elseif (strlen($company['name']) > 255) {
        $errors['name'] = '会社名は255文字以内で入力しください';
    }
    //設立日のバリデーション
    $dates = explode("-", $company['establishment_date']);
    if (!strlen($company['establishment_date'])){
        $errors['establishment_date'] = '設立日を入力してください';
    } elseif (count($dates) !== 3){
        $errors['establishment_date'] = '設立日を正しい形式で入力してください';
    } elseif (!checkdate($dates[1], $dates[2], $dates[0])) {
        $errors['establishment_date'] = '設立日を正しい日付で入力してください';
    }

    //代表者のバリデーション
    if (!strlen($company['founder'])){
        $errors['founder'] = '代表者を入力してください';
    } elseif (strlen($company['founder']) > 255) {
        $errors['founder'] = '代表者を100文字以内で入力しください';
    }

    return $errors;
}

function createCompany($link, $company)
{
    $sql = <<< EOT
INSERT INTO companies (
    name,
    establishment_date,
    founder
) VALUES (
    "{$company['name']}",
    "{$company['establishment_date']}",
    "{$company['founder']}"
)
EOT;
    $result = mysqli_query($link, $sql);
    if ($result){
        error_log('Error: fail to create company');
        error_log('Debugging Error: ' . mysqli_error($link));
    }
}

// 1.HTTPメソッドがPOSTだったら

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 2.POSTされた会社情報を変数に格納する
    // var_export($_POST);
    $company = [
        'name' => $_POST['name'],
        'establishment_date' => $_POST['establishment_date'],
        'founder' => $_POST['founder']
    ];
    // 3.バリデーションする
    $errors = validate($company);
    // 3.(1)バリデーションエラーがなければそのまま処理を進める
    if (!count($errors)) {
    // 4.データベースに接続する
    $link = dbConnect();
    // 5.データベースにデータを登録する
    createCompany($link, $company);
    // 6.データベースとの接続を切断する
    mysqli_close($link);
    header("Location: index.php");
    }

    // 3.(2)エラーがあればエラーメッセージを表示して登録画面に戻る
}

include 'views/new.php';

