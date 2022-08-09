<?php
    //ログインチェック
    session_start();
    session_regenerate_id(true);
    if(isset($_SESSION["login"]) == false){
        echo "ようこそゲストさん　";
        echo "<a href='../login/login.html'>ログインする</a><br><br>";
    }else{
        echo "{$_SESSION["user_name"]}さんログイン中　";
        echo "<a href='../user/user_top.php'>管理画面トップへ</a><br><br>";
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

            //安全対策
            $cont_code = htmlspecialchars($cont_code, ENT_QUOTES, 'UTF-8');

            //データベースに接続
            $dsn = "mysql:dbname=cgm;host=localhost;charset=utf8";
            $user = "root";
            $password = "";
            $dbh = new PDO($dsn, $user, $password);
            $dbh -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            //mst_contents
            //SQL文、ユーザーコードで絞り込んでいく　1件のレコードに絞り込める
            $sql = "SELECT name, user_code FROM mst_contents WHERE code=?";
            $stmt = $dbh -> prepare($sql);
            $data[0] = $cont_code;
            $stmt -> execute($data);

            //$cont_codeのデータを取り出し、配列nameを代入
            $rec = $stmt -> fetch(PDO::FETCH_ASSOC);
            $cont_name = $rec['name'];
            $cont_user = $rec["user_code"];

            //mst_user
            //SQL文、ユーザーコードで絞り込んでいく　1件のレコードに絞り込める
            $sql = "SELECT name FROM mst_user WHERE code=?";
            $stmt = $dbh -> prepare($sql);
            $data[0] = $cont_user;
            $stmt -> execute($data);

            //検索結果のデータを取り出し、代入
            $rec2 = $stmt -> fetch(PDO::FETCH_ASSOC);
            $user_name = $rec2['name'];

            //データベースから切断
            $dbh = null;

        }catch(Exception $e){
            echo "ただいま障害により大変ご迷惑をおかけしております。";

            //強制終了
            exit();
        }
    ?>

    作品情報参照<br>
    <br>
    作品コード<br>
    <?php echo $cont_code ?><br>
    作者<br>
    <a href="view_user_disp.php?usercode=<?php echo $cont_user?>"><?php echo $user_name?></a><br>
    作品名<br>
    <?php echo $cont_name ?><br>
    作品<br>
    <img class='img_view' src='../contents/img/img_<?php echo $cont_code?>.jpg'><br>
    <br>
    <form>
        <input type="button" onclick="history.back()" value="戻る">
    </form>

</body>
</html>