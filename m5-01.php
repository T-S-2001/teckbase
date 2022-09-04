<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-01</title>
</head>
<body>
    
<?php
$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

$sql1 = "CREATE TABLE IF NOT EXISTS mission5"
    ." ("
    . "id INT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT,"
    . "date TEXT,"
    . "pass TEXT"
    .");";
    $stmt = $pdo->query($sql1);
//sql1でテーブル"mission5"を作成

if(isset($_POST["submit3"])&&!empty($_POST["editnum"])){
    $editnum=$_POST["editnum"];
    $sql5= 'SELECT * FROM mission5';
    $stmt = $pdo->query($sql5);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        if($editnum==$row['id']){
        $name2=$row['name'];
        $comment2=$row['comment'];
        $pass2=$row['pass'];
        $pass3=$pass2;
        }
    }
}

//sql5でカラムから取得、編集元を表示する。

?>
    
<form method="post" action="">
    <input type = "text" name = "name" placeholder = "名前を入力してください"value=
    <?php if(isset($editnum)&&isset($name2)&&!empty($pass2)&&$pass2==$_POST["pass3"]){
        echo $name2;
    }
    ?>><br>
    <input type = "text" name = "comment" placeholder = "コメントを入力してください"value=
    <?php if(isset($editnum)&&isset($comment2)&&!empty($pass2)&&$pass2==$_POST["pass3"]){
        echo $comment2;
    }
    ?>><br>
    <input type = "text" name = "pass" placeholder = "パスワードを入力してください"value=
    <?php if(isset($editnum)&&isset($pass2)&&!empty($pass2)&&$pass2==$_POST["pass3"]){
        echo $pass2;
    }
    ?>>
    <input type = "submit" name ="submit1"><br>
    <input type = "number" name = "delnum" placeholder="削除番号指定用"><br>
    <input type = "text" name = "pass2" placeholder = "パスワードを入力してください">
    <input type = "submit" name = "submit2" value = "削除"> <br>
    <input type = "number" name = "editnum" placeholder="編集番号指定用"> 
    <input type = "hidden" name = "encheck" value=
    <?php if(isset($editnum)&&!empty($pass2)&&$pass2==$_POST["pass3"]){
        echo $editnum;
    }
    ?>> <br>
    <input type = "text" name = "pass3" placeholder = "パスワードを入力してください">
    <input type = "submit" name = "submit3" value = "編集"> 
   
</form>

<?php
if(!empty($_POST["name"])&&!empty($_POST["comment"])&&isset($_POST["submit1"])&&empty($_POST["encheck"])){
    $sql2 = $pdo -> prepare("INSERT INTO mission5 (id, name, comment, date, pass) VALUES (:id, :name, :comment, :date, :pass)");
    $sql2 -> bindParam(':id', $id, PDO::PARAM_STR);
    $sql2 -> bindParam(':name', $name, PDO::PARAM_STR);
    $sql2 -> bindParam(':comment', $comment, PDO::PARAM_STR);
    $sql2 -> bindParam(':date', $date, PDO::PARAM_STR);
    $sql2 -> bindParam(':pass', $pass, PDO::PARAM_STR);
    
    $sql3 = 'SELECT * FROM mission5';
    $stmt = $pdo->query($sql3);
    $results = $stmt->fetchAll();
    $id=count($results)+1;
    
    $name=$_POST["name"];
    $comment=$_POST["comment"]; 
    $date=date('Y年m月d日 H:i:s');
    $pass=$_POST["pass"];
    $sql2 -> execute();
}
//sql2でテーブルに書き込む

//暫定的に編集先取得にsql7とする
elseif(!empty($_POST["delnum"])&&isset($_POST["submit2"])){
    $id4=$_POST["delnum"];
    $pass2=$_POST["pass2"];
    $sql7 = 'SELECT * FROM mission5';
    $stmt = $pdo->query($sql7);
    $results = $stmt->fetchAll();
    $id=count($results);

    foreach ($results as $row){
        $num4= $row['id'];
        $name4= $row['name'];
        $comment4= $row['comment'];
        $date4= $row['date'];
        $pass4= $row["pass"];
        if($id4==$num4&&!empty($pass4)&&$pass4==$pass2){
            $key=1;
        }    
            $sql4='UPDATE mission5 SET name=:name,comment=:comment,date=:date,pass=:pass where id=:id';
            $stmt=$pdo->prepare($sql4);
            $stmt->bindParam(':name', $name4, PDO::PARAM_STR);
            $stmt->bindParam(':comment', $comment4, PDO::PARAM_STR);
            $stmt->bindParam(':date', $date4, PDO::PARAM_STR);
            $stmt->bindParam(':pass', $pass4, PDO::PARAM_STR);
            $numa=$num4-1;
            $stmt->bindParam(':id', $numa, PDO::PARAM_INT);
        if($num4>$id4&&isset($key)){
            $stmt->execute();
        }
            $sql4='delete from mission5 where id=:id';
            $stmt=$pdo->prepare($sql4);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            if(isset($key)){
            $stmt->execute();
            }
    }
}
//sql4で削除
//ではなく、繰り上げ編集を行う。使用変数には4をつける。

elseif(!empty($_POST["encheck"])){
    
    $id3=$_POST["encheck"]; 
    $name3=$_POST["name"];
    $comment3=$_POST["comment"];
    $date3=date('Y年m月d日 H:i:s');
    $pass3=$_POST["pass"];
    $passcheck3=$_POST["pass3"];

    $sql6= 'UPDATE mission5 SET name=:name,comment=:comment,date=:date, pass=:pass WHERE id=:id';
    $stmt=$pdo->prepare($sql6);
    $stmt->bindParam(':name', $name3, PDO::PARAM_STR);
    $stmt->bindParam(':comment', $comment3, PDO::PARAM_STR);
    $stmt->bindParam(':date', $date3, PDO::PARAM_STR);
    $stmt->bindParam(':pass', $pass3, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id3, PDO::PARAM_INT);
    $stmt->execute();
}
//sql6で編集させる

$sql3 = 'SELECT * FROM mission5';
$stmt = $pdo->query($sql3);
$results = $stmt->fetchAll();
foreach ($results as $row){
    $num= $row['id'];
    $name= $row['name'];
    $comment= $row['comment'];
    $date= $row['date'];
    $pass= $row["pass"];
    $str="$num $name $comment $date $pass ";
    echo "$str <br><hr>";
}
//sql3で"mission5"の内容を表示
?>

