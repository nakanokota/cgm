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
<title>NAKAレストラン</title>
</head>
<body>

    <?php
        //データベースの障害対策　エラートラップ
        try{
            //usercodeを受取
            $user_code = $_GET["usercode"];

            //安全対策
            $user_code = htmlspecialchars($user_code, ENT_QUOTES, 'UTF-8');

            //データベースに接続
            $dsn = "mysql:dbname=cgm;host=localhost;charset=utf8";
            $user = "root";
            $password = "";
            $dbh = new PDO($dsn, $user, $password);
            $dbh -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            //SQL文、ユーザーコードで絞り込んでいく　1件のレコードに絞り込める
            $sql = "SELECT name FROM mst_user WHERE code=?";
            $stmt = $dbh -> prepare($sql);
            $data[] = $user_code;
            $stmt -> execute($data);

            //$user_codeのデータを取り出し、配列nameを代入
            $rec = $stmt -> fetch(PDO::FETCH_ASSOC);
            $user_name = $rec['name'];

            //データベースから切断
            $dbh = null;

        }catch(Exception $e){
            echo "ただいま障害により大変ご迷惑をおかけしております。";

            //強制終了
            exit();
        }
    ?>

    ユーザー修正<br>
    <br>
    ユーザーコード<br>
    <?php echo $user_code ?><br>
    <br>
    <form method="post" action="user_edit_check.php">
        <input type="hidden" name="code" value="<?php echo $user_code ?>">

        ユーザー名<br>
        <input type="text" name="name" value="<?php echo $user_name ?>" style="width:200px"><br>

        パスワードを入力してください。<br>
        <input type="password" name="pass" style="width:100px"><br>

        もう一度パスワードを入力してください。<br>
        <input type="password" name="pass2" style="width:100px"><br>
        <br>
        <input type="button" onclick="history.back()" value="戻る">
        <input type="submit" value="OK">
    </form>

</body>
</html>