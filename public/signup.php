<?php
// DBに接続
$dbh = new PDO('mysql:host=mysql;dbname=techc', 'root', '');
if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['password'])) {
  // POSTで name と email と password が送られてきた場合はDBへの登録処理をする
  // insertする
 
// 既に同じメールアドレスで登録された会員が存在しないか確認する
  $select_sth = $dbh->prepare("SELECT * FROM users WHERE email = :email ORDER BY id DESC LIMIT 1");
  $select_sth->execute([
    ':email' => $_POST['email'],
  ]);
  $user = $select_sth->fetch();
  if (!empty($user)) {
    // 存在した場合 エラー用のクエリパラメータ付き会員登録画面にリダイレクトする
    header("HTTP/1.1 302 Found");
    header("Location: ./signup.php?duplicate_email=1");
    return;
   }

// ソルトを決める(ランダム)
 // $salt = bin2hex(random_bytes(32));

  //$password_hash = $_POST['password'];
  //foreach (range(1, 100) as $count) {
    // ソルトを追加してハッシュ化... を100回繰り返す
    //$password_hash = hash('sha256', $password_hash . $salt);
  //}

  $insert_sth = $dbh->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
  $insert_sth->execute([
    ':name' => $_POST['name'],
    ':email' => $_POST['email'],
    //':password' => hash('sha256', $_POST['password'] . $salt) . $salt,
    ':password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
  ]);
  
  // 処理が終わったら完了画面にリダイレクト
  header("HTTP/1.1 302 Found");
  header("Location: /signup_finish.php");
  return;
}
?>
<h1>会員登録</h1>

会員登録済の人は<a href="/login.php">ログイン</a>しましょう。
<hr>

<!-- 登録フォーム -->
<form method="POST">
  <!-- input要素のtype属性は全部textでも動くが、適切なものに設定すると利用者は使いやすい -->
  <label>
    名前:
    <input type="text" name="name">
  </label>
  <br>
  <label>
    メールアドレス:
    <input type="email" name="email">
  </label>
  <br>
  <label>
    パスワード:
    <input type="password" name="password" min="6" autocomplete="new-password">
  </label>
  <br>
  <button type="submit">決定</button>
</form>
<?php if(!empty($_GET['duplicate_email'])):?>
<div style="color: red;">
  入力されたメールアドレスは既に使われています
</div>
<?php endif; ?>


