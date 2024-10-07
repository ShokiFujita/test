<?php
    /* 送信をを押した時、sendの値が$_POST["send"]に入り削除キーと一致していれば下記処理を実行*/

    if(!empty($_POST["send"])){
        $delete_number = $_POST["delete_number"];
        
        if($_POST["delete_pass"] === $_POST["delete_key"]){
            require("db_connect.php");
            $result = mysqli_query($link, "DELETE FROM boards WHERE id = '$delete_number'");    
    
            header("Location: ./top.php");
            exit;        
        }

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
            border: 1px solid #0000FF;
            width: 700px;
            margin-bottom: 30px;
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
    <p>編集/削除キーを入力し、[送信]してください。</p>
    <div class="border">
        <?php
            require("db_connect.php");
            $delete_number = $_POST["delete_number"];
            $result = mysqli_query($link,"SELECT delete_key FROM boards WHERE id = $delete_number");
            while ($row = mysqli_fetch_assoc($result)){
                $delete_key = $row["delete_key"];
            }
        ?>
        <form action="check_delete_pass.php" method="post" enctype="multipart/form-data">
            <table>
                <tr>
                    <td>編集/削除キー:</td>
                    <td><input type="password" name="delete_pass"></td>
                    <td><input type="submit" name="send" value="送信"></td>
                </tr>
            </table>
            <input type="hidden" name="delete_key" value="<?php echo $delete_key; ?>"> 
            <input type="hidden" name="delete_number" value="<?php echo $delete_number; ?>"> 
        </form>
    </div>


</body>
</html>