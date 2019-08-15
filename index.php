<?php
// phpinfo();
// exit;

require_once(__DIR__ . '/config.php');
require_once(__DIR__ . '/User.php');

$userCtl = new \Coji\Lib\User();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $userCtl->createUser();
}

$users = $userCtl->getAll();

if(isset($_GET['delete'])) {
  $userCtl->userDelete();
}

 ?>

 <!DOCTYPE html>
 <html lang="ja">
   <head>
     <meta charset="utf-8">
     <title>電子DM配信システム：ユーザー登録</title>
     <link rel="stylesheet" href="/css/master.css">
   </head>
   <body>
     <h1>ユーザー登録</h1>
     <h2>登録内容入力</h2>
     <form class="" action="" method="post">
       <table>
         <tr>
           <td>氏名</td>
           <td>
             氏：<input type="text" name="last_name" value="">　　名：<input type="text" name="first_name" value="">
           </td>
         </tr>
         <tr>
           <td>権限</td>
           <td>
             <input type="radio" name="role" value="1">管理者
             <input type="radio" name="role" value="2">編集者
             <input type="radio" name="role" value="3">閲覧者
           </td>
         </tr>
         <tr>
           <td>メールアドレス</td>
           <td>
             <input type="text" name="mail" value=""><br>
           </td>
         </tr>
         <tr>
           <td>パスワード</td>
           <td>
             <input type="text" name="password" value=""><br>
           </td>
         </tr>
       </table>
       <input type="hidden" name="created_at" value="<?php echo date('Y-m-d H:i:s'); ?>"><br>
       <br>
       <input type="submit" name="" value="登録する">
     </form>


     <h2>ユーザー一覧</h2>
     <ul>
       <?php foreach ($users as $user) : ?>
         <?php if($user->delete_flg === '1'): ?>
           <?php continue; ?>
         <?php else: ?>
           <li>id：<?= h($user->id) ?>　氏名：<?= h($user->last_name) ?><?= h($user->first_name) ?>　mail:<?= h($user->mail) ?>　登録日：<?= h($user->created_at) ?>
             <span class="user_delete" data-id="<?= h($user->id) ?>">[x]</span>
           </li>
         <?php endif; ?>
       <?php endforeach; ?>
     </ul>

   <script>
    'use strict';

    // ユーザーデリート確認
    const users_delete = document.getElementsByClassName('user_delete');

    for (let i = 0; i < users_delete.length; i++) {
      users_delete[i].addEventListener('click', () => {
        let deleteUserId = users_delete[i].dataset.id;
        let deleteUrl = `\index.php?delete=${deleteUserId}`;
        let delete_confirm = confirm(`id：${deleteUserId}  を削除しますか？`);
        if(delete_confirm) {
          location.href = deleteUrl;
        }
      });
    }

   </script>
   </body>
 </html>
