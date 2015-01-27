<?php
include_once __DIR__.'/user.php';

class Amministratore {
  public static function login(){
    $db = Database::getInstance();
    $user = preg_replace('/[^a-z0-9]\-/', '', strtolower($_POST['username']));
    $password = sha1($_POST['password']);

    $q = mysql_query(
    "SELECT * FROM admin WHERE user = '$user' AND password = '$password' LIMIT 1;",
    $db->conn) or die(mysql_error());;
    if (mysql_num_rows($q) > 0){
      $obj = mysql_fetch_object($q);

      session_regenerate_id(TRUE);
      unset($_SESSION['id']);

      $_SESSION['admin'] = 'amministratore';
      $_SESSION['level'] = 1;

      return 'ok';
    }
    else {
      die('username o password sbagliata');
    }
  }

  public static function getUser($user) {
    return json_encode(Utente::get($user));
  }

  public static function logout(){
    session_destroy();
    session_regenerate_id();
  }

  public static function listUser(){
    ///return json_encode();
    $content = '
    <table>
      <tr>
        <td>Utente</td>
        <td></td>
        <td></td>
        <td></td>
    </tr>';

    $utenti = Utente::ulist();

    foreach ($utenti as $utente ) {
      $content .= "
      <tr>
        <td>$utente->nome_cognome</td>
        <td><a href='./?a=amici&amp;id=$utente->id' class='button' target='_blank'><i class='fa fa-eye'></i></a></td>
        <td><a href='?p=modifica&amp;id=$utente->id' class='button'><i class='fa fa-pencil'></i></a></td>
        <td><a href='javascript:Admin.elimina($utente->id)' class='button'><i class='fa fa-times'></i></a></td>
      </tr>
      ";
    }
    $content .= '</table>';

    return $content;
  }

}
