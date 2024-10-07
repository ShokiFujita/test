<?php
    // unset($_SESSION["original"]["message"]);を使用したいためsession開始
    session_start();

    if(!empty($_POST["reset"])){
        header("Location: ./reply.php", true, 307);
        exit;
    }
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>投稿完了画面</title>
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
    <h2>投稿完了!!</h2>
    <a href="./top.php">戻る</a>

    <?php
        require("db_connect.php");

        $reply_number = $_POST["reply_number"];
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


        if(!empty($image_path)){
            $sql = "INSERT INTO replies(board_id, name, subject, message, image_path, email, url, text_color, delete_key, created_at) VALUE ($reply_number, '$name', '$subject', '$message', '$image_path', '$email', '$url', '$text_color', $delete_key, now())";
        }   else{
            $sql = "INSERT INTO replies(board_id, name, subject, message, email, url, text_color, delete_key, created_at) VALUE ($reply_number, '$name', '$subject', '$message', '$email', '$url', '$text_color', $delete_key, now())";
        }

        $result = mysqli_query($link,$sql);

        // topへ戻った時valueを削除したいため記載
        unset($_SESSION["original"]["subject"], $_SESSION["original"]["message"]);

    ?>

</body>
</html>