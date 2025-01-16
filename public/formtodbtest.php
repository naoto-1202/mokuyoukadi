<?php
$dbh = new PDO('mysql:host=mysql;dbname=techc', 'root', '');

if (isset($_POST['body'])) {
  // POSTで送られてくるフォームパラメータ body がある場合
 
  // insertする
  $insert_sth = $dbh->prepare("INSERT INTO hogehoge (text) VALUES (:body)");
  $insert_sth->execute([
      ':body' => $_POST['body'],
  ]);
 

  header("HTTP/1.1 302 Found");
  header("Location: ./formtodbtest.php");
  return;
}
  $select_sth = $dbh->prepare('SELECT * FROM hogehoge ORDER BY created_at DESC');
  $select_sth->execute();
  // 処理が終わったらリダイレクトする
  // リダイレクトしないと，リロード時にまた同じ内容でPOSTすることにな
  
?>
  
  <!-- フォームのPOST先はこのファイル自身にする -->
  
<form method="POST" action="./formtodbtest.php">
  <textarea name="body"></textarea> 
  <button type="submit">送信</button>
</form>  

<?php foreach($select_sth as $log): ?>
   <dl style="margin-bottom: 1em; padding-bottom: 1em; border-bottom: 1px solid #ccc;">
       <dt>送信日時</dt>
       <dd><?= $log['created_at'] ?></dd>
       <dt>送信内容</dt>
       <dd><?= nl2br(htmlspecialchars($log['text'])) ?></dd>
   </dl>
<?php endforeach ?> 

