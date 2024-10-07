<?php
    $check_type = "and";

    if(!empty($_POST["check_type"])){
        $check_type = $_POST["check_type"];
    }

    if(!empty($_POST["search_word"])){
        $search_word = explode(" ", $_POST["search_word"]);
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
        .buttom{
            display:flex
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
    <p>検索語を入力してください。</p>
    <div class="border">
        <form action="" method="post" enctype="multipart/form-data">
            <table>
                <tr>
                    <td>検索語:</td>
                    <td><input type="text" name="search_word" value="<?php if(!empty($search_word)){foreach($search_word as $words){echo $words . "\n";}}?>"></td>
                    <td><input type="radio" name="check_type" value="or" <?php if(empty($_POST["check_type"]) or $check_type === "or") {echo "checked";} ?> >OR</td>
                    <td><input type="radio" name="check_type" value="and" <?php if(!empty($_POST["check_type"]) and $check_type === "and") {echo "checked";} ?> >AND</td>
                </tr>
                <tr>
                    <td><input type="submit" name="search_button" value="検索開始"></td>
                </tr>
            </table>
        </form>
    </div>
    
    <?php
    require("db_connect.php");

    if(!empty($_POST["search_word"]) and $_POST["check_type"] ===  "or"){
        foreach($search_word as $words){
        
            // or検索
            $search_sql = mysqli_query($link,"SELECT id, name, subject, message, image_path, email, url, text_color, created_at FROM boards WHERE message LIKE '%$words%'
                                              UNION
                                              SELECT id, name, subject, message, image_path, email, url, text_color, created_at FROM replies WHERE message LIKE '%$words%' ORDER BY created_at DESC;");

            while ($row = mysqli_fetch_assoc($search_sql)){
                
                
                echo "<div class=\"border\">";
                
                    // id,delete_key確認用
                    // echo "id {$row["id"]}<br/>";
                    // echo "delete_key {$row["delete_key"]}<br/>";
                    echo "<p><font color=\"#FF0000\">{$row["subject"]}</font>- ";
                    if(!empty($row["url"])){
                        echo "<a href=\"mailto:{$row["email"]}\">{$row["name"]}</a> <a href=\"{$row["url"]}\">URL</a></p>" ;
                        echo "<p><font color=\"#663333\">{$row["created_at"]}</font></p>";
                    }
                    else{
                        echo "<font color=\"#000000\">{$row["name"]}</font></p>";
                        echo "<p><font color=\"#663333\">{$row["created_at"]}</font></p>";
                    }
                    if(!empty($row["image_path"])){
                        echo nl2br("<p><font color=\"{$row["text_color"]}\">{$row["message"]}</font>
                        <img src=\"{$row["image_path"]}\" alt=\"image\" width=\"60%\" height=\"60%\"></p>");
                    }
                    else{
                        echo nl2br("<p><font color=\"{$row["text_color"]}\">{$row["message"]}</font></p>");    
                    }
                    ?>

                    <!-- check_edit_pass.phpとcheck_delete_pass.phpにinput hiddenで主キー(id)を送信し、delete_keyを検索できるように下記を記載している -->
                    <div class="buttom">
                        <form action="./reply.php" method="get" enctype="multipartform-data">
                            <a href="./reply.php?reply_number=<?php echo $row["id"];  ?>">このトピックを全て表示する</a>
                        </form>
                        <form action="check_edit_pass.php" method="post" enctype="multipartform-data">
                            <?php echo "<input type =\"submit\" value=\"編集\">"; ?>
                            <input type="hidden" name="delete_number" value="<?php echo $row["id"]; ?>">
                        </form>
                        <form action="./check_delete_pass.php" method="post" enctype="multipartform-data">
                            <?php echo "<input type =\"submit\" value=\"削除\">"; ?>
                            <input type="hidden" name="delete_number" value="<?php echo $row["id"]; ?>">
                        </form>
                    </div>
                    <?php
                echo "</div>";
            }
        }
    }

    if(!empty($_POST["search_word"]) and $_POST["check_type"] === "and" ){
        foreach($search_word as $words){
            $tst[] = "message LIKE '%$words%'";
        }
        $tst = implode(" AND ", $tst );
        $search_sql = mysqli_query($link,"SELECT id, name, subject, message, image_path, email, url, text_color, created_at FROM boards WHERE $tst
                                          UNION 
                                          SELECT id, name, subject, message, image_path, email, url, text_color, created_at FROM replies WHERE $tst ORDER BY created_at DESC; ");

        while ($row = mysqli_fetch_assoc($search_sql)){
                
                
            echo "<div class=\"border\">";
            
                echo "<p><font color=\"#FF0000\">{$row["subject"]}</font>- ";
                if(!empty($row["url"])){
                    echo "<a href=\"mailto:{$row["email"]}\">{$row["name"]}</a> <a href=\"{$row["url"]}\">URL</a></p>" ;
                    echo "<p><font color=\"#663333\">{$row["created_at"]}</font></p>";
                }
                else{
                    echo "<font color=\"#000000\">{$row["name"]}</font></p>";
                    echo "<p><font color=\"#663333\">{$row["created_at"]}</font></p>";
                }
                if(!empty($row["image_path"])){
                    echo nl2br("<p><font color=\"{$row["text_color"]}\">{$row["message"]}</font>
                    <img src=\"{$row["image_path"]}\" alt=\"image\" width=\"60%\" height=\"60%\"></p>");
                }
                else{
                    echo nl2br("<p><font color=\"{$row["text_color"]}\">{$row["message"]}</font></p>");    
                }
                ?>

                <!-- check_edit_pass.phpとcheck_delete_pass.phpにinput hiddenで主キー(id)を送信し、delete_keyを検索できるように下記を記載している -->
                <div class="buttom">
                    <form action="./reply.php" method="get" enctype="multipartform-data">
                        <a href="./reply.php?reply_number=<?php echo $row["id"]; ?>">このトピックを全て表示する</a>
                    </form>
                    <form action="check_edit_pass.php" method="post" enctype="multipartform-data">
                        <?php echo "<input type =\"submit\" value=\"編集\">"; ?>
                        <input type="hidden" name="delete_number" value="<?php echo $row["id"]; ?>">
                    </form>
                    <form action="./check_delete_pass.php" method="post" enctype="multipartform-data">
                        <?php echo "<input type =\"submit\" value=\"削除\">"; ?>
                        <input type="hidden" name="delete_number" value="<?php echo $row["id"]; ?>">
                    </form>
                </div>
                <?php
            echo "</div>";
        }



    }


        ?>

</body>
</html>