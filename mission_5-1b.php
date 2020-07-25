<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>test_5-1</title>
    <style>
      form {
        margin-bottom: 20px;
      }
    </style>
</head>
<body>
    
    
    <?php
        
    // DB接続設定
    $dsn = '***';
	$user = '***';
	$password = '***';
	$pdo = new PDO("データベース", "ユーザー", "パスワード", array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
        //テーブルを新規作成
    	$sql = "CREATE TABLE IF NOT EXISTS test5_1"
    	." ("
    	. "id INT AUTO_INCREMENT PRIMARY KEY,"//投稿番号
    	. "name char(32),"//名前32字以内
        . "comment TEXT,"//コメント
        . "date TEXT,"//投稿時間
        . "tpass TEXT"//パスワード
	    .");";
    	$stmt = $pdo->query($sql);
    	
    	
        
        
    //「送信」ボタンが押されたとき
        if (isset($_POST["submit"])){
            //$edit_NUM = $_POST["edit_NUM"];
            $name = $_POST["name"]; //name : 入力された名前の値を取得
            $comment = $_POST["comment"]; //comment : 入力されたコメントの値を取得
            $date = date("Y/m/d H:i:s"); //date : 投稿日時を取得

            $tpass = $_POST["pass"];

            echo isset($_POST["edit_NUM"]);

        
        //通常の新規書き込みをするとき
            
                echo "新規書き込み完了";
                $sql = $pdo -> prepare("INSERT INTO test5_1 (name, comment, date, tpass) VALUES (:name, :comment, :date, :tpass)");
                
                if ($_POST["edit_NUM"] == ""){
            	$sql -> bindParam(':name', $name, PDO::PARAM_STR);
            	$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
            	$sql -> bindParam(':date', $date, PDO::PARAM_STR);
            	$sql -> bindparam(':tpass', $tpass, PDO::PARAM_STR);
            	//今まで組み立てた$sqlの内容を実行する　
                $sql -> execute();
            }else{
        
                //編集用の書き込みをするとき
            //if (isset($_POST["edit_NUM"])){
            
                echo "編集書き込み完了";
            	$edit_NUM = $_POST["edit_NUM"];
            	$sql = 'UPDATE test5_1 SET name=:name,comment=:comment,date=:date WHERE id=:id';
            	$stmt = $pdo->prepare($sql);
            	$stmt->bindParam(':id', $edit_NUM, PDO::PARAM_INT);
            	$stmt->bindParam(':name', $name, PDO::PARAM_STR);
            	$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
            	$stmt->bindParam(':date', $date, PDO::PARAM_STR);
            	$stmt->execute();
            }
            
        }
        
    //削除ボタンが押されたとき
        else if (isset($_POST["delete"])){
            
            $delete_id = $_POST["delete_num"];
            $dpass=$_POST["dpass"];
            $sql = 'SELECT * FROM test5_1 WHERE id=:id ';
            
            $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
            $stmt->bindParam(':id', $delete_id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
            $stmt->execute();                             // ←SQLを実行する。
            
            $results = $stmt->fetchAll();
            
            if ($results[0]['tpass'] == $dpass){
                echo "削除しました";
            	$sql = 'delete from test5_1 where id=:id';
            	$stmt = $pdo->prepare($sql);
            	$stmt->bindParam(':id', $delete_id, PDO::PARAM_INT);
            	$stmt->execute();
            }
            else{
                echo "パスワードが違います<br>";
            }
        }
    
    //編集ボタンが押されたとき    
        else if (isset($_POST["edit"])){
            $num = $_POST["edit_num"];
            $epass = $_POST["epass"];
            $sql = 'SELECT * FROM test5_1 WHERE id=:id ';
            
            $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
            $stmt->bindParam(':id', $num, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
            $stmt->execute();                             // ←SQLを実行する。
            
            $results = $stmt->fetchAll(); 
            if ($results[0]['tpass'] == $epass){
                echo "編集中";
                $edit_id = $results[0]['id'];
        		$edit_name = $results[0]['name'];
        		$edit_comment = $results[0]['comment'];
            }
        	else {
        	    echo "パスワードが違います<br>";
        	}
        }
        
    
        echo "ok<br>";
       
    	
        //}
    
    ?>
    
    
    <form action="" method="post">
        <!--コメントフォームの作成-->
        <input type="hidden" name="edit_NUM" value="<?php if(isset($edit_id)) {echo $edit_id;} ?>">
        <input type="text" name="name" value="<?php if(isset($edit_name)){echo $edit_name;} ?>" placeholder="名前"><br> 
        <input type="text" name="comment" value="<?php if(isset($edit_comment))echo $edit_comment; ?>" placeholder="コメント"><br>        
        <input type="text" name="pass" placeholder="パスワード">
        <input type="submit" name="submit" value="送信"><br><br>

         <!--削除フォームの作成-->
        <input type="number" name="delete_num" placeholder="削除したい番号を入力"><br>
        <input type="text" name="dpass" placeholder="パスワード">
        <input type="submit" name="delete" value="削除"><br><br>
        
        <!--編集フォームの作成-->
        <input type="number" name="edit_num" placeholder="編集したい番号を入力"><br>
        <input type="text" name="epass" placeholder="パスワード">
        <input type="submit" name="edit" value="編集">
       
    </form>

<?php
    //ブラウザに表示
     $sql = 'SELECT * FROM test5_1';
     $stmt = $pdo->query($sql);
     $results = $stmt->fetchAll();
     foreach ($results as $row){
     //$rowの中にはテーブルのカラム名が入る
     echo $row['id'].' ';
     echo $row['name'].' ';
     echo $row['comment'].' ';
     echo $row['date'].'<br>';
 echo "<hr>";
 }
?>

</body>
</html>