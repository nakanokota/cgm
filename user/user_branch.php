<?php
    //ログインチェック
    session_start();
    session_regenerate_id(true);
    if(isset($_SESSION["login"]) == false){
        echo "ログインされていません。<br>";
        echo "<a href='../login/login.html'>ログイン画面へ</a>";
        exit();
    }

    //ユーザー詳細ボタンを押した場合
    if(isset($_POST["disp"]) == true){
        $user_code = $_POST["usercode"];
        header('Location: user_disp.php?usercode='.$user_code);
        exit(); 
    }

    //ユーザー修正ボタンを押した場合
    if(isset($_POST["edit"]) == true){
        $user_code = $_POST["usercode"];
        header('Location: user_edit.php?usercode='.$user_code);
        exit(); 
    }

    //ユーザー削除ボタンを押した場合
    if(isset($_POST["delete"]) == true){
        $user_code = $_POST["usercode"];
        header('Location: user_delete.php?usercode='.$user_code);
        exit();
    }

    //投稿ボタンを押した場合
    if(isset($_POST["post"]) == true){
        header('Location: ../contents/cont_post.php');
        exit();
    }

    //投稿一覧ボタンを押した場合
    if(isset($_POST["post_list"]) == true){
        $user_code = $_POST["usercode"];
        header('Location: ../contents/cont_post_list.php?usercode='.$user_code);
        exit(); 
    }

    //作品一覧ボタンを押した場合
    if(isset($_POST["contents"]) == true){
        header("Location: ../view/view_cont_list.php"); //要入力
        exit();
    }


    //ログアウトボタンを押した場合
    if(isset($_POST["logout"]) == true){
        header('Location: ../login/logout.php');
        exit();
    }

?>