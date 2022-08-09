<?php
    //ログインチェック
    session_start();
    session_regenerate_id(true);
    if(isset($_SESSION["login"]) == false){
        echo "ログインされていません。<br>";
        echo "<a href='../login/login.html'>ログイン画面へ</a>";
        exit();
    }else{
        echo "{$_SESSION["user_name"]}さんログイン中<br><br>";
    }

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>cgm</title>
</head>
<body>

    <?php
        //データベースの障害対策　エラートラップ
        try{
            $cont_code = $_POST["code"];

            //データベースに接続
            $dsn = "mysql:dbname=cgm;host=localhost;charset=utf8";
            $user = "root";
            $password = "";
            $dbh = new PDO($dsn, $user, $password);
            $dbh -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            //SQL文で情報追加を命令
            $sql = "DELETE FROM mst_contents WHERE code=?";
            $stmt = $dbh -> prepare($sql);
            $data[] = $cont_code;
            $stmt -> execute($data);

            //データベースから切断
            $dbh = null;

            //画像を削除する            
            unlink("./img/img_{$cont_code}.jpg");
            
            
        }catch(Exception $e){
            echo "ただいま障害により大変ご迷惑をおかけしております。";

            //強制終了
            exit();
        }
    ?>

    削除しました<br>
    <br>
    <a href="../user/user_top.php">戻る</a>

</body>
</html>