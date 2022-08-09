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
</head>
<body>

    管理トップメニュー<br>
    <br>
    <form method="post" action="user_branch.php">
        <input type="hidden" name="usercode" value="<?php echo $_SESSION["user_code"]?>">
        <input type="submit" name="disp" value="ユーザー詳細"><br>
        <input type="submit" name="edit" value="ユーザー修正"><br>
        <input type="submit" name="delete" value="ユーザー削除"><br>
        <input type="submit" name="post" value="投稿"><br>
        <input type="submit" name="post_list" value="自ユーザー投稿一覧"><br>
        <input type="submit" name="contents" value="全ユーザー投稿一覧"><br>
        <input type="submit" name="logout" value="ログアウト"><br>
    </form>

</body>
</html>