<?php
    // //ログインチェック
    // session_start();
    // session_regenerate_id(true);
    // if(isset($_SESSION["login"]) == false){
    //     echo "ログインされていません。<br>";
    //     echo "<a href='../login/login.html'>ログイン画面へ</a>";
    //     exit();
    // }

    // //詳細ボタンを押した場合
    // if(isset($_POST["disp"]) == true){
    //     $cont_code = $_POST["contcode"];
    //     header("Location: cont_disp.php?contcode={$cont_code}");
    //     exit(); 
    // }

    // //削除ボタンを押した場合
    // if(isset($_POST["delete"]) == true){
    //     $cont_code = $_POST["contcode"];
    //     header("Location: cont_delete.php?contcode={$cont_code}");
    //     exit();
    // }

?>