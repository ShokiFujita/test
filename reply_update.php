<?php
    $delete_number = $_POST["delete_number"];
    require("db_connect.php");

    $name = $_POST["name"];
    $subject = $_POST["subject"];
    $message = $_POST["message"];
    $email = $_POST["email"];
    $url = $_POST["url"];
    $text_color = $_POST["text_color"];
    $delete_key = $_POST["delete_key"];

    
    if(!empty($_POST["upload_img"])){
        $image_path = $_POST["upload_img"];
    }

    // imageフォルダにアクセスしないといけないので「$upload_path = "./image/" . $filename;」の処理が必要となる
    if(!empty($_FILES["upload_img"]["name"])){
        $filename = uniqid(mt_rand(), true);
        $filename = $_FILES["upload_img"]["name"];             
        $upload_path = "./image/" . $filename;
        move_uploaded_file($_FILES["upload_img"]["tmp_name"],$upload_path);
        $image_path = $upload_path;
    }
    
    
    //  チェック時画像削除
    if(!empty($_POST["delete_image"])){
        $sql = "UPDATE replies SET name = '$name', subject = '$subject', message = '$message', image_path = NULL, email = '$email', url = '$url', text_color = '$text_color', delete_key = '$delete_key' WHERE id = $delete_number";
    }
    else{
        if(!empty($image_path)){
            $sql = "UPDATE replies SET name = '$name', subject = '$subject', message = '$message', image_path = '$image_path', email = '$email', url = '$url', text_color = '$text_color', delete_key = '$delete_key' WHERE id = $delete_number";
        } 
        else{
            $sql = "UPDATE replies SET name = '$name', subject = '$subject', message = '$message', email = '$email', url = '$url', text_color = '$text_color', delete_key = '$delete_key' WHERE id = $delete_number";

        }
    }
    $result = mysqli_query($link,$sql);

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        ul{
            display: flex;
            list-style: none;
        }
        li{
            margin-right: 1%;
        }
    </style>
</head>
<body>
    <h1>課題掲示板 Sample</h1>
    <nav>
        <ul>
            <li><a href="./top.php">一覧(新規投稿)</a></li>
            <li><a href="./search.php">ワード検索</a></li>
            <li><a href="#">使い方</a></li>
            <li><a href="#">携帯へURLを送る</a></li>
            <li><a href="#">管理</a></li>
        </ul>
    </nav>
    <h2>編集完了</h2>
    <a href="./top.php">戻る</a>

    
</body>
</html>