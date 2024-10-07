<?php
    session_start();

    if(!empty($_POST["reset"])){
        header("Location: reply.php", true, 307);
        exit;
    }

    // 入力があった場合一旦セッションに保存
    $_SESSION["original"]["name"] = $_POST["name"];
    $_SESSION["original"]["subject"] = $_POST["subject"];
    $_SESSION["original"]["message"] = $_POST["message"];
    $_SESSION["original"]["email"] = $_POST["email"];
    $_SESSION["original"]["url"] = $_POST["url"];
    $_SESSION["original"]["text_color"] = $_POST["text_color"];
    $_SESSION["original"]["delete_key"] = $_POST["delete_key"];

    // 必須項目の確認
    if(empty($_POST["name"])){
        $_SESSION["flash"]["name"] = "お名前が未入力です";
    }
    if(empty($_POST["message"])){
        $_SESSION["flash"]["message"] = "メッセージが未入力です";
    }
    if(preg_match("/^[a-zA-Z0-9]+$/", $_POST["message"])){
        $_SESSION["flash"]["message"] = "本文が英数字のみの場合は書き込めません";
    }
    if(strlen($_POST["delete_key"]) <= 3 || strlen($_POST["delete_key"]) >= 8){
        $_SESSION["flash"]["delete_key"] = "削除キーは半角英数字のみ4~8文字で入力してください";
    }


    // 必須項目が入力されていなければページへリダイレクト
    if(empty($_POST["name"]) || empty($_POST["message"]) || empty($_POST["delete_key"]) || strlen($_POST["delete_key"]) <= 3 || strlen($_POST["delete_key"]) >= 8 || preg_match("/^[a-zA-Z0-9]+$/", $_POST["message"])){
        header("Location: ./reply.php", true, 307);
        exit;
    }


    // プレビューにチェックが入っていなければthanks.phpへ移行
    if(empty($_POST["preview"])){
        header("Location: ./reply_thanks.php", true, 307);
        exit;
    }
?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>確認画面</title>
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
    <form action="./reply_thanks.php" method="post" enctype="multipart/form-data">
        
        <?php
            $name = $_POST["name"];
            $subject = $_POST["subject"];
            $message = $_POST["message"];
            $email = $_POST["email"];
            $url = $_POST["url"];
            $text_color = $_POST["text_color"];
            $delete_key = $_POST["delete_key"];
            $delete_number = $_POST["reply_number"];
        ?>
        <table border="1">
            <tr>
                <td>名前</td>
                <td><?php echo $name; ?></td>
            </tr>
            <tr>
                <td>件名:</td>
                <td>
                    <?php
                        echo $subject;
                    ?>
                </td>
            </tr>
            <tr>
                <td>メッセージ:<br/>(文字色)<font color ="<?php echo $text_color; ?>">■</font></td>
                <td><?php echo nl2br($message); ?></td>
            </tr>
            <tr>
                <td>画像:</td>
                <td>
                    <?php
                        if(!empty($_FILES["upload_img"]["name"])){
                            $filename = uniqid(mt_rand(), true);
                            $filename = $_FILES["upload_img"]["name"];             
                            $upload_path = "./image/" . $filename;
                            move_uploaded_file($_FILES["upload_img"]["tmp_name"],$upload_path);
                            ?>
                            <img src="<?php echo $upload_path; ?>" alt="upload_img"  width="60%" height="60%">
                            <input type="hidden" name="upload_img" value="<?php echo $upload_path; ?>">
                            <?php
                        }else{
                            echo "添付画像なし";
                        }
                    ?>
                </td>
            </tr>
            <tr>
                <td>メールアドレス:</td>
                <td><?php echo $email; ?></td>
            </tr>
            <tr>
                <td>ホームページ:</td>
                <td><?php echo $url; ?></td>
            </tr>
            <tr>
                <td>編集/削除キー:</td>
                <td><?php echo $delete_key; ?></td>
            </tr>
        </table>
        <input type="submit" name="reset" value="戻って修正する">
        <input type="submit" value="このまま投稿する">

        <div>
            <input type="hidden" name="name" value="<?php echo $name; ?>">
            <input type="hidden" name="subject" value="<?php echo $subject; ?>">
            <input type="hidden" name="message" value="<?php echo $message; ?>">
            <input type="hidden" name="email" value="<?php echo $email; ?>">
            <input type="hidden" name="url" value="<?php echo $url; ?>">
            <input type="hidden" name="text_color" value="<?php echo $text_color; ?>">
            <input type="hidden" name="delete_key" value="<?php echo $delete_key; ?>">
            <input type="hidden" name="reply_number" value="<?php echo $delete_number; ?>">
        </div>
    </form>
</body>
</html>