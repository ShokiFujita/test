<?php
    session_start();

    // セッションのflashメッセージをクリア
    $flash = isset($_SESSION["flash"]) ? $_SESSION["flash"] : [];
    unset($_SESSION["flash"]);

    // postがあった場合confirmに$_SESSION["original"]の中へ保存しているのでその値を$originalへ代入している
    $original = isset($_SESSION["original"]) ? $_SESSION["original"] : [];
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>返信画面</title>
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
        .replies{
            margin-left: 10%
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
    <?php
        require("db_connect.php");
    if(empty($_GET["reply_number"])){
        // post
        if(!empty($_POST["reply_number"])){
            $delete_number = $_POST["reply_number"];
        
            $result = mysqli_query($link, "SELECT * FROM boards WHERE id = $delete_number  ORDER BY id DESC");

            while ($row = mysqli_fetch_assoc($result)){
                

                // id,delete_key確認用
                echo "id {$row["id"]}<br/>";
                echo "delete_key {$row["delete_key"]}<br/>";


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
                        <form action="./check_edit_pass.php" method="post" enctype="multipartform-data">
                        <?php echo "<input type =\"submit\" value=\"編集\">"; ?>
                        <input type="hidden" name="delete_number" value="<?php echo $row["id"]; ?>">
                        </form>
                        <form action="./check_delete_pass.php" method="post" enctype="multipartform-data">
                        <?php echo "<input type =\"submit\" value=\"削除\">"; ?>
                        <input type="hidden" name="delete_number" value="<?php echo $row["id"]; ?>">
                        </form>
                    </div>
                    <?php

                    // reply
                    $reply_result = mysqli_query($link,"SELECT * FROM replies");
                    
                    while($reply_row = mysqli_fetch_assoc($reply_result)){
                        echo "<div class=\"replies\">";

                            if($reply_row["board_id"] === $row["id"]){

                                echo "<hr/ width=\"100%\">";

                                // id確認用
                                echo "id {$reply_row["id"]}<br/>";
                                echo "board_id {$reply_row["board_id"]}<br/>";
                                echo "delete_key {$reply_row["delete_key"]}<br/>";
                                
                                echo "<p><font color=\"#FF0000\">{$reply_row["subject"]}</font>- ";
                                if(!empty($reply_row["url"])){
                                    echo "<a href=\"mailto:{$reply_row["email"]}\">{$reply_row["name"]}</a> <a href=\"{$reply_row["url"]}\">URL</a></p>" ;
                                    echo "<p><font color=\"#663333\">{$reply_row["created_at"]}</font></p>";
                                }
                                else{
                                    echo "<font color=\"#000000\">{$reply_row["name"]}</font></p>";
                                    echo "<p><font color=\"#663333\">{$reply_row["created_at"]}</font></p>";
                                }
                                if(!empty($reply_row["image_path"])){
                                    echo nl2br("<p><font color=\"{$reply_row["text_color"]}\">{$reply_row["message"]}</font>
                                    <img src=\"{$reply_row["image_path"]}\" alt=\"image\" width=\"60%\" height=\"60%\"></p>");
                                }
                                else{
                                    echo nl2br("<p><font color=\"{$reply_row["text_color"]}\">{$reply_row["message"]}</font></p>");    
                                }

                                ?>

                                <!-- check_edit_pass.phpとcheck_delete_pass.phpにinput hiddenで主キー(id)を送信し、delete_keyを検索できるように下記を記載している -->
                                <div class="buttom">
                                    <form action="./check_reply_edit_pass.php" method="post" enctype="multipartform-data">
                                    <?php echo "<input type =\"submit\" value=\"編集\">"; ?>
                                    <input type="hidden" name="delete_number" value="<?php echo $reply_row["id"]; ?>">
                                    </form>
                                    <form action="./check_reply_delete_pass.php" method="post" enctype="multipartform-data">
                                    <?php echo "<input type =\"submit\" value=\"削除\">"; ?>
                                    <input type="hidden" name="delete_number" value="<?php echo $reply_row["id"]; ?>">
                                    </form>
                                </div>
                                <?php
                            
                            }
                        echo "</div>";
                    }
                echo "</div>";
            }
        }
    }
        // from search
        if(!empty($_GET["reply_number"])){
            $delete_number = $_GET["reply_number"];

            $result = mysqli_query($link, "SELECT * FROM replies WHERE id = $delete_number  ORDER BY id DESC");
            $row = mysqli_fetch_assoc($result);

            if(!empty($row)){
                $board_id = $row["board_id"];
                $delete_number = $board_id;

                $result = mysqli_query($link, "SELECT * FROM boards WHERE id =  $board_id ORDER BY id DESC");
            }
            else{
                $result = mysqli_query($link, "SELECT * FROM boards WHERE id = $delete_number  ORDER BY id DESC");
            }
            while ($row = mysqli_fetch_assoc($result)){
                

                // id,delete_key確認用
                echo "id {$row["id"]}<br/>";
                echo "delete_key {$row["delete_key"]}<br/>";


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
                        <form action="./check_edit_pass.php" method="post" enctype="multipartform-data">
                        <?php echo "<input type =\"submit\" value=\"編集\">"; ?>
                        <input type="hidden" name="delete_number" value="<?php echo $row["id"]; ?>">
                        </form>
                        <form action="./check_delete_pass.php" method="post" enctype="multipartform-data">
                        <?php echo "<input type =\"submit\" value=\"削除\">"; ?>
                        <input type="hidden" name="delete_number" value="<?php echo $row["id"]; ?>">
                        </form>
                    </div>
                    <?php

                    // reply
                    $reply_result = mysqli_query($link,"SELECT * FROM replies");
                    
                    while($reply_row = mysqli_fetch_assoc($reply_result)){
                        echo "<div class=\"replies\">";

                            if($reply_row["board_id"] === $row["id"]){

                                echo "<hr/ width=\"100%\">";

                                // id確認用
                                echo "id {$reply_row["id"]}<br/>";
                                echo "board_id {$reply_row["board_id"]}<br/>";
                                echo "delete_key {$reply_row["delete_key"]}<br/>";
                                
                                echo "<p><font color=\"#FF0000\">{$reply_row["subject"]}</font>- ";
                                if(!empty($reply_row["url"])){
                                    echo "<a href=\"mailto:{$reply_row["email"]}\">{$reply_row["name"]}</a> <a href=\"{$reply_row["url"]}\">URL</a></p>" ;
                                    echo "<p><font color=\"#663333\">{$reply_row["created_at"]}</font></p>";
                                }
                                else{
                                    echo "<font color=\"#000000\">{$reply_row["name"]}</font></p>";
                                    echo "<p><font color=\"#663333\">{$reply_row["created_at"]}</font></p>";
                                }
                                if(!empty($reply_row["image_path"])){
                                    echo nl2br("<p><font color=\"{$reply_row["text_color"]}\">{$reply_row["message"]}</font>
                                    <img src=\"{$reply_row["image_path"]}\" alt=\"image\" width=\"60%\" height=\"60%\"></p>");
                                }
                                else{
                                    echo nl2br("<p><font color=\"{$reply_row["text_color"]}\">{$reply_row["message"]}</font></p>");    
                                }

                                ?>

                                <!-- check_edit_pass.phpとcheck_delete_pass.phpにinput hiddenで主キー(id)を送信し、delete_keyを検索できるように下記を記載している -->
                                <div class="buttom">
                                    <form action="./check_reply_edit_pass.php" method="post" enctype="multipartform-data">
                                    <?php echo "<input type =\"submit\" value=\"編集\">"; ?>
                                    <input type="hidden" name="delete_number" value="<?php echo $reply_row["id"]; ?>">
                                    </form>
                                    <form action="./check_reply_delete_pass.php" method="post" enctype="multipartform-data">
                                    <?php echo "<input type =\"submit\" value=\"削除\">"; ?>
                                    <input type="hidden" name="delete_number" value="<?php echo $reply_row["id"]; ?>">
                                    </form>
                                </div>
                                <?php
                            
                            }
                        echo "</div>";
                    }
                echo "</div>";
            }

            
        echo "</div>";

        }

    ?>


    <form action="./reply_confirm.php" method="post" enctype="multipart/form-data">

        <div class="border">
            <table>
                <tr>
                    <td><?php echo isset($flash["name"]) ? $flash["name"] : null ?></td>
                </tr>
                <tr>
                    <td><?php echo isset($flash["message"]) ? $flash["message"] : null ?></td>
                </tr>
                <tr>
                    <td><?php echo isset($flash["delete_key"]) ? $flash["delete_key"] : null ?></td>
                </tr>

                <tr>
                    <td>名前</dh>
                <td><input type="text" name="name" value="<?php echo isset($original["name"]) ? $original["name"] : null;?>"></td>
                </tr>
                <tr>
                    <td>件名</td>
                    <td><input type="text" name="subject" value="<?php
                            $result_reply_subject = mysqli_query($link, "SELECT subject FROM boards WHERE id = $delete_number");
                            while($reply_subject = mysqli_fetch_assoc($result_reply_subject)){
                                echo "Re:{$reply_subject["subject"]}";
                            }             
                    //  echo isset($original["subject"]) ? $original["subject"] : null;
                     ?>"></td>
                </tr>
                <tr>
                    <td>メッセージ</td>
                </tr>
                <tr>
                    <td><textarea name="message"><?php echo isset($original["message"]) ? $original["message"] : null;?></textarea></td>
                </tr>
                <tr>
                    <td>画像</td>
                    <td><input type="file" name="upload_img"></td>
                </tr>
                <tr>
                    <td>メールアドレス</td>
                    <td><input type="email" name="email" value="<?php echo isset($original["email"]) ? $original["email"] : null;?>"></td>
                </tr>
                <tr>
                    <td>URL</td>
                    <td><input type="text" name="url" value="<?php echo isset($original["url"]) ? $original["url"] : null;?>"></td>
                </tr>
                <tr>
                    <td>文字色</td>
                    <td>
                        <input type="radio" name="text_color" value="#FF0000" <?php if(!empty($original["text_color"]) and $original["text_color"] === "#FF0000" ){ echo "checked";}else{ echo "checked";} ?>><font color="#FF0000">■</font>
                        <input type="radio" name="text_color" value="#008000" <?php if(!empty($original["text_color"]) and $original["text_color"] === "#008000" ){ echo "checked";} ?>><font color="#008000">■</font>
                        <input type="radio" name="text_color" value="#0000FF" <?php if(!empty($original["text_color"]) and $original["text_color"] === "#0000FF" ){ echo "checked";} ?>><font color="#0000FF">■</font>
                        <input type="radio" name="text_color" value="#FF00FF" <?php if(!empty($original["text_color"]) and $original["text_color"] === "#FF00FF" ){ echo "checked";} ?>><font color="#FF00FF">■</font>
                        <input type="radio" name="text_color" value="#800080" <?php if(!empty($original["text_color"]) and $original["text_color"] === "#800080" ){ echo "checked";} ?>><font color="#800080">■</font>
                        <input type="radio" name="text_color" value="#FF9966" <?php if(!empty($original["text_color"]) and $original["text_color"] === "#FF9966" ){ echo "checked";} ?>><font color="#FF9966">■</font>
                        <input type="radio" name="text_color" value="#000099" <?php if(!empty($original["text_color"]) and $original["text_color"] === "#000099" ){ echo "checked";} ?>><font color="#000099">■</font>
                        <input type="radio" name="text_color" value="#000000" <?php if(!empty($original["text_color"]) and $original["text_color"] === "#000000" ){ echo "checked";} ?>><font color="#000000">■</font>
                    </td>
                </tr>
                <tr>
                    <td>削除キー</td>
                    <td><input type="password" name="delete_key" value="<?php echo isset($original["delete_key"]) ? $original["delete_key"] : null;?>">(半角英数字のみで4~8文字)</td>
                </tr>
                <tr>
                    <td colspan="2"><input type="checkbox" name="preview">プレビューする(投稿前に、内容をプレビューして確認できます)</td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <input type="submit" value="投稿">
                        <input type="submit" name="reset" value="リセット">
                        <input type="hidden" name ="reply_number" value="<?php echo $delete_number; ?>">
                    </td>
                </tr>
            </table>
        </div>
    </form>
    
</body>
</html>