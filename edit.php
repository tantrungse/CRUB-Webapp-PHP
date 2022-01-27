<?php
require_once "pdo.php";
session_start();
if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password'])
    && isset($_POST['user_id'])) {

  // Data validation
  if(strlen($_POST['name']) < 1 || strlen($_POST['password']) < 1) {
    $_SESSION['error'] = 'Missing data';
    header("Location: add.php");
    return;
  }
  if(strpos($_POST['email'], '@') === false) {
    $_SESSION['error'] = 'Bad data';
    header("Location: add.php");
    return;
  }
  $sql = "UPDATE users SET name = :name, email = :email, password = :password
          WHERE user_id = :user_id";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(
    ':name' => $_POST['name'],
    ':email' => $_POST['email'],
    ':password' => $_POST['password'],
    ':user_id' => $_POST['user_id']));
  $_SESSION['success'] = "Record updated";
  header('Location: index.php');
  return;
}
// Guardian: Make sure that user_id is present
if(!isset($_GET['user_id'])) {
  $_SESSION['error'] = "Missing user_id";
  header("Location: index.php");
  return;
}
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['user_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if($row === false) {
  $_SESSION['error'] = 'Bad value for user_id';
  header('Location: index.php');
  return;
}
$name = htmlentities($row['name']);
$email = htmlentities($row['email']);
$pwd = htmlentities($row['password']);
$user_id = $row['user_id'];
?>
<p>Edit User</p>
<form method="post">
<p>Name:
<input type="text" name="name" value="<?= $name ?>"></p>
<p>Email:
<input type="text" name="email" value="<?= $email ?>"></p>
<p>Password:
<input type="text" name="password" value="<?= $pwd ?>"></p>
<input type="hidden" name="user_id" value="<?= $user_id ?>">
<p><input type="submit" value="Update"/>
</form>
