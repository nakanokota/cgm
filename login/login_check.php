<?php

    //データベースの障害対策　エラートラップ
    try{
        require_once("../common/common.php");

        //安全対策
        $post = sanitize($_POST);
        $user_code = $post["code"];
        $user_pass = $post["pass"];

        //データベースに接続
        $dsn = "mysql:dbname=cgm;host=localhost;charset=utf8";
        $user = "root";
        $password = "";
        $dbh = new PDO($dsn, $user, $password);
        $dbh -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //SQL文で命令
        $sql = "SELECT password FROM mst_user WHERE code=?";
        $stmt = $dbh -> prepare($sql);
        $data[0] = $user_code; //入れ替えが必要になるため0を指定
        $stmt -> execute($data);

        //検索結果を$recに突っ込む
        $rec = $stmt->fetch(PDO::FETCH_ASSOC);

        //$user_pass に文字が入っているかどうか  $rec["password"]が定義されているかどうか
        if($user_pass == true && isset($rec["password"]) == true){
            //パス認証
            if(password_verify($user_pass, $rec["password"])){
                //SQL文で命令
                $sql = "SELECT name FROM mst_user WHERE code=?";
                $stmt = $dbh -> prepare($sql);
                $data[0] = $user_code;
                $stmt -> execute($data);

                //検索結果を$recに突っ込む
                $rec = $stmt->fetch(PDO::FETCH_ASSOC);
            }
        }

        //データベースから切断
        $dbh = null;

        //$recが空かどうか
        if(isset($rec["name"]) == false){
            echo "入力に間違いがあります。<br>";
            echo "<a href='login.html'>戻る</a>";
        }else{
            session_start();
            $_SESSION["login"] = 1;
            $_SESSION["user_code"] = $user_code;
            $_SESSION["user_name"] = $rec["name"];
            header("Location: ../user/user_top.php");
            exit();
        }
        
    }catch(Exception $e){
        echo "ただいま障害により大変ご迷惑をおかけしております。";

        //強制終了
        exit();
    }

?>