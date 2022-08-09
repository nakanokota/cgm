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

    作品投稿<br>
    <br>
    <form method="post" action="cont_post_done.php" enctype="multipart/form-data">
        作品名を入力してください。<br>
        <input type="test" name="name" style="width:200px"><br>
        画像を選んでください。<br>
        <input type="file" name="img" style="width:400px"><br>
        <br>
        <input type="button" onclick="history.back()" value="戻る">
        <input type="submit" value="投稿">
    </form>

</body>
</html>