<?php
//入力チェック(受信確認処理追加)
if(
//  !isset($_POST["id"]) || $_POST["id"]=="" ||
  !isset($_POST["name"]) || $_POST["name"]=="" ||
  !isset($_POST["lid"]) || $_POST["lid"]==""||
  !isset($_POST["lpw"]) || $_POST["lpw"]==""||
  !isset($_POST["kanri_flg"]) || $_POST["kanri_flg"]==""||
  !isset($_POST["life_flg"]) || $_POST["life_flg"]==""
){
  exit('ParamError');
}

//POSTデータ取得
$name = $_POST["name"];
$lid = $_POST["lid"];
$lpw = $_POST["lpw"];
$kanri_flg = $_POST["kanri_flg"];
$life_flg = $_POST["life_flg"];

if($kanri_flg =="normal"){
    $kanri_flg = 0;
}else{
    $kanri_flg = 1;
};

if($life_flg =="using"){
    $life_flg = 0;
}else{
    $life_flg = 1;
};


//DB接続
try {
  $pdo = new PDO('mysql:dbname=gs_db21;charset=utf8;host=localhost','root','');
} catch (PDOException $e) {
  exit('DbConnectError:'.$e->getMessage());
}

$pw = password_hash($lpw, PASSWORD_DEFAULT);

//画像データの取得
//$ext = pathinfo($_FILES['upfile']['name']);
//$perm = ['gif', 'jpg', 'jpeg', 'png'];

//if($_FILES['upfile']['error'] !==UPLOAD_ERR_OK){
//  $msg = [
//      UPLOAD_ERR_INI_SIZE => 'php.iniのupload_max_filessize制限を超えています',
//      UPLOAD_ERR_FORM_SIZE => 'HTMLのMAX_FILE_SIZE制限を超えています',
//      UPLOAD_ERR_PARTIAL => 'ファイルが一部しかアップロードされていません',
//      UPLOAD_ERR_NO_FILE => 'ファイルがアップロードされませんでした',
//      UPLOAD_ERR_NO_TMP_DIR => '一時保存フォルダが存在しません',
//      UPLOAD_ERR_CANT_WRITE => 'ディスクへの書込に失敗しました',
//      UPLOAD_ERR_EXTENSION => '拡張モジュールによってアップロードが中断されました'
//  ];
//    $err_msg = $msg[$_FILES['upfile']['error']];
//}elseif(!in_array(strtolower($ext['extension']),$perm)){
//    $err_msg = '画像以外のファイルはアップロードできません';
//}elseif(!@getimagesize($_FILES['upfile']['tmp_name'])){
//    $err_msg = 'ファイルの内容が画像ではありません';
//}else{
//    $src = $_FILES['upfile']['tmp_name'];
//    $dest = mb_convert_encoding($_FILES['upfile']['name'],'SJIS-WIN','UTF-8');

//データ登録SQL作成
    $stmt = $pdo->prepare("INSERT INTO gs_user_table(id, name, lid, lpw, kanri_flg, life_flg, photo) VALUES(NULL, :a1, :a2, :a3, :a4, :a5, NULL)");
$stmt->bindValue(':a1', $name);
$stmt->bindValue(':a2', $lid);
$stmt->bindValue(':a3', $pw);
$stmt->bindValue(':a4', $kanri_flg);
$stmt->bindValue(':a5', $life_flg);
//$stmt->bindValue(':a6', $dest);
$status = $stmt->execute();
//}

//データ登録処理後
if($status==false){
  //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
  $error = $stmt->errorInfo();
  exit("QueryError:".$error[2]);
}else{
  //５．index.phpへリダイレクト
  header("Location: index.php");
  exit;
}

?>