
<!DOCTYPE html>
<html lang ="ja">
    <head>
        <meta charset="UTF-8">
        <title>mission5-1</title>
    </head>
    <body>
    
 <?php
 //DB接続をする
	$dsn = 'データベース名';
	$user = 'ユーザー名';
	$password = 'パスワード';
    $pdo0 = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));



 //INSERT文でテーブルにデータをいれる。
 if(isset($_POST['name1']) && isset($_POST['comment1'])){
    $name = $_POST['name1'];
    $comment = $_POST['comment1'];
    $pass1 = $_POST['password1'];
    $date1 = date("Y/m/d H:i:s");
    
    //名前、コメント、パスワードが空ではない　＆　hiddenの編集番号がない時
   if($name!=="" && $comment!=="" && $pass1!=="" && $_POST['editnumber']==""){
     $sql = $pdo0 -> prepare
     ("INSERT INTO tbtest7 (name, comment, pass, date) VALUES (:n, :com, :pass, :date)");
     
     $sql -> bindParam(':n', $name, PDO::PARAM_STR);
     $sql -> bindParam(':com', $comment, PDO::PARAM_STR);
     $sql -> bindParam(':pass', $pass1, PDO::PARAM_INT);
     $sql -> bindParam(':date', $date1, PDO::PARAM_STR);
     
     
     $sql -> execute();
    }
 }
 ?>

 <?php
 
 //削除番号と投稿番号が同じ時のパスワードを取得
 //そのパスワードが入力したものと同じなら削除する。

 if(isset($_POST['number1'])){
    $delete_id = $_POST['number1'];
    if($delete_id!==""){    //削除番号が空でない時
        
       $stmt1 ='SELECT * FROM tbtest7';
        $stmt1 = $pdo0 -> query($stmt1);
        foreach($stmt1 as $d_row){
            $r_num1=$d_row['id'];
           if($delete_id == $r_num1){
               $delete_pass = $d_row['pass']; //パスワードを取得。
               /*
               echo  $delete_pass;
               */
           }else continue;
        }
        if($_POST['password2']!==""){ //パスワードが入力された時
              $d_pass = $_POST['password2'];
            if($d_pass == $delete_pass){  //パスワードが一致した時に削除
                $sql2 = $pdo0 -> prepare("DELETE FROM tbtest7 WHERE id = :id");
                $sql2 -> bindParam(':id', $delete_id , PDO::PARAM_INT);
                $sql2 -> execute(); 
            }
         }      
    } 
}

 //編集番号と投稿番号が同じかつ、パスワードが一致する時のみに、名前とコメントとパスワードを取得。・

 
 if(isset($_POST['number2'])){
    $e_id = $_POST['number2'];   
    if($e_id!==""){       //編集番号が入力された時     
        $stmt0 ='SELECT * FROM tbtest7';
        $stmt0 = $pdo0 -> query($stmt0);
        foreach($stmt0 as $row0){
            $r_num = $row0['id'];
           if($e_id == $r_num){
               $edit_num = $row0['id'];
               $edit_name = $row0['name'];
               $edit_com = $row0['comment'];
               $e_pass = $row0['pass'];
                //該当する投稿情報を取得。
             /* echo $e_name. $e_com. $e_pass; */
           }else continue; 
        } 
        if($_POST['password3'] !== "") {  //パスワードを入れた時
            $editpass = $_POST['password3'];
            if($editpass == $e_pass){   //パスワードが一致した時
              /*
                $stmt5 = 'SELCT * FROM tbtest7';
              $stmt5 = $pdo0 -> query($stmt5);
              foreach($stmt5 as $row5){
                  $r_num3 = $row5['id'];
              */
              $e_num = $edit_num ;
              $e_name = $edit_name ;
              $e_com = $edit_com ;
              }
            }
        }   
    } 
 




 //UPDATE文で書き換えをする。
 //editnumberがある時にUPDATE!
 
 if(isset($_POST['editnumber'])){
     $editnum = $_POST['editnumber'];
     if($editnum!=="" && $_POST['name1']!=="" && $_POST['comment1']!==""){
         $sql3 = "UPDATE tbtest7 SET name = :name, comment = :comment, date = :date  WHERE id = :id";
         $stmt1 = $pdo0 -> prepare($sql3);
         $editname = $_POST['name1'];
         $editcom  = $_POST['comment1'];
         $editdate = date("Y/m/d H:i:s");
         $params = array(':name'=> $editname,':comment'=>$editcom,':id'=>$editnum, ':date'=>$editdate);
         $stmt1 -> execute($params);
    }
}


 ?>


    <h1>掲示板テーマ</h1>
<h2>入力フォーム</h2>
<?php echo "名前とコメントと好きな数字をパスワードにして入力してください。" ."<br>" .
      "編集の場合は、表示された投稿に修正を加えて再入力して下さい。(パスワードは不要）" ."<br>"."<br>";
?>
    <form action ="mission5-1.php"method="POST">
        <input type="text"name="name1"placeholder="名前を入れてください"value
        =<?php if(isset($e_name)){
          echo $e_name ;
        }
        ?>>
        
        <input type="text"name="comment1"placeholder ="コメントを入れてください"value
        =<?php if(isset($e_com)){
            echo $e_com ;
        }
        ?>>
        
        <input type="number"name="password1"placeholder="パスワードを入れてください">
        <input type="hidden"name="editnumber"value=
        <?php if(isset($e_num )){
        echo  $e_num ;
        }
         ?>>
        <input type="submit"value="送信">
<h2>削除番号入力</h2>
<?php echo "削除したい投稿の番号と自分で設定したパスワードを入力してください。"."<br>" ."<br>"?>

        <input type="number"name="number1"placeholder ="削除番号を入力してください">
        <input type="number"name="password2"placeholder="パスワードを入れてください">
        <input type="submit"value="削除">
<h2>編集番号入力</h2>
<?php echo "編集したい投稿の番号と自分で設定したパスワードを入力してください。"."<br>"."<br>" ?>
        <input type="number"name="number2"placeholder ="編集番号を入力してください">
        <input type="number"name="password3"placeholder="パスワードを入れてください">
        <input type="submit"value="編集">
    </form>
    
<?php
 //SELECT文で取得したデータを表示(tbtest7）
   $stmt2='SELECT * FROM tbtest7';
   $stmt2=$pdo0->query($stmt2);
   echo  "<br>" ."<hr>" ;
   foreach($stmt2 as $row2){
     echo $row2['id'].':'.
          $row2['name'].' '.
          $row2['comment'].' '.
          $row2['date'].' '.
          /*
          $row2['pass'].' '.
          */
          '<br>';
    }
 ?>
    </body>
</html>