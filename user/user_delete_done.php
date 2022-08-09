<?php
    //データベースの障害対策　エラートラップ
    try{
        $user_code = $_POST["code"];

        //データベースに接続
        $dsn = "mysql:dbname=cgm;host=localhost;charset=utf8";
        $user = "root";
        $password = "";
        $dbh = new PDO($dsn, $user, $password);
        $dbh -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //mst_user
        //SQL文で情報追加を命令
        $sql = "DELETE FROM mst_user WHERE code=?";
        $stmt = $dbh -> prepare($sql);
        $data[0] = $user_code;
        $stmt -> execute($data);

        //mst_contents
        //SQL文で情報追加を命令
        $sql = "SELECT code FROM mst_contents WHERE user_code=?";
        $stmt = $dbh -> prepare($sql);
        $data[0] = $user_code;
        $stmt -> execute($data);

        $cont_code = NULL;
        for($i=0; ; $i++){
            //検索結果を$recに
            $rec = $stmt->fetch(PDO::FETCH_ASSOC); 
            if($rec == false){
                break;
            }
            $cont_code[$i] = $rec["code"];
        }

        $max_cont = 0;
        //繰り返し処理で画像を削除
        if(is_countable($cont_code)){
            $max_cont = count($cont_code);
            for($i=0; $i<$max_cont; $i++){
                unlink("../contents/img/img_{$cont_code[$i]}.jpg");
            }
        }
        
        //SQL文で情報追加を命令
        $sql = "DELETE FROM mst_contents WHERE user_code=?";
        $stmt = $dbh -> prepare($sql);
        $data[0] = $user_code;
        $stmt -> execute($data);

        //データベースから切断
        $dbh = null;

        //ログアウトページに移動
        header("Location: user_delete_logout.php");
        exit();
        
    }catch(Exception $e){
        echo "ただいま障害により大変ご迷惑をおかけしております。";

        //強制終了
        exit();
    }
    
?>