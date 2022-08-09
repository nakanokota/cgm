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
    require_once("../common/common.php");

    //安全対策
    $post = sanitize($_POST);
    $cont_name = $post["name"];
    $cont_img = $_FILES["img"];

    $error_count = 0;

    //$cont_nameが空のとき
    if($cont_name == ""){
        echo "作品名が入力されていません。<br>";
        $error_count += 1;
    }

    //画像があるか
    if($cont_img["size"] > 0){
        //画像のサイズが大きすぎないか
        if($cont_img["size"] > 5000000){
            echo "画像が大きすぎます。<br>";
            $error_count += 1;
        }
    }else{
        echo "画像を選択してください。<br>";
        $error_count += 1;
    }
    
    
?>

<!--問題なければ投稿 -->
<?php if($error_count > 0):?>
    <from>
        <input type="button" onclick="history.back()" value="戻る">
    </from>
<?php else:?>
    <?php
    //データベースに接続
    $dsn = "mysql:dbname=cgm;host=localhost;charset=utf8";
    $user = "root";
    $password = "";
    $dbh = new PDO($dsn, $user, $password);
    $dbh -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //SQL文で情報追加を命令
    $sql = "INSERT INTO mst_contents(name,user_code) VALUES (?,?)";
    $stmt = $dbh -> prepare($sql);
    $data[] = $cont_name;
    $data[] = $_SESSION["user_code"];
    $stmt -> execute($data);

    //追加したデータのcodeを取得
    $sql = "SELECT LAST_INSERT_ID()";
    $stmt = $dbh -> prepare($sql);
    $stmt -> execute($data);

    //検索結果のデータを取り出し、代入
    $rec = $stmt -> fetch(PDO::FETCH_ASSOC);
    $cont_code = $rec['LAST_INSERT_ID()'];
    
    //データベースから切断
    $dbh = null;

    //アップロード
    move_uploaded_file($cont_img["tmp_name"], "./img/img_{$cont_code}.jpg");
    ?>

    作品を投稿しました。<br>
    作品名：<?php echo $cont_name?><br>
    <img class="img_view" src='./img/img_<?php echo $cont_code?>.jpg'><br>
    <a href="../user/user_top.php">ユーザートップへ戻る</a>
<?php endif ?>


</body>
</html>