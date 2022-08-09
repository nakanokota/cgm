<?php
    //ログインチェック
//    session_start();
//    session_regenerate_id(true);
//    if(isset($_SESSION["login"]) == false){
//        echo "ログインされていません。<br>";
//        echo "<a href='../user_login/user_login.html'>ログイン画面へ</a>";
//        exit();
//    }else{
//        echo "{$_SESSION["user_name"]}さんログイン中<br><br>";
//    }

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
            require_once("../common/common.php");

            //安全対策
            $post = sanitize($_POST);
            $user_name = $post["name"];
            $user_pass = $post["pass"];

            //データベースに接続
            $dsn = "mysql:dbname=cgm;host=localhost;charset=utf8";
            $user = "root";
            $password = "";
            $dbh = new PDO($dsn, $user, $password);
            $dbh -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            //SQL文で情報追加を命令
            $sql = "INSERT INTO mst_user(name,password) VALUES (?,?)";
            $stmt = $dbh -> prepare($sql);
            $data[] = $user_name;
            $data[] = $user_pass;
            $stmt -> execute($data);

            //追加したデータのcodeを取得
            $sql = "SELECT LAST_INSERT_ID()";
            $stmt = $dbh -> prepare($sql);
            $stmt -> execute($data);

            //検索結果のデータを取り出し、代入
            $rec = $stmt -> fetch(PDO::FETCH_ASSOC);
            $user_code = $rec['LAST_INSERT_ID()'];

            //データベースから切断
            $dbh = null;

            echo "ユーザーコード：{$user_code}<br>";
            echo "{$user_name}さんを追加しました。<br>";
            echo "ユーザーコードはログインの際必要になります。<br>";
            
        }catch(Exception $e){
            echo "ただいま障害により大変ご迷惑をおかけしております。";

            //強制終了
            exit();
        }
    ?>

    <a href="../login/login.html">戻る</a>

</body>
</html>