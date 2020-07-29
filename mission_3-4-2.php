<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_3-4-2</title>
</head>

<body> 
    
<?php 
    $name = $_POST["name"];
    $comment = $_POST["comment"];
    $date = date("Y/m/d H:i:s");
    $filename = "mission_3-4.txt"; //ファイルを変数に格納
    
    //投稿番号の設定
    //ファイル内の行数がある場合に＋１
    if(file_exists($filename)){
        $num=count(file($filename))+1;
        //ない場合は１
    }else{
        $num=1;
    }
    
     //保存フォーム
    $newdata=$num."<>".$name."<>".$comment."<>".$date.PHP_EOL;
    
     //新規か編集か判断する
     //名前とコメントのデータが空じゃなかったら
    if(!empty($_POST["name"]) && !empty($_POST["comment"])){
        
        if(empty($_POST{"edit_NUM"})){
            //新規投稿
        $fp = fopen($filename,"a"); //ファイルに追記
        fwrite($fp,$newdata);
        fclose($fp);
        
        }else{
            //編集
         $edit_NUM=$_POST["edit_NUM"];
         $editFILE=file($filename);
         
         $fp=fopen($filename,"w"); //ファイルの書き換え
         
         //ループ処理
         for($i=0;$i<count($editFILE);$i++);
             $edit_NUMdata=explode("<>",$editFILE[$i]);
             
             //一致したら　書き換える
             if($edit_NUMdata[0]==$edit_NUM){
                 fwrite($fp,$edit_NUM."<>".$name."<>".$comment."<>".$date.PHP_EOL);
             }else{
                 //一致しなかったらそのまま
                 fwrite($fp,$editFILE[$i]);
             }
         }
         fclose($fp);
    
    }
    
           
    //削除対象番号が送信されたら
    if(!empty($_POST["delete_num"])){
        $delete= $_POST["delete_num"];
        
        $delfile=file($filename);
        //ファイルの書き換え
        $fp=fopen($filename,"w");
        //ループ処理
        for($i=0;$i<count($delfile);$i++){
            //<>で区切る 
            $deldata=explode("<>",$delfile[$i]);
            
            //削除番号と行番号が一致しない
            if($deldata[0]!=$delete){
                fwrite($fp,$delfile[$i]);
            }
        }
        fclose($fp);
    }
    
     //編集対象番号が送信されたら
    if(!empty($_POST["edit_num"])){
        $edit=$_POST["edit_num"];
        
        $editfile=file($filename);
        
        //ループ処理
        for($i=0;$i<count($editfile);$i++){
            //<>で区切る 
            $editdata=explode("<>",$editfile[$i]);
            
            //編集番号と行番号が一致したら
            if($edit[0]==$edit){
                $edit_num=$editdata[0];
                $editname=$editdata[1];
                $editcomment=$editdata[2];
                
            }
        }
    }
    ?>
    
<!--フォームからpost送信-->
<!--フォーム：「名前」「コメント」の入力と「送信」ボタンが1つあるフォームを作成-->
    <form action="" method="post">
        名前<br>
        <input type="text" name="name" value="<?php if(!empty($editname)){ echo $editname;}?>"><br>
    
        コメント<br>
        <input type="text" name="comment" value="<?php if(!empty($editcomment)){echo$editcomment;}?>">
        <input type="hidden" name=edit_NUM"<?php if(!empty($edit_num)){ echo $edit_num;}?>"
        <!--送信-->
        <input type="submit" name="submit" value="送信"><br>
        <br><!--削除-->
        <input type="text" name="delete_num" placeholder="削除対象番号">
        <input type="submit" name="delete" value="削除"><br>
        <br><!--編集-->
        <input type="text" name="edit_num" placeholder="編集対象番号">
        <input type="submit" name="edit" value="編集">
        <br>
    </form>
    
    <?php
    //ファイルを配列に格納し、さらに変数に格納 ブラウザに表示
    $lines=file($filename); 
    foreach($lines as $line){
    $explode=explode("<>",$line);
    echo $explode[0]," ",$explode[1]," ",$explode[2]," ",$explode[3]."<br>";
    }
    ?>

</body>
</html>