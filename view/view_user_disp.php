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

            //mst_user
            //SQL文、ユーザーコードで絞り込んでいく　1件のレコードに絞り込める
            $sql = "SELECT name FROM mst_user WHERE code=?";
            $stmt = $dbh -> prepare($sql);
            $data[0] = $user_code;
            $stmt -> execute($data);

            //$user_codeのデータを取り出し、配列nameを代入
            $rec = $stmt -> fetch(PDO::FETCH_ASSOC);
            $user_name = $rec['name'];

            //mst_contents
            //データベース内検索
            $sql = "SELECT code, name FROM mst_contents WHERE user_code=? ORDER BY code DESC";
            $stmt = $dbh->prepare($sql);
            $data[0] = $user_code;
            $stmt->execute($data);

            for($i=0; ; $i++){
                //検索結果を$rec2に
                $rec2 = $stmt->fetch(PDO::FETCH_ASSOC); 
                if($rec2 == false){
                    break;
                }
                $cont[$i]["code"] = $rec2["code"];
                $cont[$i]["name"] = $rec2["name"];
            }

            //データベースから切断
            $dbh = null;

        }catch(Exception $e){
            echo "ただいま障害により大変ご迷惑をおかけしております。";

            //強制終了
            exit();
        }

        //以下ページングの準備
        if(isset($cont) == true){
            //1ページ当たりの作品数
            define("MAX", "5");

            //総数をカウント
            $conts_total = count($cont);

            //必要ページ数を計算
            $max_page = ceil($conts_total / MAX); //ceilは小数点以下切り上げ

            //現在のページを判断
            if(isset($_GET["page_id"]) == false){
                //存在していなければ今は1
                $now_page = 1;
            }else{
                $now_page = $_GET["page_id"];
            }

            //配列の何番目から取得していけばいいか
            $start_num = ($now_page - 1) * MAX;

            //切り取り
            $conts_data = array_slice($cont, $start_num, MAX, true);
        }
    ?>

    ユーザー情報参照<br>
    <br>
    ユーザー名<br>
    <?php echo $user_name ?><br>
    <br>
    <!-- データがない場合exit() -->
    <?php if(isset($cont) == false):?>
        まだ作品が投稿されていません。<br>
        <form><input type='button' onclick='history.back()' value='戻る'></form>
        <?php exit()?>
    <?php endif?>

    <!--作品表示 -->
    <br>
    <table border="1">
        <tr>
            <td>作品名</td>
            <td>作品</td>
            <td>詳細確認</td>
        </tr>
        <?php foreach($conts_data as $val):?>
            <tr>
                <td><?php echo $val["name"]?></td>
                <td><img class='img_view' src='../contents/img/img_<?php echo $val["code"]?>.jpg'></td>
                <td><a href="view_cont_disp.php?contcode=<?php echo $val["code"]?>">詳細</a></td>
            </tr>
        <?php endforeach?>
    </table>
    <br>
    現在のページ：<?php echo $now_page?><br>
    <!-- ページリンクを生成　下5つ -->
    <?php for($i=$now_page-5; $i<$now_page; $i++):?>
        <!-- マイナスと0の場合表示しない -->
        <?php if($i <= 0):?>
            <?php echo "";?>
        <?php else:?>
            <a href="view_cont_list.php?page_id=<?php echo $i?>"><?php echo $i?></a>　
        <?php endif?>
    <?php endfor?>

    <!-- ページリンクを生成　上5つ -->
    <?php for($i=$now_page; $i<=$i+5; $i++):?>
        <!-- $max_pageを超えた場合ループ脱出 -->
        <?php if($i > $max_page):?>
            <?php break?>
        <?php endif?>
        <!-- 現在のページの場合リンクは張らない -->
        <?php if($i == $now_page):?>
            <?php echo $i."　"?>
        <?php else:?>
            <a href="view_cont_list.php?page_id=<?php echo $i?>"><?php echo $i?></a>　
        <?php endif?>
    <?php endfor?>

</body>
</html>