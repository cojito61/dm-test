<?php

namespace Coji\Lib;

class User {
  private $_db;

  public function __construct() {
    try {
      $this->_db = new \PDO(DSN, DB_USERNAME, DB_PASSWORD);
      $this->_db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    } catch (\PDOException $e) {
      echo $e->getMessage();
      exit;
    }
  }

  private function _checkError() {
    //未入力チェック
    if (
      empty($_POST['last_name']) ||
      empty($_POST['first_name']) ||
      empty($_POST['role']) ||
      empty($_POST['mail']) ||
      empty($_POST['password']) ||
      empty($_POST['created_at'])
    ) {
      echo '未入力の項目があります';
      exit;
    }

    //email形式チェック
    if(!filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL)) {
      echo 'emailの形式が不正です';
      exit;
    }

    //パスワード形式チェック
    //8文字以上、半角英数字
    if(mb_strlen($_POST['password']) < 8) {
      echo 'passwordは8文字以上です';
      exit;
    }

    if(preg_match('/^[a-zA-Z0-9]+$/' , $_POST['password']) !== 1) {
      echo 'passwordの形式が不正です';
      exit;
    }

  }

  public function createUser() {
    $this->_checkError();
    $sql = "insert into users (last_name, first_name, role, mail, password, created_at) values (:last_name, :first_name, :role, :mail, :password, :created_at)";
    $stmt = $this->_db->prepare($sql);
    $stmt->execute([
      ':last_name' => $_POST['last_name'],
      ':first_name' => $_POST['first_name'],
      ':role' => $_POST['role'],
      ':mail' => $_POST['mail'],
      ':password' => $_POST['password'],
      ':created_at' => $_POST['created_at']
    ]);

    header('Location: http://' . $_SERVER['HTTP_HOST']);
  }

  public function getAll() {
    $stmt = $this->_db->query("select * from users order by id desc");
    return $stmt->fetchAll(\PDO::FETCH_OBJ);
  }

  public function userDelete() {
    $delete_id = $_GET['delete'];
    $now = date('Y-m-d H:i:s');
    $sql = "update users set delete_flg = :delete_flg, updated_at = :now where id = :delete_id";
    var_dump($sql);
    $stmt = $this->_db->prepare($sql);
    $stmt->execute([
      ':delete_flg' => 1,
      ':now' => $now,
      ':delete_id' => $delete_id
    ]);

    header('Location: http://' . $_SERVER['HTTP_HOST']);
  }



}
