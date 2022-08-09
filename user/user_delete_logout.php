<?php
    //ログアウト
    session_start();
    $_SESSION = array();
    //クッキーに情報があるか
    if(isset($_COOKIE[session_name()]) == true){
        //パソコン側のセッションID(合言葉)をクッキーから削除
        setcookie(session_name(), "", time()-42000, "/");
    }
    //セッションを破棄
    session_destroy();

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>cgm</title>
</head>
<body>
    削除しました。<br>
    <br>
    <a href="../login/login.html">ログイン画面へ</a>
</body>
</html>