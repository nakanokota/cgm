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
        require_once("../common/common.php");

        //安全対策
        $post = sanitize($_POST);
        $user_code = $post["code"];
        $user_name = $post["name"];
        $user_pass = $post["pass"];
        $user_pass2 = $post["pass2"];

        $error_count = 0;

        //$user_nameが空のとき
        if($user_name == ""){
            echo "スタッフ名が入力されていません。<br>";
            $error_count += 1;
        }else{
            echo "スタッフ名:{$user_name}<br>";
        }

        //$user_passが空の時
        if($user_pass == ""){
            echo "パスワードが入力されていません。<br>";
            $error_count += 1;
        }

        //パスワードが一致しないとき
        if($user_pass != $user_pass2){
            echo "パスワードが一致していません。<br>";
            $error_count += 1;
        }
    ?>

    <?php
    //入力に問題があるか
    //ture 戻るボタン生成
    //false 戻るとOKボタン生成 ?>
    <?php if($error_count > 0):?>
        <from>
            <input type="button" onclick="history.back()" value="戻る">
        </from>
    <?php else:?>
        <?php $user_pass = password_hash($user_pass, PASSWORD_DEFAULT)?>
        <form method="post" action="user_edit_done.php">
            <input type="hidden" name="code" value="<?php echo $user_code?>">
            <input type="hidden" name="name" value="<?php echo $user_name?>">
            <input type="hidden" name="pass" value="<?php echo $user_pass?>">
            <br>
            <input type="button" onclick="history.back()" value="戻る">
            <input type="submit" value="OK">
        </form>
    <?php endif ?>



</body>
</html>