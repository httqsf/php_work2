<?php

$errors = [];
$review = [
    'title' => '',
    'author' => '',
    'status' => '未読',
    'score' => '',
    'impression' => ''
];

$title = "読書ログの登録";
$content = __DIR__ ."/views/new.php";
include 'views/layout.php';
