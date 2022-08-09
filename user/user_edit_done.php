<?php
    //ログインチェック
    session_start();
    session_regenerate_id(true);
    if(isset($_SESSION["login"]) == false){
        echo "ログインされていません。<br>";
        echo "<a href='../user_login/user_login.html'>ログイン画面へ</a>";
        exit();
    }else{
        echo "{$_SESSION["user_name"]}さんログイン中<br><br>";
    }

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>NAKAレストラン</title>
</head>
<body>

    <?php
        //データベースの障害対策　エラートラップ
        try{
            require_once("../common/common.php");

            //安全対策
            $post = sanitize($_POST);
            $user_code = $post["code"];
            $user_name = $post["name"];
            $user_pass = $post["pass"];

            //データベースに接続
            $dsn = "mysql:dbname=cgm;host=localhost;charset=utf8";
            $user = "root";
            $password = "";
            $dbh = new PDO($dsn, $user, $password);
            $dbh -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            //SQL文で情報追加を命令
            $sql = "UPDATE mst_user SET name=?, password=? WHERE code=?";
            $stmt = $dbh -> prepare($sql);
            $data[] = $user_name;
            $data[] = $user_pass;
            $data[] = $user_code;
            $stmt -> execute($data);

            //データベースから切断
            $dbh = null;
            
        }catch(Exception $e){
            echo "ただいま障害により大変ご迷惑をおかけしております。";

            //強制終了
            exit();
        }
    ?>

    修正しました<br>
    <br>
    <a href="user_top.php">戻る</a>

</body>
</html>