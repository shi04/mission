<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title>mission_3-5</title>
    <style>
      form {
        margin-bottom: 20px;
      }
    </style>
  </head>
  <body>

<?php
    $filename = "mission_3-5.txt"; //ファイルを変数に格納
    $name = $_POST["name"];
    $comment = $_POST["comment"];
    $date = date("Y/m/d H:i:s");
    $pass = $_POST["pass"];
    
    //投稿番号の設定
    //ファイル内の最後の投稿番号＋１
    if(file_exists($filename)){
        $num1=file($filename,FILE_IGNORE_NEW_LINES);
        foreach($num1 as $line){
            $num2=explode("<>",$line);
            foreach($num2 as $num22){
            }
        }
        $num=$num2[0]+1;
        //ない場合は１
    }else{
        $num=1;
    }
    
     //保存フォーム
    $newdata=$num."<>".$name."<>".$comment."<>".$date."<>".$pass.PHP_EOL;
    
//投稿機能
  //名前とコメントのデータが送信されたら
  if (!empty($_POST['name']) && !empty($_POST['comment']) && !empty($_POST['pass'])) {
      
    
    // edit_NUMがないときは新規投稿
    if (empty($_POST['edit_NUM'])) {
      // 新規投稿
      //ファイルに追記
      $fp = fopen($filename, "a");

      fwrite($fp, $newdata );
      fclose($fp);
    } else {
        
      // 編集機能
      $editFILE = file($filename);
      $edit_NUM = $_POST['edit_NUM'];
      $epass=$_POST['epass'];
      
      //ファイルの書き換え
      $fp = fopen($filename, "w");
      //ループ処理
      foreach ($editFILE as $line) {
        //<>で区切る 
        $edit_NUMdata = explode("<>",$line);

        //編集番号とパスワードが一致
        if ($edit_NUMdata[0] == $edit_NUM && $edit_NUMdata[4] == $epass) {
          //両方一致したら書き換え
          fwrite($fp, $edit_NUM."<>".$name."<>".$comment."<>".$date."<>".$epass.PHP_EOL);
        } else {
          //一致しなければそのまま
          fwrite($fp, $line);
        }
      }
      fclose($fp);
    }
  }
    
//削除機能
    //削除フォームが送信されたら
    if (!empty($_POST['delete_num']) && !empty($_POST['dpass'])) {

        //入力データの受け取りを変数に代入
        $delete = $_POST['delete_num'];
        $dpass = $_POST['dpass'];
        $delfile = file($filename);

        //ファイルの書き換え
        $fp = fopen($filename,"w");
        //ループ処理
        foreach ($delfile as $line) {
            //<>で区切る 
            $deldata = explode("<>",trim($line));
                
                //削除番号とパスワードが一致
                if ($deldata[0] == $delete && $deldata[4] == $dpass) {
                    //両方一致したら削除
                    fwrite($fp,$deldata[0]." "."削除しました".PHP_EOL);
                }else{
                    //一致しなければそのまま
                    fwrite($fp,$line);
                }
        }
        fclose($fp);
    }

//編集機能
    //編集フォームが送信されたら
    if (!empty($_POST['edit_num'])) {
        $epass=$_POST['epass'];
        $edit = $_POST['edit_num'];
        $editfile = file($filename);

        //ループ処理
        foreach ($editfile as $line) {

        //<>で区切る
        $editdata = explode("<>",$line);
              
            //編集番号と投稿番号が一致したら
            if ($edit == $editdata[0] ) {
                $editnumber = $editdata[0];
                $editname = $editdata[1];
                $editcomment = $editdata[2];
                }
        }
    }

?>

    <form action="" method="post">
      <input type="text" name="name" placeholder="名前" value="<?php if(isset($editname)) {echo $editname;} ?>"><br>
      <input type="text" name="comment" placeholder="コメント" value="<?php if(isset($editcomment)) {echo $editcomment;} ?>"><br>
      <input type="hidden" name="edit_NUM" value="<?php if(isset($editnumber)) {echo $editnumber;} ?>">
      <input type="text" name="pass" placeholder="パスワード">
      <input type="submit" name="submit" value="送信"><br>
      <!--削除--><br>
      <input type="text" name="delete_num" placeholder="削除対象番号"><br>
      <input type="text" name="dpass" placeholder="パスワード">
      <input type="submit" name="delete" value="削除"><br>
    <!--編集--><br>
      <input type="text" name="edit_num" placeholder="編集対象番号"><br>
      <input type="text" name="epass" placeholder="パスワード">
      <input type="submit" value="編集">
    </form>

    <?php
    //ファイルを配列に格納 ブラウザに表示
    $lines=file($filename); 
    foreach($lines as $line){
    $explode=explode("<>",$line);
    echo $explode[0]," ",$explode[1]," ",$explode[2]," ",$explode[3]."<br>";
    }
    ?>
  </body>
</html>