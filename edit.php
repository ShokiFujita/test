<?php
    require("db_connect.php");
    $delete_number = $_POST["delete_number"];
    $result = mysqli_query($link, "SELECT * FROM boards WHERE id = $delete_number");
    while($row = mysqli_fetch_assoc($result)){
        $name = $row["name"];
        $subject = $row["subject"];
        $message = $row["message"];
        $image_path = $row["image_path"];
        $email = $row["email"];
        $url = $row["url"];
        $text_color = $row["text_color"];
        $delete_key = $row["delete_key"];
    }
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
        .border{
            border: 1px solid #00CCFF;
            width: 700px;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <h1>編集課題掲示板 Sample</h1>
    <nav>
        <ul>
            <li><a href="./top.php">一覧(新規投稿)</a></li>
            <li><a href="./search.php">ワード検索</a></li>
            <li><a href="#">使い方</a></li>
            <li><a href="#">携帯へURLを送る</a></li>
            <li><a href="#">管理</a></li>
        </ul>
    </nav>
    <div class="border">
        <form action="./update.php" method="post" enctype="multipart/form-data" border="2" bordercolor="#00CCFF">
            <table>
                <tr>
                    <td>名前</dh>
                    <td><input type="text" name="name" value="<?php echo $name; ?>"></td>
                </tr>
                <tr>
                    <td>件名</td>
                    <td><input type="text" name="subject" value="<?php echo $subject; ?>"></td>
                </tr>
                <tr>
                    <td>メッセージ</td>
                </tr>
                <tr>
                    <td><textarea name="message"><?php echo $message; ?></textarea></td>
                </tr>
                <tr>
                    <td>画像</td>
                    <td><input type="file" name="upload_img"valur="<?php if(!empty($image_path)){echo $image_path; }?>"></td>
                </tr>
                <tr>
                    <td colspan="2">※新しい画像をアップロードすると、古い画像は自動的に削除されます。</td>
                </tr>
                <tr>
                    <td>
                        ▽現在の画像(
                        <input type="checkbox" name="delete_image">この画像を削除する)
                    </td>
                </tr>
                <?php
                if(!empty($image_path)){
                    ?>
                    <tr>
                        <td colspan="2"><img src="<?php echo $image_path; ?>" alt="image"  width="80%" height="80%"></td>
                    </tr>
                    <?php
                }
                ?>
                <tr>
                    <td>メールアドレス</td>
                    <td><input type="email" name="email" value="<?php echo $email; ?>"></td>
                </tr>
                <tr>
                    <td>URL</td>
                    <td><input type="text" name="url" value="<?php echo $url; ?>"></td>
                </tr>
                <tr>
                    <td>文字色</td>
                    <td>
                        <input type="radio" name="text_color" value="#FF0000" <?php if($text_color === "#FF0000"){echo "checked";}?>><font color="#FF0000">■</font>
                        <input type="radio" name="text_color" value="#008000" <?php if($text_color === "#008000"){echo "checked";}?>><font color="#008000">■</font>
                        <input type="radio" name="text_color" value="#0000FF" <?php if($text_color === "#0000FF"){echo "checked";}?>><font color="#0000FF">■</font>
                        <input type="radio" name="text_color" value="#FF00FF" <?php if($text_color === "#FF00FF"){echo "checked";}?>><font color="#FF00FF">■</font>
                        <input type="radio" name="text_color" value="#800080" <?php if($text_color === "#800080"){echo "checked";}?>><font color="#800080">■</font>
                        <input type="radio" name="text_color" value="#FF9966" <?php if($text_color === "#FF9966"){echo "checked";}?>><font color="#FF9966">■</font>
                        <input type="radio" name="text_color" value="#000099" <?php if($text_color === "#000099"){echo "checked";}?>><font color="#000099">■</font>
                        <input type="radio" name="text_color" value="#000000" <?php if($text_color === "#000000"){echo "checked";}?>><font color="#000000">■</font>
                    </td>
                </tr>
                <tr>
                    <td>削除キー</td>
                    <td><input type="password" name="delete_key" value="<?php echo $delete_key;?>">(半角英数字のみで4~8文字)</td>
                </tr>
                <tr>
                    <td colspan="2">※編集時はプレビュー機能を使えません</td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <input type="submit" value="編集">
                    </td>
                </tr>
            </table>
            <input type="hidden" name="delete_number" value="<?php echo $delete_number;?>">
        </form>
    </div>
</body>
</html>