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
<link rel="stylesheet" href="../common/common.css">
</head>
<body>

    <?php
        //データベースの障害対策　エラートラップ
        try{
            //contcodeを受取
            $cont_code = $_GET["contcode"];

            //データベースに接続
            $dsn = "mysql:dbname=cgm;host=localhost;charset=utf8";
            $user = "root";
            $password = "";
            $dbh = new PDO($dsn, $user, $password);
            $dbh -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            //SQL文、スタッフコードで絞り込んでいく　1件のレコードに絞り込める
            $sql = "SELECT name FROM mst_contents WHERE code=?";
            $stmt = $dbh -> prepare($sql);
            $data[] = $cont_code;
            $stmt -> execute($data);

            //$cont_codeでデータを取り出し、配列を変数に代入
            $rec = $stmt -> fetch(PDO::FETCH_ASSOC);
            $cont_name = $rec['name'];

            //データベースから切断
            $dbh = null;

            //画像を準備する
            $disp_img = "<img class='img_view' src='./img/img_{$cont_code}.jpg'>";
        

        }catch(Exception $e){
            echo "ただいま障害により大変ご迷惑をおかけしております。";

            //強制終了
            exit();
        }
    ?>

    作品削除<br>
    <br>
    作品名<br>
    <?php echo $cont_name ?><br>
    作品<br>
    <?php echo $disp_img ?><br>
    この作品を削除してよろしいですか？<br>
    <br>
    <form method="post" action="cont_delete_done.php">
        <input type="hidden" name="code" value="<?php echo $cont_code ?>">
        <input type="button" onclick="history.back()" value="戻る">
        <input type="submit" value="OK">
    </form>

</body>
</html>