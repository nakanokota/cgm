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
            //データベースに接続
            $dsn = "mysql:dbname=cgm;host=localhost;charset=utf8";
            $user = "root";
            $password = "";
            $dbh = new PDO($dsn, $user, $password);
            $dbh -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            //データベース内検索
            $sql = "SELECT code, name FROM mst_contents ORDER BY code DESC";
            $stmt = $dbh->prepare($sql);
            $data[] = "";
            $stmt->execute($data);

            for($i=0; ; $i++){
                //検索結果を$recに
                $rec = $stmt->fetch(PDO::FETCH_ASSOC); 
                if($rec == false){
                    break;
                }
                $cont[$i]["code"] = $rec["code"];
                $cont[$i]["name"] = $rec["name"];
            }

            //データベースから切断
            $dbh = null;

            //データがない場合exit()
            if(isset($cont) == false){
                echo "まだ作品が投稿されていません。<br>";
                echo "<form><input type='button' onclick='history.back()' value='戻る'></form>";
                exit();
            }

        }catch(Exception $e){
            echo "ただいま障害により大変ご迷惑をおかけしております。";

            //強制終了
            exit();
        }

        //以下ページングの準備
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
    ?>

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