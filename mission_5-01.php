<?php

    //やること
    /*１．パスワード機能の実装
      ２．投稿日時の追加
      ３．テーブルにパスワードと日付の項目追加*/
      
      
    //データベースへの接続
    $dsn = 'mysql:dbname=データベース名;host=localhost';
	$user = 'ユーザー名';
	$password = 'パスワード';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	
	//テーブルの作成
	$sql = "CREATE TABLE IF NOT EXISTS mission5_01"." (". "id INT AUTO_INCREMENT PRIMARY KEY,". "name char(32),"
	. "comment TEXT,"."date TEXT,"."password TEXT".");";
	$stmt = $pdo->query($sql);
	
    //入力フォームの処理
    if(!empty($_POST["name"])&&(!empty($_POST["comment"]))&&(!empty($_POST["password"]))){
        
        if(!empty($_POST["edit_select"])){
            
            $id = $_POST["edit_select"];
            $password = "";
            
            //パスワードを取得
            $sql = 'SELECT * FROM mission5_01 WHERE id=:id ';
            $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
            $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
            $stmt->execute();                             // ←SQLを実行する。
            $results = $stmt->fetchAll(); 
            foreach ($results as $row){
        		//$rowの中にはテーブルのカラム名が入る
        		$password = $row['password'];
        	}
        	
        	if($password==$_POST["password"]){
        	    //編集処理
                $name = $_POST["name"];
                $comment = $_POST["comment"];
                $sql = 'UPDATE mission5_01 SET name=:name,comment=:comment WHERE id=:id';
            	$stmt = $pdo->prepare($sql);
            	$stmt->bindParam(':name', $name, PDO::PARAM_STR);
            	$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
            	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
            	$stmt->execute();
        	}
            
        }else{
            //新規投稿
            $sql = $pdo -> prepare("INSERT INTO mission5_01 (name, comment, date, password) VALUES (:name, :comment, :date, :password)");
        	$sql -> bindParam(':name', $name, PDO::PARAM_STR);
        	$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
        	$sql -> bindParam(':date', $date, PDO::PARAM_STR);
        	$sql -> bindParam(':password', $password, PDO::PARAM_STR);
        	$name = $_POST["name"];
        	$comment = $_POST["comment"]; 
        	$date = "(".date("Y/m/d/H:i:s").")";
        	$password = $_POST["password"];
        	$sql -> execute();
        }
    }
    
    //削除フォームの処理
    if(!empty($_POST["delete"])){
        
        $id = $_POST["delete"];
        
        $sql = 'SELECT * FROM mission5_01 WHERE id=:id ';
        $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
        $stmt->execute();                             // ←SQLを実行する。
        $results = $stmt->fetchAll(); 
        foreach ($results as $row){
    		//$rowの中にはテーブルのカラム名が入る
    		$password = $row['password'];
    	}
        
        if($password==$_POST["delPass"]){
            
        	$sql = 'delete from mission5_01 where id=:id';
        	$stmt = $pdo->prepare($sql);
        	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
        	$stmt->execute();
        }
    }
    
    //編集用の変数初期化
    $editNumber = "";
    $editName = "";
    $editComment = "";
    
    //編集フォームの処理
    if(!empty($_POST["edit"])){
        $editNumber = $_POST["edit"];
        //指定したidの行をとりだす
        $sql = 'SELECT * FROM mission5_01 WHERE id=:id ';
        $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
        $stmt->bindParam(':id', $editNumber, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
        $stmt->execute();                             // ←SQLを実行する。
        $results = $stmt->fetchAll(); 
        foreach ($results as $row){
    		//$rowの中にはテーブルのカラム名が入る
    		$editName = $row['name'];
    		$editComment = $row['comment'];
    	}
    }
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>Mission_5-01</title>
    </head>
    <body>
        <h1>一行掲示板</h1>
        
        <form action="" method="POST">
            <h2>投稿フォーム</h2>
            名前<br>
            <input type="text" name="name" value="<?php echo $editName;?>">
            <br>投稿<br>
            <input type="text" name="comment" value="<?php echo $editComment;?>">
            <br>パスワード<br>
            <input type="password" name="password">
            <input type="hidden" name="edit_select" value="<?php echo $editNumber;?>">
            <input type="submit" name="input" value="投稿">
            <hr>
        </form>
        
        <form action="" method="POST">
            <h2>削除フォーム</h2>
            削除依頼番号<br>
            <input type="text" name="delete">
            <br>パスワード<br>
            <input type="password" name="delPass">
            <input type="submit" name="delsub" value="削除">
            <hr>
        </form>
        
        <form action="" method="POST">
            <h2>編集フォーム</h2>
            編集依頼番号<br>
            <input type="text" name="edit">
            <input type="submit" name="editsub" value="編集">
            <hr>
        </form>
        
        <h2>投稿表示</h2>
        <?php
            $sql = 'SELECT * FROM mission5_01';
        	$stmt = $pdo->query($sql);
        	$results = $stmt->fetchAll();
        	foreach ($results as $row){
        		//$rowの中にはテーブルのカラム名が入る
        		echo $row['id'].'　　';
        		echo $row['name'].'　　';
        		echo $row['comment'].'　　';
        		echo $row['date'].'<br>';
        	}
        ?>
        <hr>
    </body>
</html>